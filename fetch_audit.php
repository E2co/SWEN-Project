<?php

header('Content-Type: application/json');

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

$query = "SELECT * FROM student_audit ORDER BY changed_at DESC";
$stmt = $pdo->query($query);
$auditLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($auditLogs);
