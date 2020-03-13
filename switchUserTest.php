<?php 
require_once('classes/User.php');
require_once('classes/Card.php');
//the scripts task is to change the loged user session vcaraible $_SESSION['user']
session_start();

$userId=$_POST['userId'];
$_SESSION["userid"]=$userId;
//create new empty arrray of card products  
//$_SESSION["cardProducts"] = array();
//$_SESSION["card"]=new Card(1,"1232234",100,234); 

  echo 1; 

?>
