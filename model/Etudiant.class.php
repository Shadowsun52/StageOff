<?php
namespace stageOff\model;
/**
 * instance d'un étudiant
 *
 * @author Alexandre
 */
class Etudiant extends Person {  
    /**
     *
     * @var int matricule de l'étudiant 
     */
    private $_matricule;
    
    /**
     * 
     * @param int $matricule Matricule de l'étudiant
     * @param string $lastname Nom de l'étudiant
     * @param string $firstname Prénom de l'étudiant
     */
    public function __construct($matricule=NULL, $lastname=NULL, $firstname=NULL) {
        parent::__construct($lastname, $firstname);
        $this->setMatricule($matricule);
    }
    
    /**
     * 
     * @return int
     */
    public function getMatricule() {
        return $this->_matricule;
    }
    
    /**
     * 
     * @param int $matricule
     */
    public function setMatricule($matricule) {
        $this->_matricule = $matricule;
    }
}
