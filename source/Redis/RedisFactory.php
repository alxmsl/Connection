<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Connection\Redis;

/**
 * Factory for simple PhpRedis instance creation
 * @author alxmsl
 * @date 11/1/12
 */
final class RedisFactory {
    /**
     * Create PhpRedis instance by array config
     * @param array $config array configuration
     * @throws \InvalidArgumentException
     */
    public static function createRedisByConfig(array $config) {
        $Redis = new Connection();
        $Redis->setHost(@$config['host'])
            ->setPort(@$config['port']);
        (isset($config['connect_timeout'])) && $Redis->setConnectTimeout($config['connect_timeout']);
        (isset($config['connect_tries']))   && $Redis->setConnectTries($config['connect_tries']);
        return $Redis;
    }
}
