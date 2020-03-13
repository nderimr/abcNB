<?php
    require_once('dbconnection.php'); 
    require_once('classes/Product.php');
    require_once('classes/User.php');
    require_once('classes/Card.php');
     
    
    session_start();

    $productId=$_POST['productId'];
    $rateValue=$_POST['rateValue'];
    $userId=$_SESSION['user']->getId();
    rateProduct($productId,$userId,$rateValue, $conn);
    
    /**
    * checks if current user with @id $userId ahs rated the product with @id $productId. 
    *         
    * 
    * @return      bool  
    *
    */
   function productIsRatedByUser($productId,$userId,$conn)
   {
       $sql ="Select * from rates where product_id=".$productId." AND user_id=".$userId;
       
       $result = mysqli_query($conn, $sql);
       
         if (mysqli_num_rows($result) > 0) 
         {
             return true;
         }
         else
        {
            return false;
        }
    }

    /**
    * inserts users @id $userId products @id $productID and selected rate on rates table if the user has not rated the current product before 
    * return 0 if rate is not inserted on the database         
    * 
    * @return      void  
    *
    */
    function rateProduct($productId,$userId,$rateValue,$conn)
    {
        
      if(!productIsRatedByUser($productId,$userId,$conn))
      {
         $sql ="INSERT  INTO rates(product_id, user_id,rate) VALUES ($productId,$userId,$rateValue)";
         if(mysqli_query($conn, $sql))
         {
            $averageRate=getAverageRate($productId,$conn); //get average rate for product
            echo $averageRate;  
         } 
          else
        {
            echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
        } 
      }
         else 
      {
          echo 0;
      }//end if 
    }
     //get avarage rate for product 
    function getAverageRate($productId,$conn)
    {
        $sql="Select AVG(rate) as averagerate from rates where product_id=".$productId;
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc();
        return $row['averagerate'];
    }
  
?>
