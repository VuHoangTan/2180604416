<?php
$host = "localhost";    
$username = "root";     
$password = "";         
$database = "Test1"; 
// Kết nối MySQL
$conn = new mysqli($host, $username, $password, $database);

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>