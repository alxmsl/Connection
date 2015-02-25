<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Connection;

// append Connection autoloader
spl_autoload_register(array('alxmsl\Connection\Autoloader', 'autoload'));

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
        'alxmsl\\Connection\\Autoloader'              => 'Autoloader.php',
        'alxmsl\\Connection\\ConnectionInterface'     => 'ConnectionInterface.php',
        'alxmsl\\Connection\\AbstractConnection'      => 'AbstractConnection.php',
        'alxmsl\\Connection\\TransactionalInterface'  => 'TransactionalInterface.php',
        'alxmsl\\Connection\\TransactionalConnection' => 'TransactionalConnection.php',
        'alxmsl\\Connection\\DbConnection'            => 'DbConnection.php',
        'alxmsl\\Connection\\Exception\\DbException'  => 'Exception/DbException.php',

        'alxmsl\\Connection\\Query\\AbstractResult'   => 'Query/AbstractResult.php',
        'alxmsl\\Connection\\Query\\AbstractTemplate' => 'Query/AbstractTemplate.php',
        'alxmsl\\Connection\\Query\\DbTemplate'       => 'Query/DbTemplate.php',

        'alxmsl\\Connection\\Redis\\Exception\\PhpRedisException'           => 'Redis/Exception/PhpRedisException.php',
        'alxmsl\\Connection\\Redis\\Exception\\ConnectException'            => 'Redis/Exception/ConnectException.php',
        'alxmsl\\Connection\\Redis\\Exception\\ImpossibleValueException'    => 'Redis/Exception/ImpossibleValueException.php',
        'alxmsl\\Connection\\Redis\\Exception\\KeyNotFoundException'        => 'Redis/Exception/KeyNotFoundException.php',
        'alxmsl\\Connection\\Redis\\Exception\\RedisNotConfiguredException' => 'Redis/Exception/RedisNotConfiguredException.php',
        'alxmsl\\Connection\\Redis\\Exception\\ScriptExecutionException'    => 'Redis/Exception/ScriptExecutionException.php',
        'alxmsl\\Connection\\Redis\\Exception\\TriesOverConnectException'   => 'Redis/Exception/TriesOverConnectException.php',

        'alxmsl\\Connection\\Redis\\RedisInterface' => 'Redis/RedisInterface.php',
        'alxmsl\\Connection\\Redis\\Connection'     => 'Redis/Connection.php',
        'alxmsl\\Connection\\Redis\\RedisFactory'   => 'Redis/RedisFactory.php',

        'alxmsl\\Connection\\Postgresql\\Exception\\PostgresException'         => 'Postgresql/Exception/PostgresException.php',
        'alxmsl\\Connection\\Postgresql\\Exception\\ConnectException'          => 'Postgresql/Exception/ConnectException.php',
        'alxmsl\\Connection\\Postgresql\\Exception\\ConnectionBusyException'   => 'Postgresql/Exception/ConnectionBusyException.php',
        'alxmsl\\Connection\\Postgresql\\Exception\\DuplicateEntryException'   => 'Postgresql/Exception/DuplicateEntryException.php',
        'alxmsl\\Connection\\Postgresql\\Exception\\DuplicateTableException'   => 'Postgresql/Exception/DuplicateTableException.php',
        'alxmsl\\Connection\\Postgresql\\Exception\\DuplicateTypeException'    => 'Postgresql/Exception/DuplicateTypeException.php',
        'alxmsl\\Connection\\Postgresql\\Exception\\QueryException'            => 'Postgresql/Exception/QueryException.php',
        'alxmsl\\Connection\\Postgresql\\Exception\\TriesOverConnectException' => 'Postgresql/Exception/TriesOverConnectException.php',
        'alxmsl\\Connection\\Postgresql\\Exception\\UndefinedTableException'   => 'Postgresql/Exception/UndefinedTableException.php',

        'alxmsl\\Connection\\Postgresql\\Connection'    => 'Postgresql/Connection.php',
        'alxmsl\\Connection\\Postgresql\\QueryResult'   => 'Postgresql/QueryResult.php',
        'alxmsl\\Connection\\Postgresql\\QueryTemplate' => 'Postgresql/QueryTemplate.php',

        'alxmsl\\Connection\\Predis\\PredisFactory' => 'Predis/PredisFactory.php',
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
