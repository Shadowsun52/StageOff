<?php
namespace stageOff\business;

/**
 * Description of Document
 *
 * @author Alexandre
 */
abstract class Document {
    const FIRST_LINE = 7;
    /**
     * @var PHPExcel objet document excel 
     */
    private $_excel_doc;
    
    /**
     * @var int Ligne courante dans le document excel 
     */
    private $_current_line;
    
    /**
     * @var Stage stage lier au document 
     */
    private $_Stage;
    
    /**
     * @var DatabaseAccess 
     */
    private $_db_access;
    
    /**
     * 
     * @param int $id_questionnaire identifiant du questionnaire
     * @param int $id_stage identifiant du stage 
     */
    public function __construct($id_questionnaire, $id_stage) {
        $this->setDbAccess();
        $this->initExcelDoc();
        $this->createSheet($id_questionnaire);
    }
    
    protected function initExcelDoc() {
        $this->setExcelDoc(new PHPExcel());
    }
    
    /**
     * 
     * @param int $id_questionnaire identifiant du questionnaire
     * @param int $index_sheet index de la nouvelle feuille excel
     * @throws Exception
     */
    protected function createSheet($id_questionnaire, $index_sheet=0) {
        if(empty($id_questionnaire))
        {
            throw new Exception("Aucun questionnaire selectionné");
        }
        $this->getExcelDoc()->createSheet();
        $this->getExcelDoc()->setActiveSheetIndex($index_sheet);
        $this->getCurrentSheet()->setTitle(
                $this->getDbAccess()->getQuestionnaireTitle($id_questionnaire));
    }
    
//<editor-fold defaultstate="collapsed" desc="writer">
    protected function writeLogo() {
        
    }
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="getter&setter">
    /**
     * 
     * @return PHPExcel
     */
    protected function getExcelDoc() {
        return $this->_excel_doc;
    }
    
    /**
     * 
     * @param PHPExcel $excel_doc
     */
    protected function setExcelDoc($excel_doc) {
        $this->_excel_doc = $excel_doc;
    }
    
    /**
     * 
     * @return PHPExcel_Worksheet
     */
    protected function getCurrentSheet() {
        return $this->getExcelDoc()->getActiveSheet();
    }
    
    /**
     * 
     * @return int
     */
    protected function getCurrentLine() {
        return $this->_current_line;
    }
    
    /**
     * 
     * @param int $current_line
     */
    protected function setCurrentLine($current_line) {
        $this->_current_line = $current_line;
    }
    
    /**
     * 
     * @return int
     */
    protected function goFirstLine() {
        $this->setCurrentLine(self::FIRST_LINE);
        return $this->getCurrentLine();
    }
    
    /**
     * 
     * @param int $number_move nombre de ligne à avancer
     * @return int retourne la ligne avant le déplacement
     */
    protected function moveCurrentLine($number_move=1){
        $current_line = $this->getCurrentLine();
        $this->setCurrentLine($this->getCurrentLine() + $number_move);
        return $current_line;
    }
    
    /**
     * 
     * @return Stage
     */
    public function getStage() {
        return $this->_Stage;
    }
    
    /**
     * 
     * @param Stage $stage
     */
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
    
    /**
     * 
     * @return string
     */
    public function getLink() {
        return '<p></p>';
    }
    //</editor-fold>
}