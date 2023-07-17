<?php
$host     = "localhost";
$db_name  = "employee_db";
$username = "root";
$password = "";

$mysqli   = mysqli_connect($host, $username, $password, $db_name);

if ($mysqli->connect_error) {
   die("Erorr : " . $mysqli->connect_errno . " " . $mysqli->connect_error);
}
