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
    const PHARMACIE_OFFICINAL = 1;
    const PHARMACIE_HOSPITALIERE = 2;
    
    public function __construct($id_stage) {
        $this->setDbAccess();
        parent::__construct($this->getTypeStage($id_stage), $id_stage);
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
        $first_line = parent::writeStageInfo();
        $long_line = $this->getCurrentLine();
        $this->getCurrentSheet()->setCellValue('A' . $this->getCurrentLine(),
                "Durée et Période de stage");
        $this->getCurrentSheet()->setCellValue('B' . $this->moveCurrentLine(),
                $this->getStage()->getPeriode() . ' ' . $this->getStage()->getDuree());
        $this->getCurrentSheet()->setCellValue('A' . $this->getCurrentLine(),
                "Pharmacie");
        $this->getCurrentSheet()->setCellValue('B' . $this->getCurrentLine(),
                "- Adresse :");
        $this->getCurrentSheet()->setCellValue('D' . $this->moveCurrentLine(),
                $this->getStage()->getMaitreDeStage()->getPharmacie()->getAddress());
        $this->getCurrentSheet()->setCellValue('B' . $this->getCurrentLine(),
                "- Téléphone/Fax :");
        $this->getCurrentSheet()->setCellValue('D' . $this->moveCurrentLine(),
                $this->getStage()->getMaitreDeStage()->getPharmacie()->getPhoneNumber() .
                ' / ' . $this->getStage()->getMaitreDeStage()->getPharmacie()->getFaxNumber());
        $this->getCurrentSheet()->setCellValue('B' . $this->getCurrentLine(),
                "- Mail :");
        $this->getCurrentSheet()->setCellValue('D' . $this->moveCurrentLine(),
                $this->getStage()->getMaitreDeStage()->getPharmacie()->getMail());
        $this->addStyleForStageInfo($first_line);
        $this->getCurrentSheet()->unmergeCells('B' . $long_line . ':C' . $long_line);
        $this->getCurrentSheet()->mergeCells('B' . $long_line . ':G' . $long_line);
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
    
    /**
     * Retourne le type du lieu de stage qui peut etre soit officinal ou hospitalier
     * @param int $id_stage identifiant du stage
     * @return int
     * @throws Exception
     */
    protected function getTypeStage($id_stage) {
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
}
