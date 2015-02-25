<?php
namespace stageOff\model;

/**
 * Pharmacie servant de lieu de stage
 *
 * @author Alexandre
 */
class Pharmacie {
    /**
     * @var int identifiant dans la base de données 
     */
    private $_id;
    
    /**
     * @var string
     */
    private $_address;
    
    /**
     * @var string 
     */
    private $_phoneNumber;
    
    /**
     * @var string
     */
    private $_faxNumber;
    
    /**
     * @var string
     */
    private $_mail;
    
    /**
     * 
     * @param int $id Identifiant de la pharmacie
     * @param string $address Adresse de la pharmacie
     * @param string $phoneNumber numéro de téléphone de la pharmacie
     * @param string $faxNumber numéro de fax de la pharmacie
     * @param string $mail adresse email de la pharmacie
     */
    public function __construct($id=NULL, $address=NULL, $phoneNumber=NULL, 
            $faxNumber=NULL, $mail=NULL) {
        $this->setId($id);
        $this->setAddress($address);
        $this->setPhoneNumer($phoneNumber);
        $this->setFaxNumber($faxNumber);
        $this->setMail($mail);
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
     * @param int $id identifianct de la pharmacie
     */
    public function setId($id) {
        $this->_id = $id;
    }
    
    /**
     * 
     * @return string
     */
    public function getAddress() {
        return $this->_address;
    }
    
    /**
     * 
     * @param string $address
     */
    public function setAddress($address) {
        $this->_address = $address;
    }
    
    /**
     * 
     * @return string
     */
    public function getPhoneNumber() {
        return $this->_phoneNumber;
    }
    
    /**
     * 
     * @param string $phoneNumber
     */
    public function setPhoneNumer($phoneNumber) {
        $this->_phoneNumber = $phoneNumber;
    }
    
    /**
     * 
     * @return string
     */
    public function getFaxNumber() {
        return $this->_faxNumber;
    }
    
    /**
     * 
     * @param string $faxNumber
     */
    public function setFaxNumber($faxNumber) {
        $this->_faxNumber = $faxNumber;
    }
    
    /**
     * 
     * @return string
     */
    public function getMail() {
        return $this->_mail;
    }
    
    /**
     * 
     * @param string $mail
     */
    public function setMail($mail) {
        $this->_mail = $mail;
    }
}
