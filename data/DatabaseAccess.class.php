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
    const MIN_YEAR = 2013;
    const TYPE_EVALUATION_ETUDIANT = 1;
    const TYPE_EVALUATION_MDS = 2;
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
            $sql = "SELECT nom, prenom FROM pharmacien WHERE ref_identification = :id";
            $request = $this->_getConnection()->prepare($sql);
            $request->execute(array(':id' => $id));
            $result = $request->fetch();
            
            $pharmacien = new Pharmacien($id, $result['nom'], $result['prenom']);
            $pharmacien->setPharmacie($this->getPharmacie($id));
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
     * @param int $questionnaire Identifiant du questionnaire à charger pour le stage
     * @return Stage
     * @throws Exception
     */
    public function getStage($id, $questionnaire = NULL) {
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
            if($questionnaire === NULL)
            {
                $stage->setQuestionnaires($this->getAllQuestionnairesForStage($id));
            }
            else
            {
                $stage->addQuestionnaire($this->getQuestionnaire($questionnaire, $id));
            }
            return $stage;
        } catch (Exception $ex) {
            throw new Exception ('Erreur lors de la lecture dans la base de '
                    . 'données durant le chargement des informations du stage.');
        }
    }
    
    /**
     * 
     * @param int $id_etudiant identifiant de l'étudiant
     * @param int $type_evaluation type 'évaluation voulu
     * @return int[] tableau des id des stages lié à l'étudiant ayant une evaluation
     * @throws Exception
     */
    public function getAllIdStageForEtudiant($id_etudiant, $type_evaluation) {
        try{
            $sql = "SELECT id FROM stage WHERE ref_etudiant = :ref_etudiant AND " .
                    $this->getColForEvaluation($type_evaluation) . " = 1 
                    ORDER BY date_fin DESC";
            $request = $this->_getConnection()->prepare($sql);
            $request->execute(array(':ref_etudiant' => $id_etudiant));
            $results = $request->fetchAll(\PDO::FETCH_ASSOC);
            
            foreach($results as $result) {
                $stages[] = $result['id'];
            }
            
            return $stages;
            
        } catch (Exception $ex) {
            throw new Exception ('Erreur lors de la lecture dans la base de '
                    . 'données durant le chargement des identifiants des stages.');
        }
    }
    
    /**
     * 
     * @param int $id_stage
     * @return array[Questionnaire]
     * @throws Exception
     */
    public function getAllQuestionnairesForStage($id_stage) {
        try{
            $sql = "SELECT q.id, q.libelle FROM questionnaire q " .
                    "JOIN evaluation e ON e.ref_questionnaire = q.id ". 
                    "WHERE e.ref_stage = :stage GROUP BY q.id";
            $request = $this->_getConnection()->prepare($sql);
            $request->execute(array(':stage' => $id_stage));
            
            foreach ($request->fetchAll(\PDO::FETCH_ASSOC) as $result)
            {
                $questionnaires[] = new Questionnaire($result['id'], $result['libelle'],
                        $this->getQuestionsForQuestionnaire($result['id'], $id_stage));
            }
            return isset($questionnaires) ? $questionnaires : NULL;
            
        } catch (Exception $ex) {
            throw new Exception ('Erreur lors de la lecture dans la base de '
                    . 'données durant le chargement du questionnaire.');
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
                    $this->getQuestionsForQuestionnaire($id, $id_stage));
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
    public function getQuestionsForQuestionnaire($id_questionnaire, $id_stage) {
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
                $questionnements = $this->getQuestionnementsForQuestion(
                        $sql_question['questionnement'], $id_stage, $id_questionnaire, $i++);     
                $questions[] = new Question($sql_question['id'], $sql_question['libelle'], 
                        $propositions, $questionnements);
            }
            return (isset($questions)) ? $questions : NULL;
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
    private function getQuestionnementsForQuestion($liste, $id_stage, 
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
            $questionnements[] = new Questionnement($libelle, $result['proposition']);
        }
        return (isset($questionnements))? $questionnements : NULL;
    }

    /**
     * Retourne l'id du type d'officine où se déroule le stage
     * @param int $id_stage identifiant du stage 
     * @return int
     */
    public function getTypeOfficine($id_stage) {
        $sql = "SELECT o.ref_type_officine FROM officine o 
                JOIN pharmacien p ON p.ref_identification = o.ref_identification 
                JOIN stage s ON s.ref_identification = p.ref_identification WHERE s.id = :id_stage";
        $request = $this->_getConnection()->prepare($sql);
        $request->execute(array(':id_stage' => $id_stage));
        $result = $request->fetch(\PDO::FETCH_ASSOC);
        return $result['ref_type_officine'];
    }

    /**
     * 
     * @param int $year Année de fin d'étude des stagiares recherchés
     * @param int $type_evaluation le type d'évaluation voulu
     * @return type
     */
    public function getMatriculeEtudiantPerYear($year, $type_evaluation) {
        $sql = "SELECT e.matricule FROM etudiant e
                JOIN stage s ON e.matricule = s.ref_etudiant 
                WHERE e.annee like 'PHAR5%' AND e.anac = :year
                GROUP BY e.matricule
                HAVING max(s." . $this->getColForEvaluation($type_evaluation) .
                " = 1) ORDER BY e.nom, e.prenom";
        $request = $this->_getConnection()->prepare($sql);
        $request->execute(array(':year' => $year));
        $result = $request->fetchAll(\PDO::FETCH_ASSOC);
        
        if(count($result) == 0)
        {
            return null;
        }
        
        foreach($result as $row){
            $matricules[] = $row['matricule'];
        }
        return $matricules;
    }
    
    /**
     * 
     * @param int $type_evaluation le type d'évaluation voulu
     * @return int[] Liste des années avec des étudiants qui ont terminés
     */
    public function getYearWithFinalStudent($type_evaluation) {
        $sql = "SELECT e.anac as 'year' FROM stage s
                JOIN etudiant e ON e.matricule = s.ref_etudiant
                WHERE e.annee like 'PHAR5%' AND e.anac >= :min_year
                AND e.anac <= year(NOW()) AND s." .
                $this->getColForEvaluation($type_evaluation) .  
                "=1 GROUP BY e.anac ORDER BY s.date_fin DESC";
        $request = $this->_getConnection()->prepare($sql);
        $request->execute(array('min_year' => self::MIN_YEAR));
        $result = $request->fetchAll(\PDO::FETCH_ASSOC);

        foreach($result as $row) {
            $year[] = $row['year'];
        }
        
        return $year;
    }

    private function _getConnection() {
        return $this->_connection;
    }
    
    private function _setConnection() {
        $this->_connection = PDO2::getInstance()->db;
    }
    
    /**
     * 
     * @param int $type_evaluation Type d'évaluation
     * @return string
     * @throws Exception
     */
    protected function getColForEvaluation($type_evaluation) {
        if($type_evaluation == self::TYPE_EVALUATION_ETUDIANT)
        {
            return 'evaluation_etudiant_disponible';
        }
        elseif($type_evaluation == self::TYPE_EVALUATION_MDS)
        {
            return 'evaluation_disponible';
        }
        else
        {
            throw new Exception('Type d\'évaluation inconnu');
        }
    }
}
