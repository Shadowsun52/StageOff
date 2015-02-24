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
    private $_repertories;
    
    public function __construct($repertory) {
        $this->addRepertory($repertory);
        $this->_setFunctionAutoload();
        spl_autoload_register($this->_getFunctionAutoload());
    }

    public function addRepertory($repertory) {
        $this->_repertories[] = $repertory;
    }
    
    public function getPath($i) {
        $path = config::read('ROOT') . $this->_repertories[$i];
        
        if(substr($path, -1, 1) != '/')
        {
            $path .= '/';
        }
        return $path;
    }
    
    private function _setFunctionAutoload() {
        $this->_function_autoload = function ($class) {
            
            $i = 0;
            for(; $i < count($this->_repertories) 
                    && !file_exists($this->getPath($i) . $class . '.class.php'); $i++);
                    
            if($i < count($this->_repertories))
            {
                require_once $this->getPath($i) . $class . '.class.php';
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
