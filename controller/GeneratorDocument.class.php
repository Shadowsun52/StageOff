<?php
namespace stageOff\controller;
use stageOff\business\DocumentEtudiant;
use stageOff\business\DocumentMds;

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
     * Genere un fichier excel du questionnaire voulu et retour le lien vers 
     * ce fichier
     * @param int $id_stage identifiant du stage lié au questionnaire voulu
     * @param int $type type du questionnaire voulu
     * @throws Exception
     */
    public static function generateDocument($id_stage, $type) {
        $document = self::initDocument($id_stage, $type);
        $document->generateDocument();
    }
    
    /**
     * 
     * @param int $id_stage identifiant du stage lié au questionnaire voulu
     * @param int $type type du questionnaire voulu
     * @return string retourne le lien vers le document
     * @throws Exception
     */
    public static function getLinkDocument($id_stage, $type) {
        $document = self::initDocument($id_stage, $type);
        return $document->getLink();
    }
    
    /**
     * 
     * @param int $id_stage identifiant du stage lié au questionnaire voulu
     * @param int $type type du questionnaire voulu
     * @return Document retourne un object extends de document
     * @throws Exception
     */
    protected static function initDocument($id_stage, $type) {
        if($type == self::TYPE_STAGIARE) 
        {
            return new DocumentEtudiant($id_stage);
        }
        elseif($type == self::TYPE_MDS)
        {
            return new DocumentMds($id_stage);
        }
        else
        {
            throw new Exception('Type de questionnaire inconnu');
        }
    }
}
