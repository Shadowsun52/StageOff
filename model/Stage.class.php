<?php

/**
 * Description of Stage
 *
 * @author Alexandre
 */
class Stage {
    private $_id;
    private $_Etudiant;
    private $_Maitre_de_Stage;
    private $_start_date;
    private $_end_date;
    
    public function __construct($id=NULL, $start_date=NULL, $end_date=NULL, 
            $etudiant=NULL, $maitre_de_stage=NULL) {
        $this->setId($id);
        $this->setEtudiant($etudiant);
        $this->setMaitreDeStage($maitre_de_stage);
        $this->setStartDate($start_date);
        $this->setEndDate($end_date);
    }
    
    public function getId() {
        return $this->_id;
    }
    
    public function setId($id) {
        $this->_id = $id;
    }
    
    public function getEtudiant() {
        return $this->_Etudiant;
    }
    
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
    
    public function getMaitreDeStage() {
        return $this->_Maitre_de_Stage;
    }
    
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
    
    public function getStartDate() {
        return $this->_start_date;
    }
    
    public function setStartDate($start_date) {
        $this->_start_date = $start_date;
    }
    
    public function getEndDate() {
        return $this->_end_date;
    }
    
    public function setEndDate($end_date) {
        $this->_end_date = $end_date;
    }
}