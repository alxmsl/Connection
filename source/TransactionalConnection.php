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

use alxmsl\Connection\Query\AbstractResult;

/**
 * Transactional connection abstraction
 * @author alxmsl
 */
abstract class TransactionalConnection extends AbstractConnection implements TransactionalInterface {
    /**
     * Instance states
     */
    const   STATE_NONE = 0,
            STATE_SHEDULED = 1,
            STATE_STARTED = 2;

    /**
     * @var int current instance state
     */
    private $state = self::STATE_NONE;

    /**
     * @var int transaction deep counter
     */
    private $counter = 0;

    /**
     * Complete query with transaction
     * @param string $query query string
     * @return AbstractResult query result
     */
    public function querySafe($query) {
        if ($this->state === self::STATE_SHEDULED) {
            $this->state = self::STATE_STARTED;
            $this->begin();
        }
        return $this->query($query);
    }

    /**
     * Start transaction
     */
    public function start() {
        switch ($this->state) {
            case self::STATE_NONE:
                $this->state = self::STATE_SHEDULED;
                $this->counter = 1;
                break;
            default:
                $this->counter += 1;
        }
        return true;
    }

    /**
     * Accept transaction
     */
    public function accept() {
        switch ($this->state) {
            case self::STATE_SHEDULED:
                $this->counter -= 1;
                if ($this->counter <= 0) {
                    $this->state = self::STATE_NONE;
                    $this->counter = 0;
                }
                break;
        }
        return false;
    }

    /**
     * Cancel transaction
     */
    public function cancel() {
        switch ($this->state) {
            case self::STATE_SHEDULED:
                $this->state = self::STATE_NONE;
                $this->counter = 0;
                break;
        }
        return false;
    }

    /**
     * Complete query
     * @param string $query query string
     * @param array|null $data data for query parameters
     * @return AbstractResult query result
     */
    public abstract function query($query, array $data = null);

    /**
     * Start transaction implementation
     */
    public abstract function begin();

    /**
     * Accept transaction implementation
     */
    public abstract function commit();

    /**
     * Cancel transaction implementation
     */
    public abstract function rollback();
}
