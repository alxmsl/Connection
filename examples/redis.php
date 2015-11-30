<?php
/**
 * Copyright 2015 Alexey Maslov <alexey.y.maslov@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Usage example
 * @author alxmsl
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

// Use transactions
$result = $Redis->transaction(function(Redis $Redis) {
    $Redis->set('aaa', 7);
    $Redis->set('bbb', 8);
    $Redis->get('aaa');
    $Redis->get('bbb');
    return true;
}, Redis::PIPELINE);
var_dump($result);
