Connection
=============
Simple set of classes for support some connections. At this moment library supports:

* redis connection over [phpredis](https://github.com/nicolasff/phpredis)
* redis connection over [predis](https://github.com/nrk/predis)
* [postgresql](http://php.net/manual/en/intro.pgsql.php) connection

Predis usage example
-------

    use alxmsl\Connection\Predis\PredisFactory;
    
    // Create Redis Client instance with you configuration settings
    $Redis = PredisFactory::createPredisByConfig(array(
        'host' => 'localhost',
        'port' => 6379,
    ));
    
    // Use Redis commands
    $Redis->set('test', '7');
    var_dump($Redis->get('test'));


Redis usage example (phpredis)
-------

    use alxmsl\Connection\Redis\RedisFactory;

    // Create Redis Client instance with you configuration settings
    $Redis = RedisFactory::createRedisByConfig(array(
        'host' => 'localhost',
        'port' => 6379,
    ));

    // Use Redis commands
    $Redis->set('test', '7');
    var_dump($Redis->get('test'));

Postgres usage example
-------

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

License
-------
Copyright © 2014 Alexey Maslov <alexey.y.maslov@gmail.com>
This work is free. You can redistribute it and/or modify it under the
terms of the Do What The Fuck You Want To Public License, Version 2,
as published by Sam Hocevar. See the COPYING file for more details.
