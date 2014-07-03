<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Connection\Query;

/**
 * Query result
 * @author alxmsl
 * @date 4/2/13
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
