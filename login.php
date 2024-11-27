<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "school_db";

$conn = new mysqli('localhost', 'root', '', 'school_db');

if($conn -> connect_error){
    die("Connection failed: " . $conn -> connect_error);
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $conn -> real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";

    $stmt = $conn->prepare($sql);
    $stmt -> bind_param("s", $username);
    $stmt -> execute();  
    $result = $stmt -> get_result();

    if($result -> num_rows === 1){
        $user = $result -> fetch_assoc();
    
        if($password === $user['password']){
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $username;

            $redirectUrl = match($user['role']){
                'Principal' => 'Principal_dashboard.html',
                'Dean' => 'Dean_dashboard.html',
                'Teacher' => 'Teacher_dashboard.html',
                'Prefect' =>'Presec_dashboard.html',
                'Security' => 'Presec_dashboard.html',
                default => 'login.php'
            };

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'redirectUrl' => $redirectUrl]);
            exit();
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
            exit();
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit();
    }

    $stmt -> close();
    $conn -> close();
}
?>