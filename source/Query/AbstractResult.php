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

namespace alxmsl\Connection\Query;

/**
 * Query result
 * @author alxmsl
 */
abstract class AbstractResult {
    /**
     * @var int affected rows counter
     */
    private $affectedRows = 0;

    /**
     * @var mixed query result
     */
    private $result = array();

    /**
     * Affected rows setter
     * @param int $affectedRows affected rows
     */
    protected function setAffectedRows($affectedRows) {
        $this->affectedRows = (int) $affectedRows;
    }

    /**
     * Getter of affected rows
     * @return int affected rows
     */
    public function getAffectedRows() {
        return $this->affectedRows;
    }

    /**
     * Result data setter
     * @param mixed $result result data
     */
    protected function setResult($result) {
        $this->result = $result;
    }

    /**
     * Result data getter
     * @return mixed result data
     */
    public function getResult() {
        return $this->result;
    }
}
