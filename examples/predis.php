<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 *
 * Predis usage example
 * @author alxmsl
 */

// Firstly include base class
include('../vendor/autoload.php');

use alxmsl\Connection\Predis\PredisFactory;

// Create Redis Client instance with you configuration settings
$Redis = PredisFactory::createPredisByConfig(array(
    'host' => 'localhost',
    'port' => 6379,
));

// Use Redis commands
$Redis->set('test', '7');
var_dump($Redis->get('test'));
