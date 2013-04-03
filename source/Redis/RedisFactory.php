<?php

namespace Connection\Redis;

use Connection\Redis\Client\Connection;

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
