<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$dbname = 'school_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Check if student_id is provided
if (!isset($_POST['student_id'])) {
    echo json_encode(['error' => 'No student ID provided']);
    exit();
}

$student_id = $_POST['student_id'];

try {
    // Fetch student information
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :student_id");
    $stmt->execute(['student_id' => $student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo json_encode(['error' => 'Student not found']);
        exit();
    }

    // Fetch parent information
    $stmt = $pdo->prepare("SELECT * FROM parents_contact WHERE student_id = :student_id");
    $stmt->execute(['student_id' => $student_id]);
    $parent = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$parent) {
        echo json_encode(['error' => 'Parent information not found']);
        exit();
    }

    // Return student and parent information
    echo json_encode([
        'student' => [
            'id' => $student['id'],
            'name' => $student['name'],
            'grade' => $student['grade']
        ],
        'parent' => [
            'student_id' => $parent['student_id'],
            'parent_name' => $parent['parent name'],
            'email' => $parent['email'],
            'telephone_number' => $parent['telephone number']
        ]
    ]);

} catch(PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>