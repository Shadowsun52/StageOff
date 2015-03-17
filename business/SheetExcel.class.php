<?php
namespace stageOff\business;

/**
 * Description of SheetExcel
 *
 * @author Alexandre
 */
abstract class SheetExcel {
    const FIRST_LINE = 6;
    const SPACE_WITH_TITLE = 2;
    const FIRST_COL_PROPOSITION = 'B';
    const LAST_COL = 'G';
    const HEIGHT_INFO_STAGE = 23;
    const HEIGHT_TITLE_QUESTION = 30;
    const HEIGHT_LINE_FOR_PROPOSITION = 15;
    const BIG_PROPOSITION = 16;
    const MAX_PROPOSITION_SIZE = 30;
    
    //style pour le fichier excel
    private $STYLE_DEFAULT = array(
                        'font' => array(
                            'size' => 10,
                            'name' => 'Verdana'
                        )
                    );
    private $STYLE_TITLE= array(
                        'font'  => array(
                            'bold'  => true,
                            'size'  => 9,
                            'underline' => 'single'
                        )
                    );
    
    private $STYLE_INFO = array(
                        'font'  => array(
                            'bold'  => true
                        ),
                        'alignment' =>array(
                            'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                        )
                    );
    
    private $STYLE_QUESTION_TITLE = array(
                        'alignment' =>array(
                            'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        )
                    );
    
    private $STYLE_PROPOSITION = array(
                            'borders'  => array(
                                'allborders' => array(
                                    'style' => \PHPExcel_style_Border::BORDER_THIN
                                )
                            ),
                            'alignment' => array(
                                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                            )
                        );
    
    private $STYLE_QUESTIONNEMENT = array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                                )
                            ),
                            'alignment' => array(
                                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                            )
                        );
    private $STYLE_RESULT = array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                                )
                            ),
                            'alignment' => array(
                                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                            ),
                            'font' => array(
                                'bold' => true,
                                'size' => 14
                            )
                        );
    /**
     * @var Stage Stage lié à la feuille Excel
     */
    private $_stage;
    
    /**
     * @var int Ligne courante dans la Feuille Excel 
     */
    private $_current_line;
    
    /**
     * @var Worksheet Feuille Excel où l'on écrit 
     */
    private $_sheet;
    
    /**
     * 
     * @param Stage $stage Stage lié à la feuille Excel
     */
    public function __construct($stage=NULL) {
        $this->setStage($stage);
    }
  
//<editor-fold defaultstate="collapsed" desc="writer">
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
    
//</editor-fold>
    
//<editor-fold defaultstate="collapsed" desc="style">
    /**
     * 
     */
    protected function setColWidth() {
        $this->getSheet()->getColumnDimension('A')->setWidth(36);
        $this->getSheet()->getColumnDimension('B')->setWidth(10);
        for($col = 'C'; $col <= 'E'; $col++) {
            $this->getSheet()->getColumnDimension($col)->setWidth(9);
        }
        $this->getSheet()->getColumnDimension('F')->setWidth(11);
        $this->getSheet()->getColumnDimension('G')->setWidth(6);
    }
    
    protected function addStyleForStageInfo($first_line, 
            $merge_end_cell = self::LAST_COL) {
        for($i = $first_line; $i < $this->getCurrentLine(); $i++)
        {
             $this->getSheet()->mergeCells('B' .$i . ':' . $merge_end_cell . $i);
             $this->getSheet()->getRowDimension($i)
                     ->setRowHeight(self::HEIGHT_INFO_STAGE);
             $this->getSheet()->getStyle('A'. $i)
                     ->applyFromArray($this->STYLE_INFO);
             $this->getSheet()->getStyle('A'.$i.':D'.$i)->getAlignment()
                     ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        }
    }
    
    protected function addStyleForTitleQuestion() {
        $this->getSheet()->mergeCells('A' . $this->getCurrentLine() . 
                ':G' . $this->getCurrentLine());
        $this->getSheet()->getStyle('A' . $this->getCurrentLine())
                ->getAlignment()->setWrapText(true);
        $this->getSheet()->getStyle('A' . $this->getCurrentLine())
                ->applyFromArray($this->STYLE_QUESTION_TITLE);
        $this->getSheet()->getRowDimension($this->getCurrentLine())
                ->setRowHeight(self::HEIGHT_TITLE_QUESTION);
    }
//</editor-fold>
    
//<editor-fold defaultstate="collapsed" desc="getter&setter">    
    /**
     * 
     * @return Stage
     */
    public function getStage() {
        return $this->_stage;
    }
    
    /**
     * 
     * @param Stage $stage
     */
    public function setStage($stage) {
        $this->_stage = $stage;
    }
    
    /**
     * 
     * @return int
     */
    public function getCurrentLine() {
        return $this->_current_line;
    }
    
    /**
     * 
     * @param int $current_line
     */
    public function setCurrentLine($current_line) {
        $this->_current_line = $current_line;
    }
    
    public function goFirstLine() {
        $this->setCurrentLine(self::FIRST_LINE);
    }
    
    /**
     * 
     * @return Worksheet
     */
    public function getSheet() {
        return $this->_sheet;
    }
    
    /**
     * 
     * @param Worksheet $sheet
     */
    public function setSheet($sheet) {
        $this->_sheet = $sheet;
    }
//</editor-fold>
}
