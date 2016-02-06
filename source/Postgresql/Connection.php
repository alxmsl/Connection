<?php
/*
 * Copyright 2015-2016 Alexey Maslov <alexey.y.maslov@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace alxmsl\Connection\Postgresql;

use alxmsl\Connection\DbConnection;
use alxmsl\Connection\Postgresql\Exception\ConnectionBusyException;
use alxmsl\Connection\Postgresql\Exception\DuplicateEntryException;
use alxmsl\Connection\Postgresql\Exception\DuplicateTableException;
use alxmsl\Connection\Postgresql\Exception\DuplicateTypeException;
use alxmsl\Connection\Postgresql\Exception\QueryException;
use alxmsl\Connection\Postgresql\Exception\RaiseException;
use alxmsl\Connection\Postgresql\Exception\TriesOverConnectException;
use alxmsl\Connection\Postgresql\Exception\UndefinedTableException;

/**
 * Postgresql connection instance
 * @author alxmsl
 */
final class Connection extends DbConnection {
    /**
     * Postgres error codes
     */
    const CODE_DUPLICATE_ENTRY = '23505',
          CODE_UNDEFINED_TABLE = '42P01',
          CODE_DUPLICATE_TABLE = '42P07',
          CODE_DUPLICATE_TYPE  = '42710',
          CODE_RAISE_EXCEPTION = 'P0001';

    /**
     * Postgres queries
     */
    const SQL_QUERY_BEGIN    = 'BEGIN',
          SQL_QUERY_COMMIT   = 'COMMIT',
          SQL_QUERY_ROLLBACK = 'ROLLBACK';

    /**
     * @var resource connection resource
     */
    private $Resource = null;

    /**
     * @var bool need connection busy checkup
     */
    private $needBusyCheckup = false;

    /**
     * Connect to postgres instance
     * @return bool connection result
     * @throws TriesOverConnectException when connection tries was over
     */
    public function connect() {
        $count = 0;
        do {
            $count += 1;
            $this->Resource = pg_connect($this->getConnectionString());
            if ($this->Resource !== false) {
                return true;
            }
        } while ($count < $this->getConnectTries());

        throw new TriesOverConnectException();
    }

    /**
     * Disconnect from postgres instance
     */
    public function disconnect() {
        if ($this->Resource) {
            pg_close($this->Resource);
            $this->Resource = null;
        }
    }

    /**
     * Reconnect to postgres instance
     */
    public function reconnect() {
        $this->disconnect();
        return $this->connect();
    }

    /**
     * Setter connection busy checkup setting
     * @param boolean $needBusyCheckup need connection busy checkup
     * @return Connection connection instance
     */
    public function setNeedBusyCheckup($needBusyCheckup) {
        $this->needBusyCheckup = (bool) $needBusyCheckup;
        return $this;
    }

    /**
     * Getter connection busy checkup setting
     * @return boolean connection busy checkup
     */
    public function needBusyCheckup() {
        return $this->needBusyCheckup;
    }

    /**
     * Build postgres connection string
     * @return string connection string
     */
    private function getConnectionString() {
        $parameters = array(
            'host='             . $this->getHost(),
            'connect_timeout='  . $this->getConnectTimeout(),
            'user='             . $this->getUserName(),
        );
        $this->hasPort()        && $parameters[] = 'port='      . $this->getPort();
        $this->hasPassword()    && $parameters[] = 'password='  . $this->getPassword();
        $this->hasDatabase()    && $parameters[] = 'dbname='    . $this->getDatabase();

        return implode(' ', $parameters);
    }

    /**
     * Send query with/without template
     * @param string $query query string or template
     * @param array|null $data query parameters
     */
    private function sendQuery($query, array $data = null) {
        if (!is_null($data)) {
            $Template = new QueryTemplate();
            $Template->load($query);
            pg_send_query($this->Resource, $Template->parse($data));
        } else {
            pg_send_query($this->Resource, $query);
        }
    }

    /**
     * Complete query
     * @param string $query query string
     * @param array|null $data query parameters
     * @return QueryResult postgres query result
     * @throws DuplicateEntryException when entry was duplicated
     * @throws DuplicateTableException when table was duplicated
     * @throws ConnectionBusyException when connection busy by another request
     * @throws UndefinedTableException when try to query undefined table
     * @throws QueryException for other reasons
     */
    public function query($query, array $data = null) {
        if (is_null($this->Resource)) {
            $this->connect();
        }

        $busy = false;
        if ($this->needBusyCheckup()) {
            $busy = pg_connection_busy($this->Resource);
        }
        if (!$busy) {
            $this->sendQuery($query, $data);
            $Result = pg_get_result($this->Resource);
            $Error = pg_result_error($Result);
            if (!empty($Error)) {
                $errorMessage = pg_errormessage($this->Resource);
                $errorCode = pg_result_error_field($Result, PGSQL_DIAG_SQLSTATE);
                switch ($errorCode) {
                    case self::CODE_DUPLICATE_ENTRY:
                        throw new DuplicateEntryException($errorMessage);
                    case self::CODE_UNDEFINED_TABLE:
                        throw new UndefinedTableException($errorMessage);
                    case self::CODE_DUPLICATE_TABLE:
                        throw new DuplicateTableException($errorMessage);
                    case self::CODE_DUPLICATE_TYPE:
                        throw new DuplicateTypeException($errorMessage);
                    case self::CODE_RAISE_EXCEPTION:
                        throw new RaiseException($errorMessage);
                    default:
                        throw new QueryException(sprintf(
                            "%s QUERY: %s CODE: %s",
                            $errorMessage,
                            $query,
                            $errorCode
                        ));
                }
            } else {
                return new QueryResult($Result);
            }
        } else {
            throw new ConnectionBusyException();
        }
    }

    /**
     * Start transaction implementation
     */
    public function begin() {
        $this->query(self::SQL_QUERY_BEGIN, null);
    }

    /**
     * Accept transaction implementation
     */
    public function commit() {
        $this->query(self::SQL_QUERY_COMMIT, null);
    }

    /**
     * Cancel transaction implementation
     */
    public function rollback() {
        $this->query(self::SQL_QUERY_ROLLBACK, null);
    }
}
