<?php

/**
 * Description of Etudiant
 *
 * @author Alexandre
 */
class Etudiant extends Person {  
    private $_matricule;
    
    public function __construct($matricule=NULL, $lastname=NULL, $firstname=NULL) {
        parent::__construct($lastname, $firstname);
        $this->setMatricule($matricule);
    }
    
    public function getMatricule() {
        return $this->_matricule;
    }
    
    public function setMatricule($matricule) {
        $this->_matricule = $matricule;
    }
}
