<?php

/**
 * Description of DatabaseAccess
 *
 * @author Alexandre
 */
class DatabaseAccess {
    private $_connection;
    
    public function __construct() {
        $this->_setConnection();
    }
    
    public function getEtudiant($matricule) {
        try {
            $sql = "SELECT nom, prenom FROM etudiant WHERE matricule = :matricule";
            $request = $this->_getConnection()->prepare($sql);
            $request->execute(array(':matricule' => $matricule));
            $result = $request->fetch();
            
            $etudiant = new Etudiant($matricule, $result['nom'], $result['prenom']);
            return $etudiant;
        } catch (Exception $ex) {
            throw new Exception ('Erreur lors de la lecture dans la base de '
                    . 'données durant le chargement les informations de l\'étudiant.');
        }    
    }

    public function getPharmacien($id) {
        try {
            $sql = "SELECT nom, prenom, ref_identification FROM pharmacien WHERE id = :id";
            $request = $this->_getConnection()->prepare($sql);
            $request->execute(array(':id' => $id));
            $result = $request->fetch();
            
            $pharmacien = new Pharmacien($id, $result['nom'], $result['prenom']);
            $pharmacien->setPharmacie($this->getPharmacie($result['ref_identification']));
            return $pharmacien;
        } catch (Exception $ex) {
            throw new Exception ('Erreur lors de la lecture dans la base de '
                    . 'données durant le chargement les informations du pharmacien.');
        }
    }

    public function getPharmacie($ref_id) {
        try {
            $sql = "SELECT id, adresse, telephone, fax, mail FROM officine "
                    . "WHERE ref_identification = :ref_id";
            $request = $this->_getConnection()->prepare($sql);
            $request->execute(array(':ref_id' => $ref_id));
            $result = $request->fetch();
            
            $pharmacie = new Pharmacie($result['id'], $result['adresse'], 
                    $result['telephone'], $result['fax'], $result['mail']);
            return $pharmacie;
        } catch (Exception $ex) {
            throw new Exception ('Erreur lors de la lecture dans la base de '
                    . 'données durant le chargement des informations de la pharmacie.');
        }
    }
    
    public function getStage($id) {
        try {
            $sql = "SELECT ref_identification, ref_etudiant, date_debut, date_fin "
                    . "FROM stage WHERE id = :id";
            $request = $this->_getConnection()->prepare($sql);
            $request->execute(array(':id' => $id));
            $result = $request->fetch();
            
            $stage = new Stage($id, $result['date_debut'], $result['date_fin']);
            $stage->setEtudiant($this->getEtudiant($result['ref_etudiant']));
            $stage->setMaitreDeStage($this->getPharmacien($result['ref_identification']));
            return $stage;
        } catch (Exception $ex) {
            throw new Exception ('Erreur lors de la lecture dans la base de '
                    . 'données durant le chargement des informations du stage.');
        }
    }
    
    public function getQuestionnaireTitle($id) {
        try{
            $sql = "SELECT libelle FROM questionnaire WHERE id = :id";
            $request = $this->_getConnection()->prepare($sql);
            $request->execute(array(':id' => $id));
            $result = $request->fetch();
            
            return $result['libelle'];
        } catch (Exception $ex) {
            throw new Exception ('Erreur lors de la lecture dans la base de '
                    . 'données durant le chargement des informations du stage.');
        }
    }
    private function _getConnection() {
        return $this->_connection;
    }
    
    private function _setConnection() {
        $this->_connection = PDO2::getInstance()->db;
    }
}
