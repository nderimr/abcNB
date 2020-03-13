<?php 
    require_once('classes/User.php'); 
    require_once('classes/Product.php');
    session_start();
    
    $removeProductId =$_POST['productId'];
    $amount=$_POST['amount']; 
    
    removeProductFromListById($removeProductId,$amount);
    
    
    //removes the product with id @id from the list od products on the card 
    //return value  1 if product is found and removed from the list else returns 0 

    function removeProductFromListById($id,$amount)
    {
        $removed=false;
        foreach($_SESSION["cardProducts"] as $key=>$product)
         {
                if($product->getId()==$id && $product->getUserId()==$_SESSION['user']->getId()) //if product found for the user found
                {
                    if($product->getAmount()<$amount)  //if entered amunt greater than amount 
                    {
                        echo 0;
                        return;
                    }
                    else
                    if($product->getAmount()==$amount) //if entered amount equals to amount of the product in shipping cart
                    {
                       unset($_SESSION["cardProducts"][$key]);//remove product from cart items
                       $removed=true; 
                       break;
                    }
                    else
                    {
                        $_SESSION["cardProducts"][$key]->setAmount($product->getAmount()-$amount);
                        $removed=true; 
                    }
                }
         }
        if($removed)
        {
             echo 1; 
        }
        else 
        {
             echo 0;
        }
    }

?>