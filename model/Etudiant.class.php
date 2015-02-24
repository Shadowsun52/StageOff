<?php

/**
 * Description of Etudiant
 *
 * @author Alexandre
 */
class Etudiant extends Person {  
    private $_matricule;
    
    public function __construct($matricule) {
        parent::__construct();
        $this->setMatricule($matricule);
    }
    
    private function _reaDB(){
        try {
            $request = $this->getDB()->prepare("SELECT nom, prenom FROM etudiant "
                    . "WHERE matricule = :matricule");
            $request->execute(array(':matricule' => $this->getMatricule()));
            $result = $request->fetch();
            $this->setFirstname($result['prenom']);
            $this->setLastName($result['nom']);
        } catch (Exception $ex) {
            throw new Exception ('Erreur lors de la lecture dans la base de '
                    . 'données pour charger les informations de l\'étudiant.');
        }        
    }
    
    public function getMatricule() {
        return $this->_matricule;
    }
    
    public function setMatricule($matricule) {
        $this->_matricule = $matricule;
        $this->_reaDB();
    }
}
