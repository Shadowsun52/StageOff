<?php
    require_once 'model/config.class.php';
    require_once 'controller/Autoloader.class.php';
    
    $autoloader = new Autoloader('model');
    $autoloader->addRepertory('data');    