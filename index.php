<?php
namespace stageOff;
use stageOff\controller\GeneratorDocument;
use Exception;

    try {
        include('init.php');
        GeneratorDocument::generateDocumentUnique(942,  GeneratorDocument::TYPE_MDS);
        $excel = GeneratorDocument::getLinkDocumentUnique(942,  GeneratorDocument::TYPE_MDS);
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"> 
        <title></title>
    </head>
    <body>
        <a href="<?php echo $excel; ?>">
            <img src="./images/excel.png" alt="lien vers document Excel"/>
        <a/><br/>
        <?php
        //debug pour branche PerYear
        $DAO = new \stageOff\data\DatabaseAccess();
        //debug Data Per year
//        $tab_mat = $DAO->getMatriculeEtudiantPerYear(2013);
        $tab_year = $DAO->getYearWithFinalStudent(data\DatabaseAccess::TYPE_EVALUATION_MDS);
        var_dump($tab_year);
        
        $etu = $DAO->getStage(942);
        $sheet = new business\SheetExcelEtudiant($etu);
//        $worksheet = $sheet->createSheet();
//        $test = new \PHPExcel();
//        $test->addSheet($worksheet);
//        $writer = new \PHPExcel_Writer_Excel2007($test);
//        var_dump($writer->getPHPExcel());
//        $writer = new \PHPExcel_Writer_Excel2007($sheet->debugCreate());
//        $writer->save(model\config::read('ROOT') . 'evaluation/test.xlsx');
          
//        creation fichier document unique
//        $testfinal = new business\DocumentExcelUnique(business\DocumentExcelUnique::TYPE_QUESTIONNAIRE_MDS, 942);
//        var_dump($testfinal->getFileName());
//        var_dump($testfinal->getLink());
//        $testfinal->generateDocument();
        
        $matricule_etudiant= 322364;
        echo "id stage pour l'étudiant $matricule_etudiant";
        $stages = $DAO->getAllIdStageForEtudiant($matricule_etudiant, data\DatabaseAccess::TYPE_EVALUATION_MDS);
        var_dump($stages);
        
        $year = 2014;
        echo "liste etudiant pour l'année $year - " . ($year+1);
        $etudiants = $DAO->getMatriculeEtudiantPerYear($year, data\DatabaseAccess::TYPE_EVALUATION_MDS);
        var_dump($etudiants);
        $nb=0;
        foreach ($etudiants as $etudiant)
        {
            $stages = $DAO->getAllIdStageForEtudiant($etudiant, data\DatabaseAccess::TYPE_EVALUATION_MDS);
            var_dump($stages);
            $nb += count($stages);
        }
        echo "$nb <br/>";
        echo '_____________________________';
        var_dump(GeneratorDocument::getYearWithFinalStudent(GeneratorDocument::TYPE_EVALUATION_MDS));
?>
        <a href="<?php echo GeneratorDocument::getDocumentPerYear($year, GeneratorDocument::TYPE_MDS); ?>">
            <img src="./images/excel.png" alt="lien vers document Excel"/>
        <a/><br/>
    </body>
</html>