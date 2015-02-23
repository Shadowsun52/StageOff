<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Person
 *
 * @author Alexandre
 */
abstract class Person {
    protected $firstname;
    protected $lastname;
    protected $bdd;
    
    public function __construct() {
        
    }
    
    public function getFirstname() {
        return $this->firstname;
    }
    
    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }
    
    public function getLastName() {
        return $this->lastname;
    }
    
    public function setLastName($lastname) {
        $this->lastname = $lastname;
    }
}
