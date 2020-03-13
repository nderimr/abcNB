<?php
   require_once('dbconnection.php'); 
   require_once('classes/Product.php');
   require_once('classes/User.php');
   require_once('classes/Card.php');
 

   if(!isset( $_SESSION ) ) {
       session_start();
   }
    //loged user id
   if(!isset($_SESSION["userid"]))
     { 
           $_SESSION["userid"] = 2; //  user select from database (developers choice)
     }
   
   //products in the users cart initialy empty 
   if(!isset($_SESSION["cardProducts"]))
         $_SESSION["cardProducts"] = array(); //array of products added on the card
   
    //loged user object initially null      
    $_SESSION["user"]=null; 
   
    //create an Sesion of cards  
 if(!isset($_SESSION['cards']))
    {
      $_SESSION["cards"] = array();
    }
 /*  if (!isset($_SESSION['card']))
   {
     $_SESSION["card"]=new Card(1,"1232234",100,234); 
   }
   */    
      $userId=$_SESSION["userid"];
  
   // get the users data  with  id$ userId 
   $sql="SELECT * FROM `users` WHERE users.id=".$userId;
   $result = mysqli_query($conn, $sql);
   if (mysqli_num_rows($result) > 0) 
   {   
         $row=mysqli_fetch_assoc($result);
            
         $_SESSION["user"]=new User($userId, $row["username"],$row["firstname"], $row["lastName"],$row["lastLogin"],$row["address"],$row["zipCode"],$row["country"]);   
         
         //check if users cart is already created in session
         $cartIscreated=false;
         foreach($_SESSION['cards'] as $card)
           {
             if ($card->getUserId()== $_SESSION["user"]->getId())
             {
               $_SESSION['card']=$card;
               $cartIscreated=true;
             }
          }         
         if(!$cartIscreated)//if user is logged for the first time for that session create new cart for him 
         { 
           srand(mktime());
           $card=new Card(rand()%1000,'xyz',100,'1234',$_SESSION["user"]->getId()); //create card for the user with randmom id 
           $_SESSION['cards'][]=$card;            //add the created card to the array of cards
           $_SESSION['card']=$card;
          } 
      }
    else 
     {
         echo "User not found ";
     } 
     
//get all users except the logged user (only for test porpose)
  $sql="SELECT id,username FROM users WHERE is_active =1 AND id !=".$_SESSION['user']->getId();
  $result=mysqli_query($conn, $sql);
  $users=array();
  if (mysqli_num_rows($result) > 0) 
  { 
      while($row = $result->fetch_assoc())    
        {
             $users[]=new User($row["id"],$row["username"],"x","x","x","x","x","x");

        }
  }

   
      
    //Select all product from database 
    $sql = "SELECT Pr.id, Pr.name, Pr.price, Pr.amount,Pr.description, Pr.imageUrl, AVG(rates.rate) as rating,count(rates.product_id) as ratedfrom FROM products  Pr Left Join rates ON Pr.id=rates.product_id Group BY Pr.id";
     $results = mysqli_query($conn, $sql);
     $products=array(); //create empty array for storing products 
   
   if (mysqli_num_rows($result) > 0) 
   { 
          while($row = $results->fetch_assoc())    
         {
             $products[]=new Product($row["id"],$row["name"],$row["price"], $row["description"],$row["amount"],$row["rating"],$row["imageUrl"]);   
         }
    } 
    else 
    {
       echo "no products on the store";
    } 
  
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	 <meta charset="utf-8">
     <title>Online store</title>
     
     
     <!-- include bootstrap cdn -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> 
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
     
     <!-- include jQuery library -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
     <!-- include main javascript file -->
     <script src="js/script.js"></script>  
     <link rel="stylesheet" href="css/style.css">
</head>

<body >
<!-- main bootstrap div -->
<div class="container">

<div class="header">
  <span> <b>logged user </b> </span> 
  <select class="selectItems" id="logedUser">
  <option selected value="<?php echo $_SESSION["user"]->getId() ?>"><?php echo $_SESSION["user"]->getUserName() ?></option>
  <?php foreach($users as $user) { ?>
    <option value="<?php echo $user->getId()?>"> <?php echo $user->getUserName()?> </option>
  <?php }?> 
  </select>
   <span> my total amount:$</span>  <span class="price" id="cardAmount"><?php echo $_SESSION['card']->getBalance(); ?></span>  
<button class="btn btn-danger resetRates"  id="resetRates"> Reset rates</button>
  </div>


<br/>
<table id="tblAddCard" class="table-bordered productList">
    <tr>
<?php
  
  foreach($products as $product){
   ?>

   <td id="<?php echo $product->getId()?>">
      <img class="productImage" height="220px" width="240px" src="<?php echo $product->getImageUrl();?>">
      <br/> 
      <span class="productName"><b><?php echo $product->getName(); ?> </b></span> <br/>
      <span><?php echo $product->getDescription(); ?> </span> <br/>
      <span>$</span>
      <span class="productPrice"><?php echo $product->getPrice(); ?> </span> <br/>
      <span>slect amount</span> <input type="Number"  style='width:35%' class="productAmount" min="1" value="1" id="amount<?php echo $product->getId()?>">  </input> <br/>
      <span class="ratingValue"><?php echo  $product->getRating()>0? $product->getRating()." of 5": "not rated yet"  ; ?> </span> <br/>
      <p> <button class="btn-primary addToCard" value="<?php echo $product->getId();?>" >Add to cart</button></p> 
       <select class="selectItems" id="<?php echo "select".$product->getId();?>" name="rate">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
     </select>  <button class="rateProduct btn-primary" value="<?php echo $product->getId();?>">rate product</button>


   </td>
   
  

  <?php }?>
  </tr>
  </table>
  



  <!-- users card  -->
 <?php require_once("userscard.php")  ?>
   

  
  
<?php //$this->render('views/layout/footer', []); ?>
</div> <!--  end container div -->

   
</body>
</html>	