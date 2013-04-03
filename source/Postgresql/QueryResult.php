<?php

namespace Connection\Postgresql\Client;

use Connection\AbstractQueryResult;

/**
 * Postgresql query result
 * @author alxmsl
 * @date 4/2/13
 */
final class QueryResult extends AbstractQueryResult {
    /**
     * @param resource $Result query result resource
     */
    public function __construct($Result) {
        $this->setAffectedRows(pg_affected_rows($Result));

        $Rows = array();
        while ($row = pg_fetch_assoc($Result)) {
            $Rows[] = $row;
        }
        $this->setResult($Rows);
    }
}
