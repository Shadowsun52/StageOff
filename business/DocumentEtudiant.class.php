<?php
namespace stageOff\business;

/**
 * Description of DocumentEtudiant
 *
 * @author Alexandre
 */
class DocumentEtudiant extends Document{
    const ID_QUESTIONNAIRE = 3;
    
    public function __construct($id_stage) {
        parent::__construct(self::ID_QUESTIONNAIRE, $id_stage);
    }
    
    protected function getFileName($id_questionnaire) {
        return 'etu_' . $this->DeleteAccent($this->getStage()->getEtudiant()) .
            '-mds_' . $this->DeleteAccent($this->getStage()->getMaitreDeStage()) .
            ' ' . $this->getStage()->getPeriode();
    }
    
    protected function writeStageInfo() {
        parent::writeStageInfo();
        $this->getCurrentSheet()->setCellValue('A' . $this->getCurrentLine(),
                "Adresse de la pharmacie");
        $this->getCurrentSheet()->setCellValue('B' . $this->moveCurrentLine(),
                $this->getStage()->getMaitreDeStage()->getPharmacie()->getAddress());
        $this->getCurrentSheet()->setCellValue('A' . $this->getCurrentLine(),
                "Période(s) de stage");
        $this->getCurrentSheet()->setCellValue('B' . $this->moveCurrentLine(),
                $this->getStage()->getPeriode());
    }
}
