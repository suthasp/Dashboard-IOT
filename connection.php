<?php
session_start();

$servername = "localhost";
$username = "touchcom_board";
$password = "Engimanyz4714";
$dbname = "touchcom_esp32";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
?>