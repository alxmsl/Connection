<?php

namespace alxmsl\Connection\Tests\Postgresql;

use alxmsl\Connection\Postgresql\Connection;
use alxmsl\Connection\Postgresql\Exception\QueryException;
use PHPUnit_Framework_TestCase;

/**
 * Test Postgres Connection
 * @author mkrasilnikov
 */
class ConnectionTest extends PHPUnit_Framework_TestCase {
    /**
     * Test handle query result with exception
     */
    public function testPostgresRaiseExceptionOnFunction() {
        $this->setExpectedException(QueryException::class);
        $function = '
            CREATE OR REPLACE FUNCTION raise_exception()
            RETURNS VOID AS $$
            BEGIN
                RAISE EXCEPTION \'exception message;\';
            END;
            $$ LANGUAGE plpgsql;
        ';
        $Connection = new Connection();
        $Connection->setHost('postgres');
        $Connection->setUserName('postgres');
        $Connection->query($function);
        $Connection->query('SELECT raise_exception();');
    }
}
