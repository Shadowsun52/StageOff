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
    
    public function getStage($id) {
        return $this->_getDbAccess()->getStage($id);
    }

    public function getEtudiant($matricule) {
        return $this->_getDbAccess()->getEtudiant($matricule);
    }
    
    public function getPharmacien($id) {
        return $this->_getDbAccess()->getPharmacien($id);
    }
    
    private function _getDbAccess() {
        return $this->_dbAccess;
    }
    
    private function _setDbAccess() {
        $this->_dbAccess = new DatabaseAccess();
    }
}
