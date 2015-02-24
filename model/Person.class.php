<?php

/**
 * Description of Person
 *
 * @author Alexandre
 */
abstract class Person {
    private $_firstname;
    private $_lastname;
    private $_db;
    
    public function __construct() {
        $this->setDB(PDO2::getInstance()->db);
    }
    
    public function getFirstname() {
        return $this->_firstname;
    }
    
    public function setFirstname($firstname) {
        $this->_firstname = $firstname;
    }
    
    public function getLastName() {
        return $this->_lastname;
    }
    
    public function setLastName($lastname) {
        $this->_lastname = $lastname;
    }
    
    public function getDB() {
        return $this->_db;
    }
    
    public function setDB($db) {
        $this->_db = $db;
    }
}
