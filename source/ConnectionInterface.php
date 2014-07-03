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
 * Connection instance interface
 * @author alxmsl
 * @date 3/31/13
 */
interface ConnectionInterface {
    /**
     * Connect to instance
     * @return bool connection result
     */
    public function connect();

    /**
     * Disconnect from the instance
     */
    public function disconnect();

    /**
     * Reconnect to the instance
     * @return bool connection result
     */
    public function reconnect();
}
