<?php

namespace stageOff\model;

/**
 * Description of Questionnaire
 *
 * @author Alexandre
 */
class Questionnaire {
    /**
     * @var int identifiant dans la base de données du questionnaire 
     */
    private $_id;
    
    /**
     * @var string Titre du questionnaire 
     */
    private $_title;
    
    /**
     * @var array[Question] 
     */
    private $_questions;
    
    
    /**
     * 
     * @param int $id identifiant dans la base de données du questionnaire
     * @param string $title Titre du questionnaire
     * @param array[Question] $questions liste des questions du formulaire
     */
    public function __construct($id=NULL, $title=NULL, $questions=NULL) {
        $this->setId($id);
        $this->setTitle($title);
        $this->setQuestions($questions);
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
    public function getTitle() {
        return $this->_title;
    }
    
    /**
     * 
     * @param string $title
     */
    public function setTitle($title) {
        $this->_title = $title;
    }
    
    public function getQuestions() {
        return $this->_questions;
    }
    
    /**
     * 
     * @param array[Question] $questions
     */
    public function setQuestions($questions){
        $this->_questions = $questions;
    }
    
    /**
     * 
     * @param int $id
     * @return Question
     */
    public function getQuestion($id) {
        return $this->_questions[$id];
    }
    
    /**
     * 
     * @param Question $question
     */
    public function addQuestion($question) {
        $this->_questions[] = $question;
    }
}