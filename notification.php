<?php
// Database connection details
$servername = "localhost"; // Replace with your MySQL server address if different
$username = "root";        // Replace with your MySQL username (default is root)
$password = "";            // Replace with your MySQL password (default is empty for XAMPP)
$dbname = "school_db";     // The name of the database you want to use

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query the student_attendance table
$sql = "SELECT * FROM student_attendance"; // Replace with your desired query
$result = $conn->query($sql);

// Check if there are results and display them
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        // Correctly access the fields that exist in the table
        echo "ID: " . $row["id"] . " - Status: " . $row["status"] . " - Date: " . $row["date"] . "<br>";
    }
} else {
    echo "No results found.";
}

// Close the database connection
$conn->close();
?>
