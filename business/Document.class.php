<?php

/**
 * Description of Document
 *
 * @author Alexandre
 */
abstract class Document {
    private $_excel_doc;
    private $_current_line;
    private $_Stage;
    private $_db_access;
    
    public function __construct($id_questionnaire, $id_stage) {
        $this->setDbAccess();
        $this->initExcelDoc();
        $this->createSheet($id_questionnaire);
    }
    
    protected function getExcelDoc() {
        return $this->_excel_doc;
    }
    
    protected function setExcelDoc($excel_doc) {
        $this->_excel_doc = $excel_doc;
    }
    
    protected function initExcelDoc() {
        $this->setExcelDoc(new PHPExcel());
    }


    protected function getCurrentSheet() {
        return $this->getExcelDoc()->getActiveSheet();
    }
    
    protected function createSheet($id_questionnaire, $index_sheet=0) {
        if(empty($id_questionnnaire))
        {
            throw new Exception("Aucun questionnaire selectionné");
        }
        $this->getExcelDoc()->createSheet();
        $this->getExcelDoc()->setActiveSheetIndex($index_sheet);
        $this->getCurrentSheet()->setTitle(
                $this->getDbAccess()->getQuestionnaireTitle($id_questionnaire));
    }

    protected function getCurrentLine() {
        return $this->_current_line;
    }
    
    protected function setCurrentLine($current_line) {
        $this->_current_line = $current_line;
    }
    
    public function getStage() {
        return $this->_Stage;
    }
    
    public function setStage($stage) {
        if($stage)
        {
            $this->_Stage = $stage;
        }
        else
        {
            $this->_Stage = new Stage();
        }
    }
    
    protected function getDbAccess(){
        return $this->_db_access;
    }
    
    protected function setDbAccess() {
        $this->_db_access = new DatabaseAccess();
    }
}
