<?php
namespace stageOff;
use stageOff\controller\GeneratorDocument;
use Exception;

    try {
        include('init.php');
        GeneratorDocument::generateDocument(942,  GeneratorDocument::TYPE_MDS);
        $excel = GeneratorDocument::getLinkDocument(942,  GeneratorDocument::TYPE_MDS);
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
        <a/>
        <?php
        $DAO = new \stageOff\data\DatabaseAccess();
        $tab_mat = $DAO->getMatriculeEtudiantPerYear(2015);
        $tab_year = $DAO->getYearWithFinalStudent();
        var_dump($tab_year);
        ?>
    </body>
</html>
