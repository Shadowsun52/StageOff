<?php
namespace stageOff\data;
use stageOff\model\Pharmacie;
use stageOff\model\Pharmacien;
use stageOff\model\Stage;
use stageOff\model\Etudiant;
use stageOff\model\Questionnaire;
use \Exception;
/**
 * Description of DatabaseAccess
 *
 * @author Alexandre
 */
class DatabaseAccess {
    /**
     * @var PDO2 instance d'une connexion object PDO
     */
    private $_connection;
    
    public function __construct() {
        $this->_setConnection();
    }
    
    /**
     * 
     * @param int $matricule Matricule de l'étudiant à retourner
     * @return Etudiant
     * @throws Exception
     */
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

    /**
     * 
     * @param int $id Identifiant du pharmacien à retourner
     * @return Pharmacien
     * @throws Exception
     */
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

    /**
     * 
     * @param int $ref_id Identifiant de la pharmacie à retourner
     * @return Pharmacie
     * @throws Exception
     */
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
    
    /**
     * 
     * @param int $id Identifiant du stage à retourner
     * @return Stage
     * @throws Exception
     */
    public function getStage($id) {
        try {
            $sql = "SELECT ref_identification, ref_etudiant, date_debut, date_fin "
                    . "FROM stage WHERE id = :id";
            $request = $this->_getConnection()->prepare($sql);
            $request->execute(array(':id' => $id));
            $result = $request->fetch();
            
            $stage = new Stage($id, new \DateTime($result['date_debut']),
                    new \DateTime($result['date_fin']));
            $stage->setEtudiant($this->getEtudiant($result['ref_etudiant']));
            $stage->setMaitreDeStage($this->getPharmacien($result['ref_identification']));
            return $stage;
        } catch (Exception $ex) {
            throw new Exception ('Erreur lors de la lecture dans la base de '
                    . 'données durant le chargement des informations du stage.');
        }
    }
    
    /**
     * 
     * @param int $id Identificant du questionnaire recherché
     * @return Questionnaire
     * @throws Exception
     */
    public function getQuestionnaire($id) {
        try{
            $sql = "SELECT libelle FROM questionnaire WHERE id = :id";
            $request = $this->_getConnection()->prepare($sql);
            $request->execute(array(':id' => $id));
            $result = $request->fetch(\PDO::FETCH_ASSOC);
            
            $questionnaire = new Questionnaire($id, $result['libelle']);
            //lire question 
            return $questionnaire;
        } catch (Exception $ex) {
            throw new Exception ('Erreur lors de la lecture dans la base de '
                    . 'données durant le chargement du questionnaire.');
        }
    }
    
    private function _getConnection() {
        return $this->_connection;
    }
    
    private function _setConnection() {
        $this->_connection = PDO2::getInstance()->db;
    }
}
