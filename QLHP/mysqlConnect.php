<?php
$hostname = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "DK";

$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");
?>  