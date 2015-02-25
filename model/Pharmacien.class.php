<?php
namespace stageOff\model;

/**
 * Description of Pharmacien
 *
 * @author Alexandre
 */
class Pharmacien extends Person {
    private $_id;
    private $_pharmacie;
    
    public function __construct($id=null, $lastname = NULL, $firstname = NULL,
            $pharmacie = NULL) {
        parent::__construct($lastname, $firstname);
        $this->setId($id);
        $this->setPharmacie($pharmacie);
    }
    
    public function getId() {
        return $this->_id;
    }
    
    public function setId($id) {
        $this->_id = $id;
    }
    
    public function getPharmacie() {
        return $this->_pharmacie;
    }
    
    public function setPharmacie($pharmacie) {
        if($pharmacie)
        {
            $this->_pharmacie = $pharmacie;
        }
        else
        {
            $this->_pharmacie = new Pharmacie();
        }
    }
}
