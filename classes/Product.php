<?php
class Product
{
    private $id;
    private $name;
    private $price;
    private $description;
    private $amount;
    private $rating;
    private $imageUrl;
    private $userId;
    public function __construct($id,$name,$price,$description,$amount,$rating,$imageUrl)
    {
        $this->id=$id;
        $this->name=$name;
        $this->price=$price;
        $this->amount=$amount;
        $this->rating=$rating; 
        $this->imageUrl=$imageUrl;
        $this->description=$description;
    }

     public function getId()
     {
         return $this->id;
     } 
     
     public function setId($id)
     {
         $this->id=$id;
     }
     
     public function getName()
     {
         return $this->name;
     }

     public function setName($name)
     {
         $this->name=$name;   
     }
     
      public function getPrice()
      {
          return $this->price;
      }
      public function setPrice($price)
      {
          $this->price=$price;
      }
      
      public function getDescription()
      {
          return $this->description;
      }
      public function setDescription($description)
      {
          $this->description=$description;
      }
      
      public function getImageUrl()
      {
          return $this->imageUrl;
      }
      public function setImageUrl($imageUrl)
      {
          $this->imageUrl=$imageUrl;
      }
       
     public function getAmount()
     {
         return $this->amount;
     } 

      public function setAmount($amount)
     {
         $this->amount=$amount;
     }

     public function getRating()
     {
         return number_format((float)$this->rating, 2, '.', ''); 
     } 

      public function setRating($rating)
     {
         $this->rating=$rating;
     }

     public function getUserId()
     {
         return $this->userId;
     }
     public function setUserId($userId)
     {
        $this->userId=$userId;
     }



}