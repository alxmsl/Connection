<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 *
 * Postgresql query example
 * @author alxmsl
 * @date 4/2/13
 */

// Firstly include base class
include('../source/Autoloader.php');

use alxmsl\Connection\Postgresql\Connection;

// Create connection
$Connection = new Connection();
$Connection->setUserName('postgres')
    ->setPassword('postgres')
    ->setDatabase('postgres')
    ->setHost('localhost')
    ->setPort(5432);

// Connect and ...
$Connection->connect();

// ..query needed data
$Result = $Connection->query('select * from "pg_class"', null, false);
$Data = $Result->getResult();
var_dump($Data[0]);

// ..query data with parameters
$Result = $Connection->query('select count(*) from {{ tbl(table) }}', array(
    'table' => 'pg_class',
), false);
$Data = $Result->getResult();
var_dump($Data);
