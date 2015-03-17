<?php
namespace stageOff\business;
use stageOff\model\config;
use stageOff\model\Stage;

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
   
//<editor-fold defaultstate="collapsed" desc="list style">
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
//</editor-fold>
    
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
  
    /**
     * Fonction qui crée le worksheet en fonction du stage
     * @return Worksheet
     */
    public function createSheet() {
        $temp = new \PHPExcel();
        $this->setSheet(new \PHPExcel_Worksheet($temp, $this->getSheetName()));
        $temp->addSheet($this->getSheet());
        $this->writeSheet();
        $this->protectWorksheet();
        return $this->getSheet();
    }
//<editor-fold defaultstate="collapsed" desc="writer">

    protected function writeSheet() {
        $this->getSheet()->duplicateStyleArray($this->STYLE_DEFAULT, 'A1:G200');
        $this->goFirstLine();
        $this->setColWidth();
        $this->writeLogo();
        $id_questionnaire = $this->getStage()->getQuestionnaire(0)->getId();
        $this->writeTitle($id_questionnaire);
        $this->writeStageInfo();
        $this->writeAllQuestions($id_questionnaire);
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
     * Ajoute le logo au début de la page excel
     */
    protected function writeLogo() {
        $objDrawing = new \PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('logo ULB');
        $objDrawing->setDescription('logo ULB');
        $objDrawing->setPath(config::read('ROOT') . 'images/logo_ulb.png');
        $objDrawing->setHeight(70);
        $objDrawing->setCoordinates('A1');
        $objDrawing->setWorksheet($this->getSheet());
    }
    
    /**
     * Ecrit le nom du questionnaire dans le fichier excel
     * @param int $id_questionnaire
     */
    protected function writeTitle($id_questionnaire) {
        $this->getSheet()->getStyle('A'. $this->getCurrentLine())
                ->applyFromArray($this->STYLE_TITLE);
        $this->getSheet()->setCellValue(
                'A'.$this->moveCurrentLine(self::SPACE_WITH_TITLE),
                $this->getStage()->getQuestionnaireById($id_questionnaire)->getTitle());
    }
    
    /**
     * Ecrit les informations sur le stage dans le fichier  excel 
     */
    protected function writeStageInfo() {
        $first_line = $this->getCurrentLine();
        $this->getSheet()->setCellValue('A' . $this->getCurrentLine(), 
                "Nom Du Stagiare");
        $this->getSheet()->setCellValue('B' . $this->moveCurrentLine(),
                $this->getStage()->getEtudiant().'');
        $this->getSheet()->setCellValue('A' . $this->getCurrentLine(),
                "Nom du maitre de stage");
        $this->getSheet()->setCellValue('B' . $this->moveCurrentLine(),
                ''.$this->getStage()->getMaitreDeStage());
        return $first_line;
    }
    
    /**
     * Ecrit toute les questions d'un questionnaire dans le fichier excel
     * @param int $id_questionnaire
     */ 
    protected  function writeAllQuestions($id_questionnaire) {
        $number_question = 1;
        $this->moveCurrentLine();
        foreach ($this->getStage()->getQuestionnaireById($id_questionnaire)->getQuestions() as $question) {
            $this->writeQuestion($question, $number_question++);
        }
    }
    
    /**
     * Ecrit une question dans le fichier excel
     * @param Question $question
     * @param int $number_question Numéro de la question
     */
    protected function writeQuestion($question, $number_question) {
        $this->addStyleForTitleQuestion();
        $this->getSheet()->setCellValue('A' . $this->moveCurrentLine(),
                $number_question . '. ' . $question->getLibelle());
        $this->writePropositions($question);
        $this->writeQuestionnements($question);
    }
    
    /**
     * Ecrit les propositions d'une question dans le fichier excel
     * @param Question $question
     */
    protected function writePropositions($question) {
        $col = self::FIRST_COL_PROPOSITION; 
        
        foreach ($question->getPropositions() as $proposition)
        {
            $cell = $col . $this->getCurrentLine();
            $this->getSheet()->setCellValue($cell, $proposition);
            if($this->IsLastAndBigProposition($proposition, $question))
            {
                $cell .= ':G' . $this->getCurrentLine();
                $this->getSheet()->mergeCells($cell);
                $this->getSheet()->getRowDimension($this->getCurrentLine())
                     ->setRowHeight(self::HEIGHT_LINE_FOR_PROPOSITION *
                             ceil(strlen($proposition)/self::MAX_PROPOSITION_SIZE));
            }
            $this->getSheet()->getStyle($cell)
                    ->applyFromArray($this->STYLE_PROPOSITION);
            $this->getSheet()->getStyle($cell)
                        ->getAlignment()->setWrapText(true);
            $col++;
        }
        $this->moveCurrentLine();
    }

    /**
     * 
     * @param string $proposition libelle de la proposition
     * @param Question $question la question lié à la proposition
     * @return boolean
     */
    protected function IsLastAndBigProposition($proposition, $question) {
        $size_libelle = strlen($proposition);
        $last_proposition = $question->getProposition(count($question->getPropositions())-1);
        return $proposition == $last_proposition && $size_libelle > self::BIG_PROPOSITION;
    }
    
    /**
     * Ecrit les questionnements d'une question et le résultat associé
     * @param Question $question
     */
    protected function writeQuestionnements($question) {
        if($question->getQuestionnement(0)->getLibelle() === NULL)
        {
            $this->writeLineResult($this->getColResult( $question->getQuestionnement(0), 
                    $question->getPropositions(), self::FIRST_COL_PROPOSITION), $question);
        }
        else
        {
            $this->getSheet()->getStyle('A' . ($this->getCurrentLine()-1))
                        ->applyFromArray($this->STYLE_QUESTIONNEMENT);
            foreach ($question->getQuestionnements() as $questionnement) {
                $this->writeLibelleQuestionnement($questionnement->getLibelle());
                $this->writeLineResult($this->getColResult(
                        $questionnement, $question->getPropositions(), 
                        self::FIRST_COL_PROPOSITION), $question);
            }
        }
        $this->moveCurrentLine();
    }
    
    /**
     * 
     * @param string $libelle libelle du questionnement
     */
    protected function writeLibelleQuestionnement($libelle) {
        $this->getSheet()->setCellValue('A' . $this->getCurrentLine(), $libelle);
        $this->getSheet()->getStyle('A' . $this->getCurrentLine())
                ->applyFromArray($this->STYLE_QUESTIONNEMENT);
        $this->getSheet()->getStyle('A' . $this->getCurrentLine())
                ->getAlignment()->setWrapText(true);
    }

    /**
     * 
     * @param int $col_result position du résultat obtenu pour cette ligne
     * @param Question $question la question lié au résultat
     */
    protected function writeLineResult($col_result, $question) {
        $end_col = self::FIRST_COL_PROPOSITION;        
        $nb_propositions = count($question->getPropositions());
        $last_propositions = $question->getProposition($nb_propositions-1);
        
        for($i=1; $i < $nb_propositions; $end_col++, $i++);
        
        if($this->IsLastAndBigProposition($last_propositions, $question))
        {
            $this->getSheet()->mergeCells($end_col . $this->getCurrentLine() .
                    ':G' . $this->getCurrentLine());
            $end_col = 'G';
        }
        
        $this->getSheet()->getStyle(self::FIRST_COL_PROPOSITION . 
                    $this->getCurrentLine() . ':' . $end_col . $this->getCurrentLine())
                    ->applyFromArray($this->STYLE_RESULT);
        if($col_result <= $end_col)
        { 
            $this->getSheet()->setCellValue($col_result . 
                    $this->getCurrentLine(), 'X');  
        }
        $this->moveCurrentLine();
    }
    
    /**
     * Retourne la colonne du resultat obtenu pour le questionnement
     * @param Questionnement $questionnement
     * @param Array[Proposition] $propositions
     * @param string $col premier colonne des propositions
     * @return String
     */
    protected function getColResult($questionnement, $propositions, $col) {
        for($i = 0; $i < count($propositions) 
                && $questionnement->getResult() != $propositions[$i]; $i++)
        {
                    $col++;
        }
        return $col;
    }
    
    protected function protectWorksheet() {
        $this->getSheet()->getProtection()->setSheet(true);
        $this->getSheet()->getProtection()->setSort(true);
        $this->getSheet()->getProtection()->setInsertRows(true);
        $this->getSheet()->getProtection()->setFormatCells(true);
        $this->getSheet()->getProtection()->setPassword('root@psw');
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
        if($stage == NULL)
        {
            $this->_stage = new Stage();
        }
        else
        {
            $this->_stage = $stage;    
        }
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
     * @param int $number_move nombre de ligne à avancer
     * @return int retourne la ligne avant le déplacement
     */
    protected function moveCurrentLine($number_move=1){
        $current_line = $this->getCurrentLine();
        $this->setCurrentLine($this->getCurrentLine() + $number_move);
        return $current_line;
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
    
    /**
     * 
     * @return string Nom de la feuille excel
     */
    public function getSheetName() {      
        $title = $this->getStage()->getEtudiant().'';
        
        if(strlen($title) > 31)
        {
            $title = substr($title,0,28).'...';
        }
        
        return $title;
    }
//</editor-fold>
}
