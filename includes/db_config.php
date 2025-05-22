<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "hospital_db"; // Make sure this matches your actual DB name

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
