<?php
$host = 'localhost';
$user = 'root';
$password = '';  
$dbname = 'managment_art';
$port = 3307; 

$conn = new mysqli($host, $user, $password, $dbname, $port);

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}
?>
