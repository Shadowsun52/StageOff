<?php
 namespace stageOff\model;
/**
 * Classe abstraite pour la définition d'une personne
 *
 * @author Alexandre
 */
abstract class Person {
    
    /**
     * @var string prénom de la personne 
     */
    private $_firstname;
    
    /**
     *
     * @var string nom de la personne
     */
    private $_lastname;
    
    /**
     * @param string $lastname
     * @param string $firstname
     */
    public function __construct($lastname=NULL, $firstname=NULL) {
        $this->setFirstname($firstname);
        $this->setLastName($lastname);
    }
    
    /**
     * @return string
     */
    public function getFirstname() {
        return $this->_firstname;
    }
    
    /**
     * @param string $firstname
     */
    public function setFirstname($firstname) {
        $this->_firstname = $firstname;
    }
    
    
    /**
     * @return string
     */
    public function getLastName() {
        return $this->_lastname;
    }
    
    /**
     * @param string $lastname
     */
    public function setLastName($lastname) {
        $this->_lastname = $lastname;
    }
}
