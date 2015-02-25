<?php

namespace alxmsl\Connection\Predis;
use Predis\Client;

/**
 * Factory for simplified predis instance creation
 * @author alxmsl
 */
final class PredisFactory {
    /**
     * Create Predis instance by array config
     * @param array $config configuration array
     * @return Client predis instance
     */
    public static function createPredisByConfig(array $config) {
        return new Client($config);
    }
}
