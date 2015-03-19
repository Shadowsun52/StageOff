<?php
namespace stageOff\business;

/**
 * Description of DocumentExcelPerYear
 *
 * @author Alexandre
 */
class DocumentExcelPerYear extends DocumentExcel{
    const SAVE_SUBFOLDER = '';
    
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
    }
    
    protected function addContain() {
        
    }
 
//<editor-fold defaultstate="collapsed" desc="getter&setter">
    protected function getSavePath() {
        
    }

    public function getFileName() {
        
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
    public function setSheetsExcel($sheets) {
        $this->_sheets_excel = $sheets;
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
