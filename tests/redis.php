<?php
/**
 * Usage example
 * @author alxmsl
 * @date 10/22/12
 */

// Firstly include base class
include('../source/Autoloader.php');

use Connection\Redis\RedisFactory;

// Create Redis Client instance with you configuration settings
$Redis = RedisFactory::createRedisByConfig(array(
    'host' => 'localhost',
    'port' => 6379,
));

// Use Redis commands
$Redis->set('test', '7');
var_dump($Redis->get('test'));