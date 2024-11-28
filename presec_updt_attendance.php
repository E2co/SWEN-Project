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

if(!$attendanceData || !isset($attendanceData['studentId'])){
    echo json_encode(["success" => false, "error" => "Invalid input data"]);
    exit;
}

$sql = "INSERT INTO student_attendance (student_id, status, date) VALUES (?, 'late', CURDATE()) ON DUPLICATE KEY UPDATE status = 'late', date = CURDATE()";

$stmt = $conn->prepare($sql);

if(!$stmt){
    echo json_encode(["success" => false, "error" => "Failed to prepare SQL statement"]);
    exit;
}

$stmt->bind_param("s", $studentId);
$studentId = $attendanceData['studentId'];
    
if(!$stmt->execute()){
    echo json_encode(["success" => false, "error" => "Failed to update attendance"]);
    exit;
} else{
    echo json_encode(["success" => true, "message" => "Attendance updated successfully"]);
}

$stmt->close();
$conn->close();
?>