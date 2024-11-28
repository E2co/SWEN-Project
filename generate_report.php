<?php
//include the database connection

$host = "localhost";
$user = "root";
$pass = "";
$db = "school_db";

$conn = new mysqli($host, $user, $pass, $db);

error_reporting(E_ALL);
ini_set('display_errors', 1);

//checking the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//get filter values from the GET request
$selectedGrade = isset($_GET['grade'])? $_GET['grade'] : '';
$selectedStudentId = isset($_GET['student_id'])? $_GET['student_id']: '';
$selectedMonth = isset($_GET['month'])? $_GET['month'] : '';//default to current month
$selectedYear = isset($_GET['year'])? $_GET['year'] :'';//default to current year

file_put_contents('debug.log', print_r($_GET, true), FILE_APPEND);

//SQL QUERY
$query = "
    SELECT s.id, s.name, sa.status, sa.date
    FROM students s
    LEFT JOIN student_attendance sa ON s.id = sa.student_id
    WHERE MONTH(sa.date) = ? AND YEAR(sa.date)= ?
    ";

//adding conditions of filtering by grade and the ID if provided
if ($selectedGrade){
    $query .= " 
    AND s.grade= ?";
}

if ($selectedStudentId){
    $query .= " 
    AND s.id = ?";
}
 
//prepare sql statement 
/*the bind_param() function binds the variables to the SQL Query, it takes types of variables as first argument 
and the actual variables as arguments listed after. It depends on whether the filters are provided.*/
$stmt = $conn->prepare($query);
if ($selectedGrade && $selectedStudentId){
    $stmt->bind_param('iiis', $selectedMonth, $selectedYear, $selectedGrade, $selectedStudentId);
}elseif($selectedGrade){
    $stmt->bind_param('iis', $selectedMonth, $selectedYear, $selectedGrade);
}elseif($selectedStudentId){
    $stmt->bind_param('iis', $selectedMonth, $selectedYear, $selectedStudentId);
}else {
    $stmt->bind_param('ii', $selectedMonth, $selectedYear);
}

//executing the query
$stmt->execute();
$attendanceData =[]; //array to store the attendance data
$result = $stmt->get_result();

//looping through the results to aggregate the attendance data
while ($row = $result->fetch_assoc()) 
{
    $studentId = $row['id'];

    //initialize the student data 
    if (!isset($attendanceData[$studentId])){
        $attendanceData[$studentId]=[
            'name'=>$row['name'],
            'totalp'=>0, //stores the total present
            'totala'=>0, //stores the total absent
            'perc'=>0,//stores attendance percentage
            'abs_dates'=>[]//stores absence data
        ];
    }

    //increment present or absent count based on the status
    if ($row['status']=='present'){
        $attendanceData[$studentId]['totalp']++;
    } else {
        $attendanceData[$studentId]['totala']++;
        $attendanceData[$studentId]['abs_dates'][]=$row['date'];
    }

}

    //now handling the attendance percentage 
    foreach($attendanceData as $studentId => &$summary){
        //calculate attendance percentage
        $totalDays = $summary['totalp']+ $summary['totala'];
        $summary['perc']=($totalDays > 0)? ($summary['totalp']/$totalDays)*100: 0;

        $absenceDates=$summary['abs_dates'];
        sort($absenceDates); //Ensures the absence dates are sorted
 
    }
    
    //closing connection
    $stmt->close();
    $conn->close();

    //return the report data as a JSON response 
    header('Content-Type: application/json');
    echo json_encode(array_values($attendanceData));
?>



