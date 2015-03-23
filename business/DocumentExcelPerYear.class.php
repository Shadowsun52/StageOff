<?php
namespace stageOff\business;

/**
 * Description of DocumentExcelPerYear
 *
 * @author Alexandre
 */
class DocumentExcelPerYear extends DocumentExcel{
    const SAVE_SUBFOLDER = '';
    const TEMP_MAX_EXECUTION = 300;
    /**
     * @var int Année de fin d'étude des étudiants recherchés
     */
    private $_year;
    
    /**
     * @var SheetExcel[] Tableau des feuilles excels à lié au document 
     */
    private $_sheets_excel;
    
    /**
     * 
     * @param int $type_questionnaire Le type du questionnaire à lié au document excel
     * @param int $year L'année de fin d'études des étudiants recherchés
     */
    public function __construct($type_questionnaire, $year) {
        parent::__construct($type_questionnaire);
        $this->setYear($year);
        $this->setSheetsExcel();

        $etudiants = $this->getDbAccess()->getMatriculeEtudiantPerYear(
                $this->getYear(), $this->getTypeQuestionnaire());
        foreach($etudiants as $id_etudiant) {
            $id_stages = $this->getDbAccess()->getAllIdStageForEtudiant(
                    $id_etudiant, $this->getTypeQuestionnaire());
            foreach ($id_stages as $id_stage) {
                $stage = $this->readStage($id_stage);
                $this->addSheetExcel($this->createSheetExcel($stage));
            }
        }
    }
    
    protected function addContain() {
        set_time_limit (self::TEMP_MAX_EXECUTION);
        foreach ($this->getSheetsExcel() as $sheet_excel)
        {
            $sheet_excel->createSheet($this->getExcelDoc());
        }
    }
 
//<editor-fold defaultstate="collapsed" desc="getter&setter">
    protected function getSavePath() {
        $path = self::SAVE_FOLDER;
        
        if(self::SAVE_SUBFOLDER != '')
        {
            $path .= '/'. self::SAVE_SUBFOLDER;
        }
 
        return $path . '/';
    }

    public function getFileName() {
        if($this->getTypeQuestionnaire() == self::TYPE_QUESTIONNAIRE_ETUDIANT)
        {
            $file_name = "Formulaire d'evaluation des stages";
        }
        else
        {
            $file_name = "Formulaire d'evaluation des stagiares";
        }
        
        return ($this->getYear()-1) . '-' . $this->getYear() . ' ' . $file_name;
    }
    
    /**
     * 
     * @return SheetExcel[]
     */
    public function getSheetsExcel() {
        return $this->_sheets_excel;
    }
    
    /**
     * 
     * @param SheetExcel[] $sheets
     */
    public function setSheetsExcel($sheets = NULL) {
        if($sheets == NULL) 
        {
            $this->_sheets_excel = array();
        }
        else
        {
           $this->_sheets_excel = $sheets; 
        }
    }
    
    /**
     * 
     * @param int $id
     * @return SheetExcel
     */
    public function getSheetExcel($id) {
        return $this->_sheets_excel[$id];
    }
    
    /**
     * 
     * @param SheetExcel $sheet
     */
    public function addSheetExcel($sheet) {
        $this->_sheets_excel[] = $sheet;
    }
    
    /**
     * 
     * @return int
     */
    public function getYear() {
        return $this->_year;
    }
    
    /**
     * 
     * @param int $year L'année de fin d'études des étudiants recherchés
     */
    public function setYear($year) {
        $this->_year = $year;
    }
    
    
//</editor-fold>   
}
