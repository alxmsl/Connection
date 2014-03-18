<?php

namespace Connection\Postgresql\Client;

use Connection\Query\DbTemplate;
use Serializable;

/**
 * Template for postgresql queries
 * @author alxmsl
 * @date 4/6/13
 */
final class QueryTemplate extends DbTemplate {
    /**
     * Escape string parameters
     * @param mixed $value value
     * @return string escaped value
     */
    public function str($value) {
        return pg_escape_literal((string) $value);
    }

    /**
     * Escape integer value
     * @param mixed $value query value
     * @return int query value as an integer
     */
    public function int($value) {
        return (int) $value;
    }

    /**
     * Composite types value
     * @param object $value composite type instance
     * @return string sub-query value for composite type
     */
    public function row($value) {
        if ($value instanceof Serializable) {
            return $value->serialize();
        } else {
            return $value;
        }
    }

    /**
     * Escape tables
     * @param mixed $table table name
     * @return string escaped table name
     */
    public function tbl($table) {
        return pg_escape_identifier((string) $table);
    }

}
