<?php
/**
 * Postgresql query example
 * @author alxmsl
 * @date 4/2/13
 */

// Firstly include base class
include('../source/Autoloader.php');

use Connection\Postgresql\Client\Connection;

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
$Result = $Connection->query('select * from "pg_class"');
$Data = $Result->getResult();
var_dump($Data[0]);

// ..query data with parameters
$Result = $Connection->query('select count(*) from {{ tbl(table) }}', array(
    'table' => 'pg_class',
));
$Data = $Result->getResult();
var_dump($Data);