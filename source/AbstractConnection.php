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

namespace alxmsl\Connection;

use InvalidArgumentException;

/**
 * @author alxmsl
 */
abstract class AbstractConnection implements ConnectionInterface {
    /**
     * Missing connection port constant
     */
    const MISSING_PORT = -1;

    /**
     * @var string instance hostname
     */
    private $host = '';

    /**
     * @var int instance port
     */
    private $port = self::MISSING_PORT;

    /**
     * @var float instance connect timeout
     */
    private $connectTimeout = 0;

    /**
     * @var int number of tries for connect to instance
     */
    private $connectTries = 1;

    /**
     * @var bool use persistence connection, or not
     */
    private $persistent = false;

    /**
     * Setter of connection timeout parameter
     * @param float $connectTimeout connection timeout value
     * @throws \InvalidArgumentException
     * @return AbstractConnection self
     */
    public function setConnectTimeout($connectTimeout) {
        $this->connectTimeout = (float) $connectTimeout;
        if ($this->connectTimeout < 0) {
            throw new InvalidArgumentException('connect timeout < 0');
        }
        return $this;
    }

    /**
     * Getter of connection timeout exception
     * @return float connect timeout value
     */
    public function getConnectTimeout() {
        return $this->connectTimeout;
    }

    /**
     * Setter of number of connection tries
     * @param int $connectTries connection tries count
     * @throws \InvalidArgumentException
     * @return AbstractConnection self
     */
    public function setConnectTries($connectTries) {
        $this->connectTries = (int) $connectTries;
        if ($this->connectTries < 1) {
            throw new InvalidArgumentException('connect tries < 1');
        }
        return $this;
    }

    /**
     * Getter of number of connection tries
     * @return int connection tries count
     */
    public function getConnectTries() {
        return $this->connectTries;
    }

    /**
     * Setter for instance host
     * @param string $host instance host
     * @throws \InvalidArgumentException
     * @return AbstractConnection self
     */
    public function setHost($host) {
        $this->host = (string) $host;
        if (empty($this->host)) {
            throw new InvalidArgumentException('empty host');
        }
        return $this;
    }

    /**
     * Getter of instance hostname
     * @return string instance host
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * Setter of instance connection port
     * @param int $port instance connection port
     * @throws \InvalidArgumentException
     * @return AbstractConnection self
     */
    public function setPort($port) {
        $this->port = $port;
        if ($this->port < 0 || $this->port > 65535) {
            throw new InvalidArgumentException('connection post is out of range');
        }
        return $this;
    }

    /**
     * Getter of instance connection port
     * @return int instance connection port
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * Instance has connection port or not
     * @return bool has connection port or not
     */
    public function hasPort() {
        return $this->port != self::MISSING_PORT;
    }

    /**
     * Use persistent connection or not
     * @param bool $persistent if need to use persistent connection
     * @return AbstractConnection self
     */
    public function setPersistent($persistent) {
        $this->persistent = (bool) $persistent;
        return $this;
    }

    /**
     * Use persistent connection or not
     * @return bool if need using persistent connection
     */
    public function isPersistent() {
        return $this->persistent;
    }

    /**
     * Check required connection parameters configuration method
     * @return bool check result
     */
    protected function isConfigured() {
        return !empty($this->host) && $this->port >= 0 && $this->port <= 65535;
    }
}
