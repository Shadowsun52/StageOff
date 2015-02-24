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
                    . 'données pour charger les informations de l\'étudiant.');
        }    
    }

    public function getPharmacien($id) {
        try {
            $sql = "SELECT nom, prenom FROM pharmacien WHERE id = :id";
            $request = $this->_getConnection()->prepare($sql);
            $request->execute(array(':id' => $id));
            $result = $request->fetch();
            
            $pharmacien = new Pharmacien($id, $result['nom'], $result['prenom']);
            return $pharmacien;
        } catch (Exception $ex) {
            throw new Exception ('Erreur lors de la lecture dans la base de '
                    . 'données pour charger les informations du pharmacien.');
        }
    }

    private function _getConnection() {
        return $this->_connection;
    }
    
    private function _setConnection() {
        $this->_connection = PDO2::getInstance()->db;
    }
}
