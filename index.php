<?php
namespace stageOff;
use Exception;

    try {
        include('init.php');
        $controller = new controller\Controller();
        $test = $controller->getStage(127,1);
        $excel = new business\DocumentTest(2,967);
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
            var_dump($excel->getStage());
            $data = new data\DatabaseAccess();
            echo "<p>_________________________________</p>";
            var_dump($data->getQuestionnaire(1, 127));
        ?>
    </body>
</html>
