<?php
/**
 * Copyright 2015-2016 Alexey Maslov <alexey.y.maslov@gmail.com>
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
 * Postgresql query example
 * @author alxmsl
 */

// Firstly include base class
include '../vendor/autoload.php';

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
$Result = $Connection->query('select * from "pg_class"', null);
$Data = $Result->getResult();
var_dump($Data[0]);

// ..query data with parameters
$Result = $Connection->query('select count(*) from {{ tbl(table) }}', array(
    'table' => 'pg_class',
));
$Data = $Result->getResult();
var_dump($Data);
