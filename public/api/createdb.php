
<?php
$servername = "filepond-boilerplate-php_mariadbtest_1";
$username = "root";
$password = "mypass";

echo "Connecting to ".$servername."<br>";
// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE OSPREY_UPLOAD";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully <br>";
} else {
    echo "Error creating database: " . $conn->error ."<br>";
}

$conn->close();

echo "Creating table <br>";

// Create connection


$conn = new mysqli($servername, $username, $password, "OSPREY_UPLOAD");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// sql to create table
$sql = "CREATE TABLE Uploads (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    filename VARCHAR(50) NOT NULL,
    title VARCHAR(50) NOT NULL,
    purpose VARCHAR(30),
    status VARCHAR(30), 
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table Uploads created successfully <br>";
    } else {
        echo "Error creating table: " . $conn->error;
    }

$conn->close();