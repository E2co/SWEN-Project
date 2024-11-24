<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'school_db');

if($conn -> connect_error){
    die("Connection failed: " . $conn -> connect_error);
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $conn -> real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn -> query($sql);

    if($result -> num_rows === 1){
        $user = $result -> fetch_assoc();
    
        //if(password_verify($password, $user['password'])){
            if($password === $user['password']){
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $username;

            switch($user['role']){
                case 'Principal':
                    header('Location: Principal_dashboard.html');
                    exit();
                case 'Dean':
                    header('Location: Dean_dashboard.html');
                    exit();
                case 'Teacher':
                    header('Location: Teacher_dashboard.html');
                    exit();
                case 'Prefect':
                    header('Location: Presec_dashboard.html');
                    exit();
                case 'Security':
                    header('Location: Presec_dashboard.html');
                    exit();
            }
        } else {
            header('Location: login.php?error=invalid_credentials');
            exit();
        }
    } //else {
        //header('Location: login.php?error=user_not_found');
        //exit();
    //}

    $conn -> close();
}
?>