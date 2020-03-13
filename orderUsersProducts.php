<?php
//orders all users products by setting setting the orders status in shipment user id 
require_once('dbconnection.php'); 
require_once('classes/Product.php');
require_once('classes/User.php');
require_once('classes/Card.php');
 

session_start();

/*insert new order in orders table columns 
*   usersId  
*   products id, 
*   amount
*   shipping type  
*/


$userId=$_SESSION["user"]->getId();

$shipmentId=$_POST['shipmentId'];

$shipmentPrice=getShipmentPriceById($shipmentId,$conn);

$totalOrderPrice=0.0;
$totalOrderPrice+=floatval($shipmentPrice); //stores order total price including shipping price 

$sql="";
$validBalance=true;
foreach($_SESSION['cardProducts'] as $product) 
      if($product->getUserId()==$userId)  
      {
            $totalOrderPrice=$totalOrderPrice+floatval(floatval($product->getPrice())*floatval($product->getAmount()));
           
            if($totalOrderPrice>$_SESSION['card']->getBalance()) //if total prise grater than amount in the card 
            {
                $validBalance=false;
                echo 0;
                exit;
            }
            $sql.="INSERT INTO orders (user_id,product_id,amount,date_time,shipment_type_id,status_id) values(". 
                                       $userId.",".$product->getId().",".$product->getAmount().",now(),1,1);";  
        }
       
       $currentBalance=$_SESSION['card']->getBalance();
       $newBalance=$currentBalance-$totalOrderPrice;
       
       $_SESSION['card']->setBalance($newBalance);
        if($conn->multi_query($sql) === TRUE)
        { 
            foreach($_SESSION['cardProducts'] as $key=> $product)
               if($product->getUserId()==$userId)
                 unset($_SESSION['cardProducts'][$key]);
             echo $_SESSION['card']->getBalance(); //return new balance
       } 
           else
         {
             echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
         } 


/**
    * get shipment price from database by shippment id 
    *         
    * 
    * @return      int   
    *
    */
function getShipmentPriceById($shipmentId,$conn)
{
     $sql="Select price FROM shipmenttype where id=".$shipmentId;
     $result = mysqli_query($conn, $sql);
        
     if (mysqli_num_rows($result) > 0) 
     {
           $row=mysqli_fetch_assoc($result); 
           $shipmentPrice =$row["price"];
           return $shipmentPrice;
     } 
     else 
     {
         echo "no shipment type found with provided ID";
         exit;
     } 
}


?>