<?php

namespace Connection;

/**
 * Transactional systems interface
 * @author alxmsl
 * @date 4/1/13
 */
interface TransactionalInterface {
    /**
     * Start transaction
     */
    public function start();

    /**
     * Accept transaction
     */
    public function accept();

    /**
     * Cancel transaction
     */
    public function cancel();
}
