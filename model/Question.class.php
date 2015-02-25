<?php
namespace stageOff\model;

/**
 * Description of Question
 *
 * @author Alexandre
 */
class Question {
    /**
     * @var int 
     */
    private $_id;
    
    /**
     * @var string 
     */
    private $_libelle;
    
    /**
     * @var array[Questionnement] 
     */
    private $_questionnements;
    
    /**
     * @var array[string]
     */
    private $_proposition;
    
    /**
     * 
     * @param int $id Identifiant dans la base de données de la question
     * @param string $libelle Libelle de la question
     * @param array[string] $proposition Proposition possible pour la question
     * @param array[Questionnement] $questionnement Les différents questionnement de la question
     */
    public function __construct($id=NULL, $libelle=NULL, $propositions=NULL, 
            $questionnements=NULL) {
        
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
     * @return string
     */
    public function getLibelle() {
        return $this->_libelle;
    }
    
    /**
     * 
     * @param string $libelle
     */
    public function setLibelle($libelle) {
        $this->_libelle = $libelle;
    }
    
    /**
     * 
     * @return array[Questionnement]
     */
    public function getQuestionnements() {
        return $this->_questionnements;
    }
    
    /**
     * 
     * @param array[Questionnement] $questionnements
     */
    public function setQuestionnements($questionnements) {
        $this->_questionnements = $questionnements;
    }
    
    /**
     * 
     * @param int $id
     * @return Questionnement
     */
    public function getQuestionnement($id) {
        return $this->_questionnements[$id];
    }
    
    /**
     * 
     * @param Questionnement $questionnement
     */
    public function addQuestionnement($questionnement) {
        $this->_questionnements[] = $questionnement;
    }
    
    /**
     * 
     * @return array[string]
     */
    public function getPropositions() {
        return $this->_proposition;
    }
    
    /**
     * 
     * @param array[string] $propositions
     */
    public function setPropositions($propositions) {
        $this->_proposition = $propositions;
    }
    
    /**
     * 
     * @param int $id
     * @return string
     */
    public function getProposition($id) {
        return $this->_proposition[$id];
    }
    
    /**
     * 
     * @param string $proposition
     */
    public function addProposition($proposition) {
        $this->_proposition[] = $proposition;
    }
}
