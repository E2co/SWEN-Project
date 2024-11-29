<?php

$host = 'localhost';
$db = 'school_db';
$user = 'root';
$pass = '';

try {
    
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $studentName = $_POST['studentName'];
    $studentID = $_POST['studentID'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    $sql = "
        SELECT sa.student_id, s.name, sa.date, sa.status
        FROM student_attendance sa
        JOIN students s ON sa.student_id = s.id
        WHERE 1=1
    ";

    $parameters = [];

    if(!empty($studentName)){
        $sql .= " AND s.name LIKE :studentName";
        $parameters[':studentName'] = '%' . $studentName . '%';
    }

    if(!empty($studentID)){
        $sql .= " AND sa.student_id LIKE :studentID";
        $parameters[':studentID'] = '%' . $studentID . '%';
    }

    if(!empty($startDate) && !empty($endDate)){
        $sql .= " AND sa.date  BETWEEN :startDate AND :endDate";
        $parameters[':startDate'] = $startDate;
        $parameters[':endDate'] = $endDate;
    }

    $stmt = $pdo->prepare($sql);

    foreach($parameters as $key => $value){
        $stmt->bindValue($key, $value);
    }
        
    $stmt->execute();

    $attendanceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($attendanceRecords);
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
}
?>
