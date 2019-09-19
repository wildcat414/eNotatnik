<?php
require_once("config.php");
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// sql to create table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    login VARCHAR(64) UNIQUE NOT NULL,
    password VARCHAR(64) NOT NULL,
    email VARCHAR(64) UNIQUE NOT NULL,
    registeredAt INT(10) UNSIGNED NOT NULL,
    token VARCHAR(64)
    )";
    
if ($conn->query($sql) === TRUE) {
    echo "Table users created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
}

// sql to create table
$sql = "CREATE TABLE IF NOT EXISTS notes (
    userId INT(6) UNSIGNED PRIMARY KEY,
    content TEXT NOT NULL,
    editedAt INT(10) UNSIGNED NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(id)
    )";

if ($conn->query($sql) === TRUE) {
    echo "Table notes created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
}

// sql to create table
$sql = "CREATE TABLE IF NOT EXISTS history_logs (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    userId INT(6) UNSIGNED NOT NULL,
    editedAt INT(10) UNSIGNED NOT NULL,
    charDiff INT(6) NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(id)
    )";

if ($conn->query($sql) === TRUE) {
    echo "Table history_logs created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
