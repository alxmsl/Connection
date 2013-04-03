<?php

namespace Connection;

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
