<?php
namespace stageOff\controller;
use stageOff\data\DatabaseAccess;

/**
 * Description of Controller
 *
 * @author Alexandre
 */
class Controller {
    /**
     * @var DatabaseAccess 
     */
    private $_dbAccess;
    
    public function __construct() {
        $this->_setDbAccess();
    }
    
    /**
     * 
     * @param int $id Identifiant du stage recherché
     * @return type
     */
    public function getStage($id, $questionnaire = NULL) {
        return $this->_getDbAccess()->getStage($id, $questionnaire);
    }

    /**
     * 
     * @param int $matricule Matricule de l'étudiant recherché
     * @return type
     */
    public function getEtudiant($matricule) {
        return $this->_getDbAccess()->getEtudiant($matricule);
    }
    
    /**
     * 
     * @param int $id Identifiant du pharmacien recherché
     * @return type
     */
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
