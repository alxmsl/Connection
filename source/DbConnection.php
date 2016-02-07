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

/**
 * Database connection abstraction
 * @author alxmsl
 */
abstract class DbConnection extends TransactionalConnection {
    /**
     * @var string user name
     */
    private $userName = '';

    /**
     * @var string user password
     */
    private $password = '';

    /**
     * @var string needed database name
     */
    private $database = '';

    /**
     * Database name setter
     * @param string $database database name
     * @return DbConnection self
     */
    public function setDatabase($database) {
        $this->database = (string) $database;
        return $this;
    }

    /**
     * Database name getter
     * @return string database name
     */
    public function getDatabase() {
        return $this->database;
    }

    /**
     * Database name was set or not
     * @return bool status was database name set or not
     */
    public function hasDatabase() {
        return !empty($this->database);
    }

    /**
     * Database connection user password
     * @param string $password connection user password
     * @return DbConnection self
     */
    public function setPassword($password) {
        $this->password = (string) $password;
        return $this;
    }

    /**
     * Database connection password getter
     * @return string connection password
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Database password was set or not
     * @return bool status was database password set or not
     */
    public function hasPassword() {
        return !empty($this->password);
    }

    /**
     * Database connection user name setter
     * @param string $userName user name
     * @return DbConnection self
     */
    public function setUserName($userName) {
        $this->userName = (string) $userName;
        return $this;
    }

    /**
     * Database connection user name getter
     * @return string database connection user name
     */
    public function getUserName() {
        return $this->userName;
    }
}
