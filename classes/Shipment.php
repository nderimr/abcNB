<?php 
class Shipment 
{  
    private $id;
    private $type;
    private $price;
    public function __construct($id,$type,$price)
    {
        $this->id=$id;
        $this->type=$type;
        $this->price=$price;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id=$id;
    }

    public function getType()
    {
        return $this->type;
    }
    public function setType($type)
    {
        $this->type=$type;
    }
   
    public function getPrice()
    {
        return $this->price;
    }
    public function setPrice($price)
    {
        $this->price=$price;
    }
}
?>