<?php
    try {
        include('init.php');
        $controller = new Controller();
        $test = $controller->getPharmacien(254);
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
            echo $test->getLastName();
        ?>
    </body>
</html>
