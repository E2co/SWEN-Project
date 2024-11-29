<?php
// Database connection parameters
$host = 'localhost';
$dbname = 'school_db';
$username = 'root';  // Default XAMPP username
$password = '';      // Default XAMPP password

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate and sanitize input
        $student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
        $st_first_name = filter_input(INPUT_POST, 'st_first_name', FILTER_SANITIZE_STRING);
        $st_last_name = filter_input(INPUT_POST, 'st_last_name', FILTER_SANITIZE_STRING);
        $grade = filter_input(INPUT_POST, 'grade', FILTER_VALIDATE_INT);
        
        // Parent information
        $parent_first_name = filter_input(INPUT_POST, 'parent_first_name', FILTER_SANITIZE_STRING);
        $parent_last_name = filter_input(INPUT_POST, 'parent_last_name', FILTER_SANITIZE_STRING);
        $parent_email = filter_input(INPUT_POST, 'parent_email', FILTER_VALIDATE_EMAIL);
        $parent_telephone = filter_input(INPUT_POST, 'parent_telephone', FILTER_SANITIZE_STRING);

        // Validate inputs
        if ($student_id && $st_first_name && $st_last_name && $grade 
            && $parent_first_name && $parent_last_name 
            && $parent_email && $parent_telephone) {
            
            // Start a transaction
            $pdo->beginTransaction();

            try {
                // Prepare SQL for students table
                $stmt_student = $pdo->prepare("INSERT INTO students (id, name, grade) VALUES (?, ?, ?)");
                $stmt_student->execute([$student_id, "$st_first_name $st_last_name", $grade]);

                // Prepare SQL for parents_contact table
                $stmt_parent = $pdo->prepare("INSERT INTO parents_contact (student_id, `parent name`, email, `telephone number`) VALUES (?, ?, ?, ?)");
                $stmt_parent->execute([$student_id, "$parent_first_name $parent_last_name", $parent_email, $parent_telephone]);

                $auditStmt = $pdo->prepare("INSERT INTO student_audit (student_id, operation) VALUES (:student_id, 'Added')");
                $auditStmt->execute(['student_id' => $student_id]);

                // Commit the transaction
                $pdo->commit();

                // Return success response
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Student added successfully']);
                exit;

            } catch (PDOException $e) {
                // Rollback the transaction
                $pdo->rollBack();

                // Return error response
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
                exit;
            }
        } else {
            // Invalid input
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
            exit;
        }
    } else {
        // Not a POST request
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
        exit;
    }
} catch (PDOException $e) {
    // Connection error
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

?>