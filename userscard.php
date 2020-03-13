<?php
require_once("classes/Shipment.php");
  // get all shipment types from database
   $sql="SELECT * FROM shipmenttype";
   $result = mysqli_query($conn, $sql);
      
    if (mysqli_num_rows($result) > 0) 
   {
         while($row=mysqli_fetch_assoc($result)) 
         { //ad to the shipments array 
            $shipments[]=new Shipment($row["id"],$row["type"],$row["price"]);   
        }
    } 
   //get all in cart product stored in session  for user with $userId id
   function getUsersCartItems($userId)
   {

    
   }

?>

<br/>
   
  <h4 id="emptyCard">Your shipping cart is empty </h4>  
   <table id="tblcard" class="table-striped  table-bordered hasItems"  width="70%">
     
     <tr id="tblHeading">
           <th>name</th>
           <th>price ($)</th>
           <th>amount</th>
           <th>total price ($)</th>
           <th>remove</th>
     </tr>
     
    <?php $orderTotalPrice=0.0; 
        foreach($_SESSION['cardProducts'] as $product) { 
          if($product->getUserId()==$_SESSION['user']->getId()){
          $prPrice= ($product->getAmount())*(floatval($product->getPrice())); $orderTotalPrice+=$prPrice; //calculate product price with amount and add to orderTotalPrice
        ?>
                                                        
      <tr class="dataRow" id="row<?php echo $product->getId();?>">
       <td> <?php echo $product->getName(); ?> </td>  
       <td class="productPrice"> <?php echo $product->getPrice(); ?> </td>
       <td class="totalAmount"> <?php echo $product->getAmount(); ?> </td>
       <td class="totalProductPrice"> <?php echo $prPrice;  ?> </td>
       <td> <input type="number" style="width:25%" value="1"  min="1" step="1" class="removeAmount"></input>  <button style="width:70%" value="<?php echo $product->getId();?>" class='removeProductFromCard btn-danger'>Remove</button> </td>  
       
    </tr>
      <?php   }//end if 
           } /* end foreach */ ?>
</table>
<br/>

  
<table class="hasItems" width="70%">
<tr>
 <td>
    <select class="selectItems"  id="shippingType">
                 
      <option value="select">Select shipment type</option> 
      <?php  foreach($shipments as $shipment){ ?>
          <option id="<?php echo $shipment->getId(); ?>" value="<?php echo $shipment->getPrice(); ?> "> <?php echo $shipment->getType();?> </option>
       <?php } /*end foreach*/ ?>     
    </select>
     &emsp;
    $<span id="shipingCost"> </span>  
</td> 
    <td>
        <b>Total price:</b> <span class="price" id="totalPrice"> <?php echo $orderTotalPrice ?></span>
    </td>

 <td>  
      <button style="width:100%"  class="btn btn-primary" id="orderCardItems">Order</button> 
 </td> 
  </tr>
  </table>





