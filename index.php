<?php
namespace stageOff;
use Exception;

    try {
        include('init.php');
        $controller = new controller\Controller();
        $test = $controller->getStage(312);
        $excel = new business\DocumentTest(1,312);
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
            var_dump($test);
            $data = new data\DatabaseAccess();
            var_dump($data->getQuestionnaire(1, 127));
        ?>
    </body>
</html>
