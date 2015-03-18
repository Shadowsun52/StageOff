<?php
namespace stageOff\business;
/**
 * Description of DocumentExcelUnique
 *
 * @author Alexandre
 */
class DocumentExcelUnique extends DocumentExcel{
    const SAVE_SUBFOLDER_ETU = '';
    const SAVE_SUBFOLDER_MDS = '';
    
    /**
     * @var SheetExcel Feuille excel lié au document 
     */
    private $_sheet_excel;
    
    /**
     * 
     * @param int $type_questionnaire Type du questionnaire voulu
     * @param int $id_stage Identifiant du stage lié au document
     */
    public function __construct($type_questionnaire, $id_stage) {
        parent::__construct($type_questionnaire);
        $stage = $this->readStage($id_stage);
        $this->createSheetExcel($stage);
    }
    
    protected function addContain() {
        $this->getExcelDoc()->addSheet($this->getSheetExcel()->createSheet());
    }

//<editor-fold defaultstate="collapsed" desc="getter&setter">
    /**
     * Retourne le nom du document excel
     * @return string
     */
    public function getFileName() {
        $info_etu = 'etu_' . $this->deleteAccent(
                $this->getSheetExcel()->getStage()->getEtudiant());
        $info_mds = 'mds_' . $this->deleteAccent(
                $this->getSheetExcel()->getStage()->getMaitreDeStage());
        $periode = ' ' . $this->getSheetExcel()->getStage()->getPeriode();
        
        if($this->getTypeQuestionnaire() == self::TYPE_QUESTIONNAIRE_ETUDIANT)
        {
            return $info_etu . '-' . $info_mds . $periode;
        }
        
        //Questionnaire Des MDS
        if($this->getTypeOfficine($this->getSheetExcel()->getStage()->getId()) 
                == self::STAGE_OFFINICAL)
        {
            return $info_mds . '-' . $info_etu . $periode . ' (Officinal)';
        }
        
        return $info_mds . '-' . $info_etu . $periode . ' (Hospitalier)';
    }

    /**
     * Retourne le chemin relative vers le dossier on sont stocker les Excels
     * @return string
     */
    protected function getSavePath() {
        $path = '/' . self::SAVE_FOLDER;
        if($this->getTypeQuestionnaire() == self::TYPE_QUESTIONNAIRE_ETUDIANT
                && self::SAVE_SUBFOLDER_ETU != '')
        {
            $path .= '/'. self::SAVE_SUBFOLDER_ETU;
        }
        elseif ($this->getTypeQuestionnaire() == self::TYPE_QUESTIONNAIRE_MDS
                && self::SAVE_SUBFOLDER_MDS != '') {
            $path .= '/'. self::SAVE_SUBFOLDER_MDS;
        }
        
        return $path;
    }
    
    /**
     * 
     * @return SheetExcel
     */
    public function getSheetExcel() {
        return $this->_sheet_excel;
    }
    
    /**
     * 
     * @param ExcelSheet $sheet
     */
    public function setSheetExcel($sheet) {
        $this->_sheet_excel = $sheet;
    }
//</editor-fold>   
}
