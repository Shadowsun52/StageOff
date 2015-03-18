<?php
namespace stageOff\business;
use stageOff\model\config;
use stageOff\data\DatabaseAccess;
/**
 * Description of DocumentExcel
 *
 * @author Alexandre
 */
abstract class DocumentExcel {
    const TYPE_QUESTIONNAIRE_ETUDIANT = 1;
    const TYPE_QUESTIONNAIRE_MDS = 2;
    const SAVE_FOLDER = 'evaluation';
    
    /**
     * @var PHPExcel objet document excel 
     */
    private $_excel_doc;
    
    /**
     * @var int Type du questionnaire = Etudiant ou MDS; 
     */
    private $_type_questionnaire;
    
    /**
     * @var DatabaseAccess 
     */
    private $_db_access;
    
    /**
     * 
     * @param int $type_questionnaire Type du questionnaire voulu
     * @throws Exception
     */
    public function __construct($type_questionnaire) {
        $this->setTypeQuestionnaire($type_questionnaire);
        $this->initDbAccess();
    }
//<editor-fold defaultstate="collapsed" desc="Generate Doc">
    /**
     * Generer le document excel
     */
    public function generateDocument() {
        $this->initExcelDoc();
        $this->addContain();
        $this->saveDocument();
    }
    
    protected abstract function addContain();

    public function initExcelDoc() {
        $this->setExcelDoc(new \PHPExcel());
    }
    
    /**
     * Attache une feuille excel au document excel
     * @param Worksheet $sheet La feuille excel à attacher
     */
    protected function attachSheetToDoc($sheet) {
        $this->getExcelDoc()->addSheet($sheet);
    }
    
    /**
     * Détache une feuille excel du document si celle-ci est attaché
     * @param Worksheet $sheet Feuille excel à détacher
     * @return boolean Retourne vrai si la feuille était attaché et a bien été détaché
     * ou false si la feuille n'était pas attaché
     */
    protected function detachSheetToDoc($sheet) {
        $sheet_index = $this->getExcelDoc()->getIndex(
                $this->getExcelDoc()->getSheetByName($sheet->getTitle()));
        if($sheet_index === NULL)
        {
            return FALSE;
        }
        $this->getExcelDoc()->removeSheetByIndex($sheet_index);
        return TRUE;
    }
    
    protected function saveDocument() {
        $writer = new \PHPExcel_Writer_Excel2007($this->getExcelDoc());
        $writer->save(config::read('ROOT') . $this->getSavePath() .
                $this->getFileName() .'.xlsx');
    }
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="getter&setter">
    /**
     * Retourne le lien vers le document excel
     * @return string
     */
    public function getLink() {
        return './' . config::read('PATH') . $this->getSavePath() .
                $this->getFileName() .'.xlsx';
    }
    
    /**
     * Retourne le nom du document excel
     * @return string
     */
    public abstract function getFileName();
    
    /**
     * Retourne le chemin relative vers le dossier on sont stocker les Excels
     * @return string
     */
    public abstract function getSavePath();
    
    public function getExcelDoc() {
        return $this->_excel_doc;
    }
    
    public function setExcelDoc($excel_doc) {
        $$this->_excel_doc = $excel_doc;
    }
    
    public function getTypeQuestionnaire() {
        return $this->_type_questionnaire;
    }
    
    /**
     * 
     * @param int $type_questionnaire 
     * @throws Exception
     */
    public function setTypeQuestionnaire($type_questionnaire) {
        if(!$this->typeQuestionnaireExist($type_questionnaire))
        {
            throw new Exception("Type du questionnaire inconnu!");
        }
        $this->_type_questionnaire = $type_questionnaire;
    }
    
    /**
     * Verifie que le type du questionnaire existe
     * @param int $type_questionnaire type du questionnaire
     * @return boolean
     */
    protected function typeQuestionnaireExist($type_questionnaire) {
        return $type_questionnaire == self::TYPE_QUESTIONNAIRE_ETUDIANT ||
                $type_questionnaire == self::TYPE_QUESTIONNAIRE_MDS;
    }
    
    public function initDbAccess() {
        $this->setDbAccess(new DatabaseAccess());
    }
    
    public function getDbAccess() {
        return $this->_db_access;
    }
    
    public function setDbAccess($db_access) {
        $this->_db_access = $db_access;
    }
//</editor-fold>
}
