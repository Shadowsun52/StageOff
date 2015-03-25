<?php
namespace stageOff\business;

/**
 * Description of SheetExcelMDS
 *
 * @author Alexandre
 */
class SheetExcelMDS extends SheetExcel{
    public function __construct($stage = NULL) {
        parent::__construct($stage);
    }
    
    protected function writeStageInfo() {
        $first_line = parent::writeStageInfo();
        $this->getSheet()->setCellValue('A' . $this->getCurrentLine(),
                "Durée et Période de stage");
        $this->getSheet()->setCellValue('B' . $this->moveCurrentLine(),
                $this->getStage()->getPeriode() . ' ' . $this->getStage()->getDuree());
        $this->addStyleForStageInfo($first_line);
        $first_line = $this->getCurrentLine();
        
        $this->getSheet()->setCellValue('A' . $this->getCurrentLine(),
                "Pharmacie");
        $this->getSheet()->setCellValue('B' . $this->getCurrentLine(),
                "- Adresse :");
        $this->getSheet()->setCellValue('D' . $this->moveCurrentLine(),
                $this->getStage()->getMaitreDeStage()->getPharmacie()->getAddress());
        $this->getSheet()->setCellValue('B' . $this->getCurrentLine(),
                "- Téléphone/Fax :");
        $this->getSheet()->setCellValue('D' . $this->moveCurrentLine(),
                $this->getStage()->getMaitreDeStage()->getPharmacie()->getPhoneNumber() .
                ' / ' . $this->getStage()->getMaitreDeStage()->getPharmacie()->getFaxNumber());
        $this->getSheet()->setCellValue('B' . $this->getCurrentLine(),
                "- Mail :");
        $this->getSheet()->setCellValue('D' . $this->moveCurrentLine(),
                $this->getStage()->getMaitreDeStage()->getPharmacie()->getMail());
        $this->addStyleForStageInfo($first_line, 'C');
    }
    
    protected function writeComment() {
        $this->getSheet()->setCellValue('A' . $this->getCurrentLine(), 
                "Commentaire :");
        $this->getSheet()->getStyle('A'. $this->moveCurrentLine())
                     ->applyFromArray($this->STYLE_INFO);
        $this->getSheet()->setCellValue('A' . $this->getCurrentLine(),
        $this->getStage()->getCommentMds());
        $this->addStyleForComment($this->getStage()->getCommentMds());
    }
}
