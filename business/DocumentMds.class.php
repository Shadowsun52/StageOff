<?php
namespace stageOff\business;
use Exception;
/**
 * Description of DocumentMds
 *
 * @author Alexandre
 */
class DocumentMds extends Document{
    const STAGE_OFFINICAL = 1;
    const STAGE_HOSPITALIER = 2;
    
    public function __construct($id_questionnaire, $id_stage) {
        if(!$this->questionnaireExist($id_questionnaire))
        {
            throw new Exception('Ce questionnaire n\'existe pas.');
        }
        parent::__construct($id_questionnaire, $id_stage);
    }
    
    protected function getFileName($id_questionnaire) {
        $file_name =  'mds_' . $this->DeleteAccent($this->getStage()->getMaitreDeStage()) . 
                '-etu_' . $this->DeleteAccent($this->getStage()->getEtudiant()) . 
                ' ' . $this->getStage()->getPeriode();
        if ($id_questionnaire == self::STAGE_HOSPITALIER) {
            return $file_name . ' (Hospitalier)';
        } else {
            return $file_name . ' (Officinal)';
        }
    }
    
    protected function writeStageInfo() {
        parent::writeStageInfo();
        $this->getCurrentSheet()->setCellValue('A' . $this->getCurrentLine(),
                "Durée et Période de stage");
        $this->getCurrentSheet()->setCellValue('B' . $this->moveCurrentLine(),
                $this->getStage()->getPeriode() . ' ' . $this->getStage()->getDuree());
        $this->getCurrentSheet()->setCellValue('A' . $this->getCurrentLine(),
                "Pharmacie");
        
        $this->getCurrentSheet()->setCellValue('B' . $this->getCurrentLine(),
                "- Adresse :");
        $this->getCurrentSheet()->setCellValue('C' . $this->moveCurrentLine(),
                $this->getStage()->getMaitreDeStage()->getPharmacie()->getAddress());
        $this->getCurrentSheet()->setCellValue('B' . $this->getCurrentLine(),
                "- Téléphone/Fax :");
        $this->getCurrentSheet()->setCellValue('C' . $this->moveCurrentLine(),
                $this->getStage()->getMaitreDeStage()->getPharmacie()->getPhoneNumber() .
                ' / ' . $this->getStage()->getMaitreDeStage()->getPharmacie()->getFaxNumber());
        $this->getCurrentSheet()->setCellValue('B' . $this->getCurrentLine(),
                "- Mail :");
        $this->getCurrentSheet()->setCellValue('C' . $this->moveCurrentLine(),
                $this->getStage()->getMaitreDeStage()->getPharmacie()->getMail());
    }
    
    /**
     * Vérifie si l'identifiant du questionnaire existe bien et correspond à
     * un document pour le maitre de stage
     * @param int $id_questionnaire identifiant du questionnaire
     * @return boolean
     */
    protected function questionnaireExist($id_questionnaire) {
        return $id_questionnaire == self::STAGE_HOSPITALIER 
                || $id_questionnaire == self::STAGE_OFFINICAL;
    }
}
