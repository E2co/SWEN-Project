<?php
session_start();

$host = '127.0.0.1'; 
$username = 'root';   
$password = '';       
$dbname = 'school_db'; 

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];

    // Store the message in the session to be used later on the dashboard
    $_SESSION['notification_message'] = $message;
}
$query = "SELECT students.name, student_attendance.date FROM student_attendance 
          INNER JOIN students ON student_attendance.id = students.id 
          WHERE student_attendance.status = 'absent'";  // Fetch all absences
$result = $conn->query($query);

$pendingNotifications = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pendingNotifications[] = $row;
    }
}

// Close the database connection
$conn->close();
?>
