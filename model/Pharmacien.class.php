<?php

/**
 * Description of Pharmacien
 *
 * @author Alexandre
 */
class Pharmacien extends Person {
    private $_id;
    
    public function __construct($id=null, $lastname = NULL, $firstname = NULL) {
        parent::__construct($lastname, $firstname);
        $this->setId($id);
    }
    
    public function getId() {
        return $this->_id;
    }
    
    public function setId($id) {
        $this->_id = $id;
    }
}
