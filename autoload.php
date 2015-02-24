<?php
    require_once 'model/config.class.php';
    require_once 'controller/Autoloader.class.php';
    
    new Autoloader('model');
    new Autoloader('data');