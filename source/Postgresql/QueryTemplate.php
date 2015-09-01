<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Connection\Postgresql;
use alxmsl\Connection\Query\DbTemplate;
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
     * Escape float value
     * @param mixed $value query value
     * @return float query value as a float
     */
    public function float($value) {
        return (float) $value;
    }

    /**
     * Escape boolean value
     * @param mixed $value query value
     * @return string query value as an boolean
     */
    public function bool($value) {
        return $value ? 'true' : 'false';
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
