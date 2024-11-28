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

// Validate input
if (!isset($_POST['student_id']) || !isset($_POST['student_name']) || 
    !isset($_POST['student_grade']) || !isset($_POST['parent_student_id']) || 
    !isset($_POST['parent_name']) || !isset($_POST['parent_email']) || 
    !isset($_POST['parent_telephone'])) {
    echo json_encode(['error' => 'Missing required information']);
    exit();
}

$student_id = $_POST['student_id'];
$student_name = $_POST['student_name'];
$student_grade = $_POST['student_grade'];
$parent_student_id = $_POST['parent_student_id'];
$parent_name = $_POST['parent_name'];
$parent_email = $_POST['parent_email'];
$parent_telephone = $_POST['parent_telephone'];

try {
    // Update student information
    $stmt = $pdo->prepare("UPDATE students SET name = :name, grade = :grade WHERE id = :id");
    $stmt->execute([
        'name' => $student_name,
        'grade' => $student_grade,
        'id' => $student_id
    ]);

    // Update parent information
    $stmt = $pdo->prepare("UPDATE parents_contact SET `parent name` = :parent_name, email = :email, `telephone number` = :telephone WHERE student_id = :student_id");
    $stmt->execute([
        'parent_name' => $parent_name,
        'email' => $parent_email,
        'telephone' => $parent_telephone,
        'student_id' => $parent_student_id
    ]);

    $auditStmt = $pdo->prepare("INSERT INTO student_audit (student_id, operation) VALUES (:student_id, 'updated')");
    $auditStmt->execute([
        'student_id' => $student_id
    ]);
    

    echo json_encode(['success' => true]);

} catch(PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>