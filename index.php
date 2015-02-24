<?php
    try {
        include('init.php');
        $controller = new Controller();
        $p = $controller->getEtudiant(32954);
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
            echo $p->getLastName();
        ?>
    </body>
</html>
