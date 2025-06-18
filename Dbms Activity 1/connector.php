<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "studentattendancedb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //for debbuging purpsoses you can also add a echo to display a message
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
