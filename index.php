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
        <a href="<?php echo $excel; ?>">
            <img src="./images/excel.png" alt="lien vers document Excel"/>
        <a/>
    </body>
</html>
