<?php
namespace stageOff\model;

/**
 * Un Stage d'un étudiant
 *
 * @author Alexandre
 */
class Stage {
    const DEFAULT_DATE_FORMAT = 'd-m-Y' ; 
    
    /**
     * @var int Identifiant dans la base de données du stage 
     */
    private $_id;
    
    /**
     * @var Etudiant Etudiant effectuant le stage 
     */
    private $_Etudiant;
    
    /**
     * @var Pharmacien Pharmacie s'occupant du stagiaire en tant que maitre de stage 
     */
    private $_Maitre_de_Stage;
    
    /**
     * @var DateTime date du début du stage 
     */
    private $_start_date;
    
    /**
     * @var DateTime date de fin du stage 
     */
    private $_end_date;
    
    /**
     *
     * @var array[Questionnaire] Les informations pour chaque questionnaire lié au stagiaire
     */
    private $_questionnaires;
    
    /**
     * 
     * @param int $id Identifiant du stage
     * @param DateTime $start_date Date de Début du stage
     * @param DateTime $end_date Date de fin du stage
     * @param Etudiant $etudiant Etudiant effectuant le stage
     * @param Pharmacien $maitre_de_stage Pharmacien supervisant l'étudiant
     */
    public function __construct($id=NULL, $start_date=NULL, $end_date=NULL, 
            $etudiant=NULL, $maitre_de_stage=NULL, $questionnaires=NULL) {
        $this->setId($id);
        $this->setEtudiant($etudiant);
        $this->setMaitreDeStage($maitre_de_stage);
        $this->setStartDate($start_date);
        $this->setEndDate($end_date);
        $this->setQuestionnaires($questionnaires);
    }
    
    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->_id;
    }
    
    /**
     * 
     * @param int $id
     */
    public function setId($id) {
        $this->_id = $id;
    }
    
    /**
     * 
     * @return Etudiant
     */
    public function getEtudiant() {
        return $this->_Etudiant;
    }
    
    /**
     * 
     * @param Etudiant $etudiant
     */
    public function setEtudiant($etudiant) {
        if($etudiant)
        {
            $this->_Etudiant = $etudiant;
        }
        else
        {
            $this->_Etudiant = new Etudiant();
        }
    }
    
    /**
     * 
     * @return Pharmacien
     */
    public function getMaitreDeStage() {
        return $this->_Maitre_de_Stage;
    }
    
    /**
     * 
     * @param Pharmacien $maitre_de_stage
     */
    public function setMaitreDeStage($maitre_de_stage) {
        if($maitre_de_stage)
        {
            $this->_Maitre_de_Stage = $maitre_de_stage;
        }
        else
        {
            $this->_Maitre_de_Stage = new Pharmacien();
        }
    }
    
    /**
     * 
     * @return DateTime
     */
    public function getStartDate() {
        return $this->_start_date;
    }
    
    /**
     * 
     * @param DateTime $start_date
     */
    public function setStartDate($start_date) {
        $this->_start_date = $start_date;
    }
    
    /**
     * 
     * @param String $format Format de la date à retourner
     * @return String
     */
    public function getStartDateFormat($format=NULL) {
        if($format === NULL)
        {
            return $this->getStartDate()->format(self::DEFAULT_DATE_FORMAT);
        }
        else
        {
            return $this->getStartDate()->format($format);
        }
    }
    
    /**
     * 
     * @return DateTime
     */
    public function getEndDate() {
        return $this->_end_date;
    }
    
    /**
     * 
     * @param DateTime $end_date
     */
    public function setEndDate($end_date) {
        $this->_end_date = $end_date;
    }
    
    /**
     * 
     * @param String $format Format de la date à retourner
     * @return String
     */
    public function getEndDateFormat($format=NULL) {
        if($format === NULL)
        {
            return $this->getEndDate()->format(self::DEFAULT_DATE_FORMAT);
        }
        else
        {
            return $this->getEndDate()->format($format);
        }
    }
    
    /**
     * 
     * @param string $format Format de la date à retourner
     * @return string
     */
    public function getPeriode($format=NULL) {
        return 'du ' . $this->getStartDateFormat($format) . ' au ' . 
                $this->getEndDateFormat($format);
    }
    
    /**
     * retourne un string contenant la durée du stage en nombre de jour sous le 
     * format suivant : (XX jours)
     * @return string
     */
    public function getDuree() {
        $interval = $this->getStartDate()->diff($this->getEndDate());
        return '(' . $interval->days . 'jours)';
    }

    /**
     * 
     * @return array[Questionnaire]
     */
    public function getQuestionnaires() {
        return $this->_questionnaires;
    }
    
    /**
     * 
     * @param array['Questionnaire'] $questionnaires
     */
    public function setQuestionnaires($questionnaires) {
        $this->_questionnaires = $questionnaires;
    }
    
    /**
     * 
     * @param int $id identifiant du questionnaire
     * @return Questionnaire
     */
    public function getQuestionnaireById($id) {
        foreach ($this->getQuestionnaires() as $questionnaire)
        {
            if($questionnaire->getId() == $id)
            {
                return $questionnaire;
            }
        }
        return null;
    }
    
    /**
     * 
     * @param Questionnaire $questionnaire
     */
    public function addQuestionnaire($questionnaire) {
        $this->_questionnaires[] = $questionnaire;
    }
}