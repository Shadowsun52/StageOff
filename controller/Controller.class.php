<?php

/**
 * Description of Controller
 *
 * @author Alexandre
 */
class Controller {
    private $_dbAccess;
    
    public function __construct() {
        $this->_setDbAccess();
    }
    
    public function getEtudiant($matricule) {
        return $this->_dbAccess->getEtudiant($matricule);
    }
    
    private function _getDbAccess() {
        return $this->_dbAccess;
    }
    
    private function _setDbAccess() {
        $this->_dbAccess = new DatabaseAccess();
    }
}
