<?php
//include the database connection

$host = "localhost";
$user = "root";
$pass = "";
$db = "school_db";

$conn = new mysqli($host, $user, $pass, $db);

error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve filter values from the AJAX POST request
$studentID = isset($_POST['student_id']) ? $_POST['student_id'] : '';
$grade = isset($_POST['grade']) ? $_POST['grade'] : '';
$month = isset($_POST['month']) ? $_POST['month'] : date('m');  
$year = isset($_POST['year']) ? $_POST['year'] : date('Y');  

error_log("Filters - Student ID: " . $studentID . ", Grade: " . $grade . ", Month: " . $month . ", Year: " . $year);

$query = "
    SELECT s.id AS student_id, s.name, s.grade, sa.status, sa.date
    FROM student_attendance sa
    INNER JOIN students s ON sa.student_id = s.id
    WHERE MONTH(sa.date) = ? AND YEAR(sa.date) = ?
";

// Add conditions based on provided filters
if (!empty($studentID)) {
    $query .= " AND sa.student_id = ?";
}

if (!empty($grade)) {
    $query .= " AND s.grade = ?";
}

// Prepare SQL statement
$stmt = $conn->prepare($query);

// Bind parameters based on the filters
if (!empty($studentID) && !empty($grade)) {
    $stmt->bind_param('iiis', $month, $year, $grade, $studentID);
} elseif (!empty($studentID)) {
    $stmt->bind_param('iis', $month, $year, $studentID);
} elseif (!empty($grade)) {
    $stmt->bind_param('ii', $month, $year, $grade);
} else {
    $stmt->bind_param('ii', $month, $year);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Initialize an array to store attendance data
$attendanceData = [];
if ($result->num_rows > 0) {
    // Loop through the results and process the attendance data
    while ($row = $result->fetch_assoc()) {
        $studentId = $row['student_id'];

        // Initialize student data if it doesn't exist
        if (!isset($attendanceData[$studentId])) {
            $attendanceData[$studentId] = [
                'name' => $row['name'],
                'grade' => $row['grade'],
                'totalPresent' => 0,
                'totalAbsent' => 0,
                'totalLate' => 0,
                'attendancePercentage' => 0,
                'absenceDates' => [],
                'lateDates' => []
            ];
        }

        // Increment counters for present, absent, or late
        if ($row['status'] == 'present') {
            $attendanceData[$studentId]['totalPresent']++;
        } elseif ($row['status'] == 'absent') {
            $attendanceData[$studentId]['totalAbsent']++;
            $attendanceData[$studentId]['absenceDates'][] = $row['date'];
        } elseif ($row['status'] == 'late') {
            $attendanceData[$studentId]['totalLate']++;
            $attendanceData[$studentId]['lateDates'][] = $row['date'];
        }
    }
} else {
    error_log("No attendance data found for the provided filters.");
}
 
// Calculate the attendance percentage for each student
foreach ($attendanceData as $studentId => &$summary) {
    $totalDays = $summary['totalPresent'] + $summary['totalAbsent'] + $summary['totalLate'];
    $summary['attendancePercentage'] = ($totalDays > 0) ? ($summary['totalPresent'] / $totalDays) * 100 : 0;

    // Sort absence and late dates for display purposes (optional)
    sort($summary['absenceDates']);
    sort($summary['lateDates']);
}

// Close the database connection
$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode(array_values($attendanceData));
?>


