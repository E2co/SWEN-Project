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
        -- Check for 3 consecutive absences
        SELECT sa1.student_id, sa1.date AS date1, sa2.date AS date2, sa3.date AS date3
        FROM student_attendance sa1
        JOIN student_attendance sa2 ON sa1.student_id = sa2.student_id AND sa2.date = DATE_ADD(sa1.date, INTERVAL 1 DAY)
        JOIN student_attendance sa3 ON sa1.student_id = sa3.student_id AND sa3.date = DATE_ADD(sa2.date, INTERVAL 1 DAY)
        WHERE sa1.status = 'absent' AND sa2.status = 'absent' AND sa3.status = 'absent'
        
        UNION
        
        -- Check for 3 consecutive lateness
        SELECT sa1.student_id, sa1.date AS date1, sa2.date AS date2, sa3.date AS date3
        FROM student_attendance sa1
        JOIN student_attendance sa2 ON sa1.student_id = sa2.student_id AND sa2.date = DATE_ADD(sa1.date, INTERVAL 1 DAY)
        JOIN student_attendance sa3 ON sa1.student_id = sa3.student_id AND sa3.date = DATE_ADD(sa2.date, INTERVAL 1 DAY)
        WHERE sa1.status = 'late' AND sa2.status = 'late' AND sa3.status = 'late'
    ) AS consecutive_issues
    JOIN students s ON s.id = consecutive_issues.student_id
    GROUP BY s.id, s.name
"; 

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo 'Student ID: ' . $row["id"] . ' - Name: ' . $row["name"] . ' has been absent or late for 3 consecutive days.';
    }
    
} else {
    echo '<No students with 3 consecutive absences or lateness found.';
}

$conn->close();
?>
