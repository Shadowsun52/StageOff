<?php
namespace stageOff\business;
use stageOff\model\config;
use stageOff\data\DatabaseAccess;
use stageOff\model\Stage;

/**
 * Description of Document
 *
 * @author Alexandre
 */
abstract class Document {
    const FIRST_LINE = 7;
    const SPACE_WITH_TITLE = 2;
    
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
     *
     * @var int
     */
//    private $_id_questionnaire;
    
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
        if($id_questionnaire === NULL || empty($id_questionnaire)) {
            throw new Exception("Aucun questionnaire selectionné");
        }
        $this->setDbAccess();
        $this->initExcelDoc(); 
        $this->setStage($this->getDbAccess()->getStage($id_stage, $id_questionnaire));
        $this->createSheet();
        $this->goFirstLine();
        $this->writeDocument($id_questionnaire);
        $this->saveDocument();
    }
    
    protected function initExcelDoc() {
        $this->setExcelDoc(new \PHPExcel());
    }
    
    /**
     * 
     * @param int $index_sheet index de la nouvelle feuille excel
     * @throws Exception
     */
    protected function createSheet($index_sheet=0) {
        $this->getExcelDoc()->createSheet();
        $this->getExcelDoc()->setActiveSheetIndex($index_sheet);
    }

//<editor-fold defaultstate="collapsed" desc="Save File">
    protected function saveDocument() {
        $writer = new \PHPExcel_Writer_Excel2007($this->getExcelDoc());
        $writer->save(config::read('ROOT') . 'evaluation/' . $this->getFileName(). '.xlsx');
    }

    /**
     * 
     * @return String Nom du fichier créer
     */
    abstract protected function getFileName();


    /**
     * Fonction retirant les accents d'une chaine de caractère
     * @param string $input
     * @return string
     */
    protected function DeleteAccent($input) {
        $str = htmlentities($input, ENT_NOQUOTES, 'utf-8');
        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        return preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    }
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="writer">
    protected function writeDocument($id_questionnaire) {
        $this->writeLogo();
        $this->writeTitle($id_questionnaire);
        $this->writeStageInfo();
    }
    
    protected function writeLogo() {
        $objDrawing = new \PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('logo ULB');
        $objDrawing->setDescription('logo ULB');
        $objDrawing->setPath(config::read('ROOT') . 'images/logo_ulb.png');
        $objDrawing->setHeight(100);
        $objDrawing->setCoordinates('A1');
        $objDrawing->setOffsetX(-10);
        $objDrawing->setWorksheet($this->getCurrentSheet());
    }
    
    protected function writeTitle($id_questionnaire) {
        $this->getCurrentSheet()->setCellValue(
                'A'.$this->moveCurrentLine(self::SPACE_WITH_TITLE),
                $this->getStage()->getQuestionnaireById($id_questionnaire)->getTitle());
    }
    
    protected function writeStageInfo() {
        $this->getCurrentSheet()->setCellValue('A' . $this->getCurrentLine(), 
                "Nom Du Stagiare");
        $this->getCurrentSheet()->setCellValue('B' . $this->moveCurrentLine(),
                $this->getStage()->getEtudiant().'');
        $this->getCurrentSheet()->setCellValue('A' . $this->getCurrentLine(),
                "Nom du maitre de stage");
        $this->getCurrentSheet()->setCellValue('B' . $this->moveCurrentLine(),
                ''.$this->getStage()->getMaitreDeStage());
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