<?php 

$conn = new mysqli('localhost', 'root','','school_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn -> connect_error);
}


if (isset(($_POST['id']))) {
    $student_id = $_POST['id'];

    $query = "SELECT * FROM students WHERE id =?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo "<h3>Student Information</h3>";

        echo '<label for="name">Name:</label>';
        echo '<input type="text" id="name" name="name" value="' . htmlspecialchars($student['name']) .'"required><br>';
        
        echo '<label for="grade">Grade:</label>';
        echo '<input type="number" id="grade" name="grade" value="' . htmlspecialchars($student['grade']) .'"required><br>';

        $parent_query = "SELECT * FROM parents_contact WHERE student_id = ?";
        $parent_stmt = $conn->prepare($parent_query);
        $parent_stmt->bind_param("i", $student_id);
        $parent_stmt->execute();
        $parent_result = $parent_stmt->get_result();
    
        if ($parent_result->num_rows > 0) {
            $parent = $parent_result->fetch_assoc();
            echo "<h3>Parent Information</h3>";

            echo '<label for="parent_name">Parent Name:</label>';
            echo '<input type="text" id="parent_name" name="parent_name" value="' . htmlspecialchars($parent['parent name']) .'"required><br>';

            echo '<label for="email">Email:</label>';
            echo '<input type="email" id="email" name="email" value="' . htmlspecialchars($parent['email']) .'"required><br>';

            echo '<label for="phone">Phone:</label>';
            echo '<input type="text" id="phone" name="phone" value="' . htmlspecialchars($parent['telephone number']) .'"required><br>';
    
        } else {
            echo "<p>No parent contact information found.</p>";
        }
    } else {
        echo "<p> No student found with that ID.</p>";
    }
    
    $conn->close();

}
?>