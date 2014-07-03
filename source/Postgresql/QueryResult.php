<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Connection\Postgresql;
use alxmsl\Connection\Query\AbstractResult;

/**
 * Postgresql query result
 * @author alxmsl
 * @date 4/2/13
 */
final class QueryResult extends AbstractResult {
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
