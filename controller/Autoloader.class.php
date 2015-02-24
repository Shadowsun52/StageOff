<?php
/**
 * Description of Autoloader
 *  Classe permettant de créer une fonction d'autoload pour charger des classes
 * dans un répectoire définit par la variable $repertory
 * 
 * La classe ne peut être que créer et ne comporte pas de méthode public
 * @author Alexandre
 */
class Autoloader {
    
    private $_function_autoload;
    private $_path;
    
    public function __construct($repertory) {
        $this->_setPath($repertory);
        $this->_setFunctionAutoload();
        spl_autoload_register($this->_getFunctionAutoload());
    }

    private function _setPath($repertory) {
        $this->_path = config::read('ROOT') . $repertory;
        
        if(substr($this->_path, -1, 1) != '/')
        {
            $this->_path .= '/';
        }
    }
    
    public function getPath() {
        return $this->_path;
    }
    
    private function _setFunctionAutoload() {
        $this->_function_autoload = function ($class) {
            if(file_exists($this->getPath() . $class . '.class.php'))
            {
                require_once $this->getPath() . $class . '.class.php';
            }
            else
            {
                throw new Exception ('la classe ' . $class . ' n\'existe pas');
            }
        };
    }
    
    private function _getFunctionAutoload() {
        return $this->_function_autoload;
    }
    
    public function removeAutoload() {
        spl_autoload_unregister($this->_getFunctionAutoload());
    }
}
