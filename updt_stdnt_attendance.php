<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "school_db"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$input = file_get_contents('php://input');
$attendanceData = json_decode($input, true);

if(!$attendanceData || !is_array($attendanceData)){
    echo json_encode(["success" => false, "error" => "Invalid input data"]);
    exit;
}

$sql = "INSERT INTO student_attendance (id, status, date) VALUES (?, ?, CURDATE()) ON DUPLICATE KEY UPDATE status = VALUES(status)";

$stmt = $conn->prepare($sql);

if(!$stmt){
    echo json_encode(["success" => false, "error" => "Failed to prepare SQL statement"]);
    exit;
}

foreach($attendanceData as $studentId => $status){
    $stmt->bind_param("is", $studentId, $status);

    if(!$stmt->execute()){
        echo json_encode(["success" => false, "error" => "Failed to update attendance for student ID: $studentId"]);
        exit;
    }
}

$stmt->close();
$conn->close();

echo json_encode(["success" => true, "message" => "Attendance updated successfully"]);
?>