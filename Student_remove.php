<?php 

$host = 'localhost';
$dbname = 'school_db';
$username = 'root';  
$password = '';      

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);

        if (!$student_id) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error', 
                'message' => 'Invalid student ID'
            ]);
            exit;
        }

        $pdo->beginTransaction();

        try {
            $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE id = ?");
            $check_stmt->execute([$student_id]);
            
            if ($check_stmt->fetchColumn() == 0) {
                $pdo->rollBack();
                http_response_code(404);
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Student not found'
                ]);
                exit;
            }

            $stmt_parent = $pdo->prepare("DELETE FROM parents_contact WHERE student_id = ?");
            $stmt_parent->execute([$student_id]);

            $stmt_student = $pdo->prepare("DELETE FROM students WHERE id = ?");
            $stmt_student->execute([$student_id]);

            $stmt_attendance = $pdo->prepare("DELETE FROM student_attendance WHERE student_id = ?");
            $stmt_attendance->execute([$student_id]);

            $auditStmt = $pdo->prepare("INSERT INTO student_audit (student_id, operation) VALUES (:student_id, 'Removed')");
            $auditStmt->execute(['student_id' => $student_id]);

            $pdo->commit();

            http_response_code(200);
            echo json_encode([
                'status' => 'success', 
                'message' => "Student with ID $student_id removed successfully"
            ]);
            exit;

        } catch (PDOException $e) {
            $pdo->rollBack();

            http_response_code(500);
            echo json_encode([
                'status' => 'error', 
                'message' => 'Database error: ' . $e->getMessage()
            ]);
            exit;
        }
    } else {
        http_response_code(405);
        echo json_encode([
            'status' => 'error', 
            'message' => 'Method Not Allowed'
        ]);
        exit;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Connection failed: ' . $e->getMessage()
    ]);
    exit;
}
?>