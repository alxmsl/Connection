<?php

namespace Connection;

// append Connection autoloader
spl_autoload_register(array('\Connection\Autoloader', 'autoload'));

/**
 * Base class
 * @author alxmsl
 * @date 10/22/12
 */
final class Autoloader {
    /**
     * @var array array of available classes
     */
    private static $classes = array(
        'Connection\\Autoloader'                    => 'Autoloader.php',
        'Connection\\ConnectionInterface'           => 'ConnectionInterface.php',
        'Connection\\AbstractConnection'            => 'AbstractConnection.php',
        'Connection\\TransactionalInterface'        => 'TransactionalInterface.php',
        'Connection\\TransactionalConnection'       => 'TransactionalConnection.php',
        'Connection\\DbConnection'                  => 'DbConnection.php',

        'Connection\\Query\\AbstractResult'         => 'Query/AbstractResult.php',
        'Connection\\Query\\AbstractTemplate'       => 'Query/AbstractTemplate.php',
        'Connection\\Query\\DbTemplate'             => 'Query/DbTemplate.php',

        'Connection\\Redis\\Client\\RedisInterface' => 'Redis/RedisInterface.php',
        'Connection\\Redis\\Client\\Connection'     => 'Redis/Connection.php',
        'Connection\\Redis\\RedisFactory'           => 'Redis/RedisFactory.php',

        'Connection\\Postgresql\\Client\\Connection'    => 'Postgresql/Connection.php',
        'Connection\\Postgresql\\Client\\QueryResult'   => 'Postgresql/QueryResult.php',
        'Connection\\Postgresql\\Client\\QueryTemplate' => 'Postgresql/QueryTemplate.php',
    );

    /**
     * Component autoloader
     * @param string $className claass name
     */
    public static function autoload($className) {
        if (array_key_exists($className, self::$classes)) {
            $fileName = realpath(dirname(__FILE__)) . '/' . self::$classes[$className];
            if (file_exists($fileName)) {
                include $fileName;
            }
        }
    }
}