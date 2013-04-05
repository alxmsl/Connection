<?php

namespace Connection\Postgresql\Client;

use Connection\Query\DbTemplate;

/**
 * Template for postgresql queries
 * @author alxmsl
 * @date 4/6/13
 */
final class QueryTemplate extends DbTemplate {
    /**
     * Escape string parameters
     * @param string $value value
     * @return string escaped value
     */
    public function str($value) {
        return pg_escape_literal((string) $value);
    }

    /**
     * Escape tables
     * @param string $table table name
     * @return string escaped table name
     */
    public function tbl($table) {
        return pg_escape_identifier((string) $table);
    }

}
