<?php
class Card 
{ 
    private $id;
    private $number; //card number
    private $balance; //amunt of money available
    private $cvv; //card ccv number
    private $userId; //users card 
 
   public function __construct($id, $number,$balance,$cvv,$userId)
   {
       $this->id=$id;
       $this->number=$number;
       $this->balance=$balance;
       $this->cvv=$cvv;
       $this->userId=$userId;
  }

     
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id=$id;
    }
   
    public function getBalance()
    {
         return $this->balance;
    }
    public function setBalance($balance)
    {
    $this->balance=$balance;
    }
   
    public function setUserId($userId)
    {
        $this->userId=$userId;
    } 
    public function getUserId()
    {
        return $this->userId;
    }

    public function withdrowAmount($amount)
    {
       if($amount>$balance)
           return "Speicified amount is greater than balance";
         else
           $balance-=$amount;
               
     }
    public function addAmount($ammunt)
    {
          $this->balance+=$amount;
    }


}