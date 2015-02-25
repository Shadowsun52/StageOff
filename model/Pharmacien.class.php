<?php
namespace stageOff\model;

/**
 * Un pharmacien servant de maitre de stage pour un étudiant
 *
 * @author Alexandre
 */
class Pharmacien extends Person {
    /**
     * @var int identifiant dans la base de données du pharmacien 
     */
    private $_id;
    
    /**
     * @var Pharmacie Pharmacie où travaille le pharmacien
     */
    private $_pharmacie;
    
    /**
     * 
     * @param int $id Identifiant unique du pharmacien
     * @param string $lastname Nom du pharmacien
     * @param string $firstname Prenom du pharmacien
     * @param Pharmacie $pharmacie Pharmacie où travaille le pharmacien
     */
    public function __construct($id=null, $lastname = NULL, $firstname = NULL,
            $pharmacie = NULL) {
        parent::__construct($lastname, $firstname);
        $this->setId($id);
        $this->setPharmacie($pharmacie);
    }
    
    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->_id;
    }
    
    /**
     * 
     * @param int $id
     */
    public function setId($id) {
        $this->_id = $id;
    }
    
    /**
     * 
     * @return Pharmacie
     */
    public function getPharmacie() {
        return $this->_pharmacie;
    }
    
    /**
     * 
     * @param Pharmacie $pharmacie
     */
    public function setPharmacie($pharmacie) {
        if($pharmacie)
        {
            $this->_pharmacie = $pharmacie;
        }
        else
        {
            $this->_pharmacie = new Pharmacie();
        }
    }
}
