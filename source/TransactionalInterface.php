<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Connection;

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
