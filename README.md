# Connection

[![License](https://poser.pugx.org/alxmsl/connection/license)](https://packagist.org/packages/alxmsl/connection)
[![Latest Stable Version](https://poser.pugx.org/alxmsl/connection/version)](https://packagist.org/packages/alxmsl/connection)
[![Total Downloads](https://poser.pugx.org/alxmsl/connection/downloads)](https://packagist.org/packages/alxmsl/connection)

Simple set of classes for support some connections. At this moment library supports:

* redis connection over [phpredis](https://github.com/nicolasff/phpredis)
* redis connection over [predis](https://github.com/nrk/predis)
* [postgresql](http://php.net/manual/en/intro.pgsql.php) connection

## Predis usage example

    use alxmsl\Connection\Predis\PredisFactory;
    
    // Create Redis Client instance with you configuration settings
    $Redis = PredisFactory::createPredisByConfig(array(
        'host' => 'localhost',
        'port' => 6379,
    ));
    
    // Use Redis commands
    $Redis->set('test', '7');
    var_dump($Redis->get('test'));


## Redis usage example (phpredis)

    use alxmsl\Connection\Redis\RedisFactory;

    // Create Redis Client instance with you configuration settings
    $Redis = RedisFactory::createRedisByConfig(array(
        'host' => 'localhost',
        'port' => 6379,
    ));

    // Use Redis commands
    $Redis->set('test', '7');
    var_dump($Redis->get('test'));

## Postgres usage example

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

## Contributing

I welcome any help in developing this project. I accept contributions as pull requests. Finally, I kindly ask that 
 you add your tests and document all changes in library behavior/corrections. You are welcome to add copyright notices 
 with your name/nickname and email in all files, that you change

## License

Copyright 2015-2016 Alexey Maslov <alexey.y.maslov@gmail.com>

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
