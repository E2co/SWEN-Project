<?php
//connecting to the database
$servername= "localhost";
$username= "root";
$password= "";
$dbname= "school_db";
$conn= new mysqli($servername,$username,$password,$dbname);

if($conn->connect_error){
    die("The Connection Failed: ". $conn->connect_error);
}

//retrieve data from the AJAX request
$studentID=isset($_POST['student_id'])?$_POST['student_id']:'';
$grade=isset($_POST['grade'])?$_POST['grade']:'';
$startDate=isset($_POST['start_date'])?$_POST['start_date']:'';
$endDate=isset($_POST['end_date'])?$_POST['end_date']:'';
$query="SELECT
    s.id AS student_id, s.grade, sa.status, sa.date FROM student_attendance sa 
    INNER JOIN students s ON sa.student_id=s.id
    WHERE 1=1";

if(!empty($studentID)){
    $query ="AND sa.student_id ='".$conn->real_escape_string($studentID)."'";
}

if(!empty($grade)){
    $query ="AND s.grade ='".$conn->real_escape_string($grade)."'";
}

if(!empty($startDate)){
    $query ="AND sa.date >='".$conn->real_escape_string($startDate)."'";
}

if(!empty($endDate)){
    $query ="AND sa.date <='".$conn->real_escape_string($endDate)."'";
}

//executing the query
$result=$conn->query($query);
//checking returned data
$attendanceData=[];
if($result->num_rows>0){
    while($row=$result->fetch_assoc()){
        $attendanceData[]=$row;
    }
}

echo json_encode($attendanceData);
$conn->close();
?>