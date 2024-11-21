<?php
session_start();

//Sample user data for testing
$users = [
    ['username' => 'teacher', 'password' => '123', 'role' => 'Teacher'],
    ['username' => 'principal', 'password' => 'principal123', 'role' => 'Principal'],
    ['username' => 'dean', 'password' => 'dean123', 'role' => 'Dean'],
    ['username' => '123', 'password' => '123', 'role' => 'Prefect'],
    ['username' => '123', 'password' => '123', 'role' => 'Security']
];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];

    foreach($users as $user){
        if($user['username'] === $username && $user['password'] === $password){
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
        }
    }

    header('Location: login.php?error=invalid_credentials');
    exit();
}
?>