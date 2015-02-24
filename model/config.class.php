<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of config
 *
 * @author Alexandre
 */
class config {
    static $confArray;

    public static function read($name)
    {
        return self::$confArray[$name];
    }

    public static function write($name, $value)
    {
        self::$confArray[$name] = $value;
    }

}

//Config::write('db.host', 'localhost');
//Config::write('db.basename', 'congespharma');
//Config::write('db.user', 'root');
//Config::write('db.password', '');
Config::write('db.host', 'localhost');
Config::write('db.basename', 'stageoff');
Config::write('db.user', 'root');
Config::write('db.password', '');

// Debug mode
Config::write('debug', false);

// Constantes
$root = $_SERVER['DOCUMENT_ROOT'];
if (substr($root, -1, 1) == '/') $root = substr($root, 0, -1);
Config::write('ROOT', $root . dirname($_SERVER["PHP_SELF"]). '/');