<?php
require_once('dbconnection.php');

$sql = "DELETE FROM rates";
if ($conn->query($sql) == TRUE) 
{
    echo "All rates deleted";
} 
else 
{
    echo "Error deleting record: " . $conn->error;
}
?>