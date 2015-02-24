<?php

/**
 * Description of Pharmacie
 *
 * @author Alexandre
 */
class Pharmacie {
    private $_id;
    private $_address;
    private $_phoneNumber;
    private $_faxNumber;
    private $_mail;
    
    public function __construct($id=NULL, $address=NULL, $phoneNumber=NULL, 
            $faxNumber=NULL, $mail=NULL) {
        $this->setId($id);
        $this->setAddress($address);
        $this->setPhoneNumer($phoneNumber);
        $this->setFaxNumber($faxNumber);
        $this->setMail($mail);
    }
    
    public function getId() {
        return $this->_id;
    }
    
    public function setId($id) {
        $this->_id = $id;
    }
    
    public function getAddress() {
        return $this->_address;
    }
    
    public function setAddress($address) {
        $this->_address = $address;
    }
    
    public function getPhoneNumber() {
        return $this->_phoneNumber;
    }
    
    public function setPhoneNumer($phoneNumber) {
        $this->_phoneNumber = $phoneNumber;
    }
    
    public function getFaxNumber() {
        return $this->_faxNumber;
    }
    
    public function setFaxNumber($faxNumber) {
        $this->_faxNumber = $faxNumber;
    }
    
    public function getMail() {
        return $this->_mail;
    }
    
    public function setMail($mail) {
        $this->_mail = $mail;
    }
}
