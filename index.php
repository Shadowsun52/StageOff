<?php
    try {
        include('autoload.php');
        $p = new Etudiant(32954);
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
