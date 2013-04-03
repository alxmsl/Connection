<?php

namespace Connection\Postgresql\Client;

use Connection\TransactionalConnection,
    Connection\DbException,
    Connection\DbConnection;

/**
 * Postgresql connection instance
 * @author alxmsl
 * @date 4/1/13
 */
final class Connection extends DbConnection {
    /**
     * Postgres error codes
     */
    const   CODE_DUPLICATE_ENTRY = '23505',
            CODE_UNDEFINED_TABLE = '42P01',
            CODE_DUPLICATE_TABLE = '42P07';

    /**
     * Postgres queries
     */
    const   SQL_QUERY_BEGIN     = 'BEGIN',
            SQL_QUERY_COMMIT    = 'COMMIT',
            SQL_QUERY_ROLLBACK  = 'ROLLBACK';

    /**
     * @var resource connection resource
     */
    private $Resource = null;

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
     * Build postgres connection string
     * @return string connection string
     */
    private function getConnectionString() {
        $parameters = array(
            'host=' . $this->getHost(),
            'port=' . $this->getPort(),
            'connect_timeout=' . $this->getConnectTimeout(),
            'user=' . $this->getUserName(),
            'password=' . $this->getPassword(),
            'dbname=' . $this->getDatabase(),
        );
        return implode(' ', $parameters);
    }

    /**
     * Complete query
     * @param string $query query string
     * @return QueryResult postgres query result
     * @throws DuplicateEntryException when entry was duplicated
     * @throws DuplicateTableException when table was duplicated
     * @throws ConnectionBusyException when connection busy by another request
     * @throws UndefinedTableException when try to query undefined table
     * @throws QueryException for other reasons
     */
    public function query($query) {
        if (!pg_connection_busy($this->Resource)) {
            pg_send_query($this->Resource, $query);
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
                    default:
                        throw new QueryException($errorMessage . ':' . $query);
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
        $this->query(self::SQL_QUERY_BEGIN);
    }

    /**
     * Accept transaction implementation
     */
    public function commit() {
        $this->query(self::SQL_QUERY_COMMIT);
    }

    /**
     * Cancel transaction implementation
     */
    public function rollback() {
        $this->query(self::SQL_QUERY_ROLLBACK);
    }
}

/**
 * Base postgres exception class
 */
class PostgresException extends DbException {}

/**
 * Postgres connection exceptions
 */
class ConnectException extends PostgresException {}

/**
 * Connection tries over exception
 */
final class TriesOverConnectException extends ConnectException {}

/**
 * Connection busy on the current query
 */
final class ConnectionBusyException extends ConnectException {}

/**
 * Base query exception
 */
class QueryException extends PostgresException {}

/**
 * Entry was duplicate exception
 */
final class DuplicateEntryException extends QueryException {}

/**
 * Query to undefined table exception
 */
final class UndefinedTableException extends QueryException {}

/**
 * Table was duplicate exception
 */
final class DuplicateTableException extends QueryException {}