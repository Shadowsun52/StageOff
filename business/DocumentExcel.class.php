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
    const STAGE_OFFINICAL = 1;
    const STAGE_HOSPITALIER = 2;
    const DOCUMENT_ETUDIANT = 3;
    const PHARMACIE_OFFICINAL = 1;
    const PHARMACIE_HOSPITALIERE = 2;
    
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
    
    protected function createSheetExcel($stage){
        if($this->getTypeQuestionnaire() == self::TYPE_QUESTIONNAIRE_ETUDIANT)
        {
            return new SheetExcelEtudiant($stage);
        }
        
        return new SheetExcelMDS($stage);
    }
    
    /**
     * Fonction retirant les accents d'une chaine de caractère
     * @param string $input
     * @return string
     */
    protected function deleteAccent($input) {
        $str = htmlentities($input, ENT_NOQUOTES, 'utf-8');
        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        return preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
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
    protected abstract function getSavePath();
    
    /**
     * Retourne le type du lieu de stage qui peut etre soit officinal ou hospitalier
     * @param int $id_stage identifiant du stage
     * @return int
     * @throws Exception
     */
    protected function getTypeOfficine($id_stage) {
        $type_officine = $this->getDbAccess()->getTypeOfficine($id_stage);
        if($type_officine == self::PHARMACIE_OFFICINAL)
        {
            return self::STAGE_OFFINICAL;
        }
        elseif($type_officine == self:: PHARMACIE_HOSPITALIERE)
        {
            return self::STAGE_HOSPITALIER;
        }
        else
        {
            throw new Exception('Aucun type de questionnaire trouvé pour ce stage');
        }
    }
    
    /**
     * Retourne un stage de la base de donnée par rapport à un idée et au type 
     * du questionnaire
     * @param int $id_stage identifiant du stage
     * @return Stage
     * @throws Exception
     */
    protected function readStage($id_stage) {
        if($this->getTypeQuestionnaire() == self::TYPE_QUESTIONNAIRE_ETUDIANT) {
            return $this->getDbAccess()->getStage($id_stage, self::DOCUMENT_ETUDIANT);
        }
        
        return $this->getDbAccess()->getStage($id_stage, $this->getTypeOfficine($id_stage));
    }


    public function getExcelDoc() {
        return $this->_excel_doc;
    }
    
    public function setExcelDoc($excel_doc) {
        $this->_excel_doc = $excel_doc;
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
