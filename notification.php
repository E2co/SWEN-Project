<?php

$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "school_db";     

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
    SELECT s.id, s.name
    FROM (
        SELECT sa1.id, sa1.date AS date1, sa2.date AS date2, sa3.date AS date3
        FROM student_attendance sa1
        JOIN student_attendance sa2 ON sa1.id = sa2.id AND sa2.date = DATE_ADD(sa1.date, INTERVAL 1 DAY)
        JOIN student_attendance sa3 ON sa1.id = sa3.id AND sa3.date = DATE_ADD(sa2.date, INTERVAL 1 DAY)
        WHERE sa1.status = 'absent' AND sa2.status = 'absent' AND sa3.status = 'absent'
    ) AS consecutive_absences
    JOIN students s ON s.id = consecutive_absences.id
    GROUP BY s.id, s.name
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
       
        echo 'Student ID: ' . $row["id"] . ' - Name: ' . $row["name"] . ' has been absent for 3 consecutive days.';
     
    }
} else {
    echo '<div class="notification">No students with 3 consecutive absences found.';
    
}

$conn->close();
?>
