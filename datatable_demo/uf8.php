<?php
/* Database connection start */
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "gestion_reserves";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

$statut_1="Non levée";

$statut_utf8=utf8_encode($statut_1); 

$sql='UPDATE gr_lst_statut SET ls_description="'.$statut_utf8.'" WHERE ls_id=1';

echo $sql;

?>