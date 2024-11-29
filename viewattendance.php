<?php
// Database connection parameters
$host = 'localhost';
$db = 'school_db';
$user = 'root';
$pass = '';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the input data
    $studentName = $_POST['studentName'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Prepare the SQL query
    $stmt = $pdo->prepare("
        SELECT sa.student_id, s.name, sa.date, sa.status
        FROM student_attendance sa
        JOIN students s ON sa.student_id = s.id
        WHERE s.name LIKE :studentName
        AND sa.date BETWEEN :startDate AND :endDate
    ");

    // Bind parameters
    $stmt->bindValue(':studentName', '%' . $studentName . '%');
    $stmt->bindValue(':startDate', $startDate);
    $stmt->bindValue(':endDate', $endDate);

    // Execute the query
    $stmt->execute();

    // Fetch the results
    $attendanceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    echo json_encode($attendanceRecords);
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
}
?>
