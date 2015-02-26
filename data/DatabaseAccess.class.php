<?php
namespace stageOff\data;
use stageOff\model\Pharmacie;
use stageOff\model\Pharmacien;
use stageOff\model\Stage;
use stageOff\model\Etudiant;
use stageOff\model\Questionnaire;
use stageOff\model\Question;
use stageOff\model\Questionnement;
use \Exception;
/**
 * Description of DatabaseAccess
 *
 * @author Alexandre
 */
class DatabaseAccess {
    const SEPARATOR = '#';
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
     * @param int $id Identifiant du questionnaire recherché
     * @param int $id_stage Identifiant du stage auquelle le questionnaire est lié
     * @return Questionnaire
     * @throws Exception
     */
    public function getQuestionnaire($id, $id_stage) {
        try{
            $sql = "SELECT libelle FROM questionnaire WHERE id = :id";
            $request = $this->_getConnection()->prepare($sql);
            $request->execute(array(':id' => $id));
            $result = $request->fetch(\PDO::FETCH_ASSOC);
            
            $questionnaire = new Questionnaire($id, $result['libelle'], 
                    $this->getQuestionForQuestionnaire($id, $id_stage));
            return $questionnaire;
        } catch (Exception $ex) {
            throw new Exception ('Erreur lors de la lecture dans la base de '
                    . 'données durant le chargement du questionnaire.');
        }
    }

    /**
     * 
     * @param int $id_questionnaire identifiant de la base de données du questionnaire
     * @param int $id_stage Identifiant du stage auquelle le questionnaire est lié
     * @return array[Question] retourne sous forme de tableau toute questions du questionnaire 
     * @throws Exception
     */
    public function getQuestionForQuestionnaire($id_questionnaire, $id_stage) {
        try {
            $sql = "SELECT q.id, q.libelle, q.questionnement, p.libelle as 'propositions' " .
                    "FROM question q JOIN proposition p ON q.ref_proposition = p.id " .
                    "WHERE q.ref_questionnaire = :ref_questionnaire";
            $request = $this->_getConnection()->prepare($sql);
            $request->execute(array(':ref_questionnaire' => $id_questionnaire));
            $i = 1;
            foreach($request->fetchAll(\PDO::FETCH_ASSOC) as $sql_question)
            {
                $propositions = explode(self::SEPARATOR, $sql_question['propositions']);
                $questionnements = $this->getQuestionnementForQuestion(
                        $sql_question['questionnement'], $id_stage, $id_questionnaire, $i++);     
                $question = new Question($sql_question['id'], $sql_question['libelle'], 
                        $propositions, $questionnements);
                $questions[] = $question;
            }            
            return $questions;
        } catch (Exception $ex) {
            throw new Exception ('Erreur lors de la lecture dans la base de '
                    . 'données durant le chargement des questions.');
        }
    }
    
    /**
     * 
     * @param string $liste Libelle des questionnements lié à la question sous
     * forme d'une liste en string
     * @param int $id_stage Identifiant du stage pour lequel on récupère les informations
     * @param int $id_questionnaire Identifiant du questionnaire auquel est liée la question
     * @param int $id_question Identifiant de la question auquel sont liés les questionnements
     * @return array[Questionnement]
     */
    private function getQuestionnementForQuestion($liste, $id_stage, 
            $id_questionnaire, $id_question) {
        if($liste === '0')
        {
            $libelles[] =  null;
        }
        else
        {
            $libelles = explode(self::SEPARATOR, $liste);
        }
        $sql = "SELECT proposition FROM evaluation WHERE ref_stage= :stage AND "
                . "ref_questionnaire = :questionnaire AND question = :question "
                . "ORDER BY questionnement ASC";
        $request = $this->_getConnection()->prepare($sql);
        $request->execute(array(':stage' => $id_stage, 
                                ':questionnaire' => $id_questionnaire,
                                ':question' => $id_question));
        foreach ($libelles as $libelle)
        {
            $result = $request->fetch(\PDO::FETCH_ASSOC);
            $questionnement = new Questionnement($libelle, $result['proposition']);
            $questionnements[] = $questionnement;
        }
        return $questionnements;
    }
    
    private function _getConnection() {
        return $this->_connection;
    }
    
    private function _setConnection() {
        $this->_connection = PDO2::getInstance()->db;
    }
}
