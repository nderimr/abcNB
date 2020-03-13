<?php
class  User 
{
       private $id;
       private $username;
       private $firstName;
       private $lastName;
       private $lastLogin;
       private $address;
       private $zipCode;
       private $country;
       public function __construct($id,$username,$firsName,$lastName,$lastLogin,$address,$zipCode,$country)
       {
           $this->id=$id;
           $this->username=$username;
           $this->firstName=$firsName;
           $this->lastName=$lastName;
           $this->address=$address;
           $this->zipCode=$zipCode;
           $this->country=$country;
       }

       public function getId()
       {
           return $this->id;
       }
       
       public function setId($id)
       {
           $this->id=$id;
       }

       public function getUsername()
       {
           return $this->username;
       }

       public function setUsername($username)
       {
           $this->username=$username;
       }
       public function getFirstName()
       {
           return $this->firstName;
       } 
       
       public function setFirstName($firstName)
       {
           $this->firstName=$firstName;
       }
       
       public function getLastName()
       {
           return $this->lastName;
       } 
       public function setLastName($lastName)
       {
           $this->lastName=$lastName;
       }
       
       public function getLastLogin()
       {
           return $this->lastLogin;
       }
       public function setLastLogin($lastLogin)
       {
            $this->lastLogin=lastLogin;
       }
       
       public function getAddress()
       {
           return $this->address;
       }
       public function setAddress($address)
       {
            $this->address=$address;
       }
      
       public function getZipCode()
       {
           return $this->zipCode;
       }

       public function setZipCode($zipCode)
       {
            $this->zipCode=$zipCode;
       }

       public function getContry()
       {
           return $this->country;
       }

       public function setContry()
       {
           $this->country=$country;
       }
       
   }



?>