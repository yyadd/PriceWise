<?php

class User extends CI_Model{
    
    public $email;
    public $firstName;
    public $lastName;
    //private $password;
    
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
    }
    
    
    
    
    function CreateUser($email,$firstName,$lastName){
        $this->email = $email;
        ///$this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        
        
        return $this;
    }
    
}