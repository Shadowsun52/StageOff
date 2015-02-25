<?php
namespace stageOff;
use Exception;
/**
 * Classe static pour charger les classes utilisées dans l'app
 * 
 * @author Alexandre
 */
class Autoloader {
    
    public static function register() {
        spl_autoload_register(array(__CLASS__, '_autoload'));
    }

    private static function _autoload($class){
        if(strpos($class, __NAMESPACE__ . "\\") === 0)
        {
            $class = str_replace(__NAMESPACE__ . '\\', '', $class);
            $class = str_replace('\\', '/', $class);
            if(file_exists($class . '.class.php'))
            {
                require_once $class . '.class.php';
            }
            else
            {
                throw new Exception ('la classe ' . $class . ' n\'existe pas');
            }
        }
    }
}
