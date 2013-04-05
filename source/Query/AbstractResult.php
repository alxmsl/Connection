<?php

namespace Connection\Query;

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
