<?php
   require_once('dbconnection.php'); 
   require_once('classes/Product.php');
   require_once('classes/User.php');
    
   
    $statusId=1;  //default status id  
    $shipmentTypeId=1;
    if( !isset( $_SESSION ) ) {
        session_start();
    }
    
    if(!isset($_SESSION['user']))
       exit;
    
    $userId=$_SESSION['user']->getId();
    $productInCard=false;
    $productId=$_POST['product']['id'];
    $productAmount=$_POST['product']['amount'];
    // add the posted product on the cardProducts array  
   if( isset($_SESSION['cardProducts']))
   {
       //check if product is already on the shipping card
        foreach($_SESSION['cardProducts'] as $product) 
        {
            if($product->getId()==$productId && $product->getUserId()==$userId) //if product on shipping cart for user update amount
            {
                $currentAmount=$product->getAmount();
                $product->setAmount($currentAmount+$productAmount);
                $productInCard=true;
                break;
            }
        }
        if(!$productInCard)
        {
            $pr=new Product($_POST['product']['id'],$_POST['product']['name'],$_POST['product']['price'],'..',$_POST['product']['amount'],1,$_POST['product']['imageUrl']);  
            $pr->setUserId($userId);
            array_push($_SESSION["cardProducts"],$pr);
            echo 1; //product added succesfully on the card      
        }
        else
        echo 0; //product was not added on the card
    }
   
     //find users card by id        
    function findusersCardById($userId)
    {
        foreach($_SESSION['cards'] as $key=>$card)
        {
            if($userId==$card->getId())
              {
                  return $key;
              }
        }
        
        return -1;

    }
   
   
?>