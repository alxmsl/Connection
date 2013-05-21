<?php

namespace Connection;

use Exception;

/**
 * Database connection abstraction
 * @author alxmsl
 * @date 4/3/13
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

/**
 * Database exception
 */
class DbException extends Exception {}