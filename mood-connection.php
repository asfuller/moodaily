<?php

$dbhost = "sql204.epizy.com";
$dbuser = "epiz_28519119";
$dbpass = "J5humBnKc8K7i";
$dbname = "epiz_28519119_mood";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname)){


	die("failed to connect!");
}

?>
