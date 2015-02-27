<?php
namespace stageOff;
use Exception;

    try {
        include('init.php');
        $excel = new business\DocumentMds(1,942);
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
        ?>
    </body>
</html>
