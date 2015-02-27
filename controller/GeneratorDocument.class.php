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
     * @return string un lien vers le ficher excel generé
     * @throws Exception
     */
    public function generateDocument($id_stage, $type) {
        if($type == self::TYPE_STAGIARE) 
        {
            $document = new DocumentEtudiant($id_stage);
            return $document->getLink();
        }
        elseif($type == self::TYPE_MDS)
        {
            //recuperer le type de stage (officine ou hospitalier
            return null;
        }
        else
        {
            throw new Exception('Type de questionnaire inconnu');
        }
    }
}
