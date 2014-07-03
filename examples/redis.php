<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 *
 * Usage example
 * @author alxmsl
 * @date 10/22/12
 */

// Firstly include base class
include('../source/Autoloader.php');

use alxmsl\Connection\Redis\RedisFactory;

// Create Redis Client instance with you configuration settings
$Redis = RedisFactory::createRedisByConfig(array(
    'host' => 'localhost',
    'port' => 6379,
));

// Use Redis commands
$Redis->set('test', '7');
var_dump($Redis->get('test'));
