<?php

namespace stageOff\model;

/**
 * Description of Questionnement
 *
 * @author Alexandre
 */
class Questionnement {
    /**
     * @var string Libelle du questionnement
     */
    private $_libelle;
    
    /**
     * @var string Resultat au questionnement 
     */
    private $_result;
    
    /**
     * 
     * @param string $libelle Libelle du questionnement
     * @param string $result Resultat au questionnement
     */
    public function __construct($libelle=NULL, $result=NULL) {
        $this->setLibelle($libelle);
        $this->setResult($result);
    }
    
    /**
     * 
     * @return string
     */
    public function getLibelle() {
        return $this->_libelle;
    }
    
    /**
     * 
     * @param string $libelle
     */
    public function setLibelle($libelle) {
        $this->_libelle = $libelle;
    }
    
    /**
     * 
     * @return string
     */
    public function getResult() {
        return $this->_result;
    }
    
    /**
     * 
     * @param string $result
     */
    public function setResult($result) {
        $this->_result = $result;
    }
}
