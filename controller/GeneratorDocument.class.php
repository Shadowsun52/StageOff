<?php
namespace stageOff\controller;
use stageOff\business\DocumentExcelUnique;
use stageOff\business\DocumentExcelPerYear;

/**
 * Factory en static appelant la classe generant le fichier excel voulu et 
 * et retourne le lien pour télécharger le fichier excel
 *
 * @author Alexandre
 */
class GeneratorDocument {
    const TYPE_STAGIARE = 1;
    const TYPE_MDS = 2;
    
    /**
     * Genere un fichier excel du questionnaire voulu pour un stage donné
     * @param int $id_stage identifiant du stage lié au questionnaire voulu
     * @param int $type_questionnaire type du questionnaire voulu
     * @throws Exception
     */
    public static function generateDocumentUnique($id_stage, $type_questionnaire) {
        $document = new DocumentExcelUnique($type_questionnaire, $id_stage);
        $document->generateDocument();
    }
    
    /**
     * Retourne le lien d'un fichier excel pour un stage spécifique 
     * @param int $id_stage identifiant du stage lié au questionnaire voulu
     * @param int $type_questionnaire type du questionnaire voulu
     * @return string retourne le lien vers le document
     * @throws Exception
     */
    public static function getLinkDocumentUnique($id_stage, $type_questionnaire) {
        $document = new DocumentExcelUnique($type_questionnaire, $id_stage);
        return $document->getLink();
    }
    
    /**
     * Generer le fichier excels des stages des étudiants terminant leur études 
     * durant l'année entrée
     * @param type $year 
     * @param type $type_questionnaire type de questionnaire voulu
     * @return string le lien vers le document excel
     */
    public static function getDocumentPerYear($year, $type_questionnaire) {
        $document = new DocumentExcelPerYear($type_questionnaire, $year);
        $document->generateDocument();
        return $document->getLink();
    }
}
