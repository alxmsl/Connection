<?php
/*
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
 */

namespace alxmsl\Connection\Postgresql;

use alxmsl\Connection\Query\DbTemplate;
use Serializable;

/**
 * Template for postgresql queries
 * @author alxmsl
 */
final class QueryTemplate extends DbTemplate {
    public function json($value) {
        return $this->str(json_encode($value, JSON_UNESCAPED_UNICODE));
    }

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

    /**
     * Escape string[] values
     * @param array $array
     * @return string
     */
    public function arrstr(array $array) {
        $array = array_map(function($value) {
            return pg_escape_identifier((string) $value);
        }, $array);
        return $this->str(sprintf('{%s}', implode(',', $array)));
    }

    /**
     * Escape int[] values
     * @param array $array
     * @return string
     */
    public function arrint(array $array) {
        $array = array_map('intval', $array);
        return $this->str(sprintf('{%s}', implode(',', $array)));
    }

    /**
     * Escape integers array
     * @param array $array
     * @return string
     */
    public function inint(array $array) {
        $array = array_map('intval', $array);
        return sprintf('(%s)', implode(',', $array));
    }

    /**
     * Escape strings array
     * @param array $array
     * @return string
     */
    public function instr(array $array) {
        $array = array_map(function ($value) {
            return pg_escape_literal((string) $value);
        }, $array);
        return sprintf('(%s)', implode(',', $array));
    }
}
