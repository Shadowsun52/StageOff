<?php
    try {
        include('init.php');
        $controller = new Controller();
        $test = $controller->getStage(312);
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
            echo $test->getMaitreDeStage()->getLastname();
        ?>
    </body>
</html>
