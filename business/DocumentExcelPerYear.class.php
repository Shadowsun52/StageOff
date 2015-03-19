<?php
namespace stageOff\business;

/**
 * Description of DocumentExcelPerYear
 *
 * @author Alexandre
 */
class DocumentExcelPerYear extends DocumentExcel{
    const SAVE_SUBFOLDER = '';
    
    public function __construct($type_questionnaire) {
        parent::__construct($type_questionnaire);
    }
    
    protected function addContain() {
        
    }

    protected function getSavePath() {
        
    }

    public function getFileName() {
        
    }

}
