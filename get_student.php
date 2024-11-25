<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "school_db"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT id, name FROM students";
$result = $conn->query($sql);

$students = [];
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $students[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($students);

$conn->close();
?>