<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentregistry"; 


$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database studentregistry created successfully or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error;
}


$conn->select_db($dbname);

$sql = "CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    id_number VARCHAR(10) NOT NULL UNIQUE,
    parent_id INT NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Table students created successfully.<br>";
} else {
    echo "Error creating table students: " . $conn->error;
}


$sql = "CREATE TABLE IF NOT EXISTS parents (
    parent_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    phone VARCHAR(15)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table parents created successfully.<br>";
} else {
    echo "Error creating table parents: " . $conn->error;
}

$sql = "INSERT INTO parents (name, email, phone) VALUES
    ('John Smith', 'john.smith@example.com', '555-1234'),
    ('Jane Doe', 'jane.doe@example.com', '555-5678'),
    ('Robert Brown', 'robert.brown@example.com', '555-8765'),
    ('Emily Davis', 'emily.davis@example.com', '555-4321'),
    ('Michael Johnson', 'michael.johnson@example.com', '555-6789')";
if ($conn->query($sql) === TRUE) {
    echo "Sample data inserted into parents table.<br>";
} else {
    echo "Error inserting data into parents: " . $conn->error;
}

$sql = "INSERT INTO students (name, id_number, parent_id) VALUES
    ('Alice Smith', 'S001', 1),
    ('Bob Doe', 'S002', 2),
    ('Charlie Brown', 'S003', 3),
    ('Daisy Davis', 'S004', 4),
    ('Evan Johnson', 'S005', 5)";
if ($conn->query($sql) === TRUE) {
    echo "Sample data inserted into students table.<br>";
} else {
    echo "Error inserting data into students: " . $conn->error;
}


$conn->close();
?>