<?php

namespace Connection;

/**
 * Transactional connection abstraction
 * @author alxmsl
 * @date 4/1/13
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
     * @return AbstractQueryResult query result
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
     * @return AbstractQueryResult query result
     */
    public abstract function query($query);

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
