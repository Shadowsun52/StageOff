<?php
namespace stageOff\business;

/**
 * Description of SheetExcelEtudiant
 *
 * @author Alexandre
 */
class SheetExcelEtudiant extends SheetExcel{
    public function __construct($stage = NULL) {
        parent::__construct($stage);
    }

    /**
     * Ecrit les informations sur le stage dans le fichier  excel 
     */
    protected function writeStageInfo() {
        $first_line = parent::writeStageInfo();
        $this->getSheet()->setCellValue('A' . $this->getCurrentLine(),
                "Adresse de la pharmacie");
        $this->getSheet()->setCellValue('B' . $this->moveCurrentLine(),
                $this->getStage()->getMaitreDeStage()->getPharmacie()->getAddress());
        $this->getSheet()->setCellValue('A' . $this->getCurrentLine(),
                "Période(s) de stage");
        $this->getSheet()->setCellValue('B' . $this->moveCurrentLine(),
                $this->getStage()->getPeriode());
        $this->addStyleForStageInfo($first_line);
    }
    
    protected function writeComment() {
        $this->getSheet()->setCellValue('A' . $this->getCurrentLine(), 
                "Commentaire :");
        $this->getSheet()->getStyle('A'. $this->moveCurrentLine())
                     ->applyFromArray($this->STYLE_INFO);
        $this->getSheet()->setCellValue('A' . $this->getCurrentLine(),
        $this->getStage()->getCommentEtu());
        $this->addStyleForComment($this->getStage()->getCommentEtu());
    }
}
