<?php
namespace stageOff;
use stageOff\controller\GeneratorDocument;
use Exception;

    try {
        include('init.php');
        $excel = GeneratorDocument::generateDocument(942,  GeneratorDocument::TYPE_MDS);
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
        <?php
            echo $excel;
        ?>
    </body>
</html>
