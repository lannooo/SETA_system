<?php
include "CommonData.php";

session_start();
$sid = $_SESSION['userID'];

$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die('fail_no_connection');
}
$mysqli->query('set names utf8');

$sql_select = "select name from student_info where sid = $sid";
$result_select = $mysqli->query($sql_select);
if($result_select == false)
    die("fail_select_error");
if(mysqli_num_rows($result_select) == 0)
    die("fail_no_student");
echo $result_select->fetch_array()[0];

echo "&&&";

$sql_select = "select * from (class natural join course inner join teacher on class.tid = teacher.tid) inner join attend on class.clid = attend.clid where attend.sid = $sid";

$result_select = $mysqli->query($sql_select);
if($result_select == false)
    die("fail_select_error");
if(mysqli_num_rows($result_select) == 0)
    die("fail_no_course");

while($row_select = $result_select->fetch_array()){
    $course_class = new course_class();
    $course_class->coid=$row_select[0];$course_class->clid=$row_select[1];$course_class->tid=$row_select[2];
    $course_class->cltime=$row_select[3];$course_class->place=$row_select[4];$course_class->student_num=$row_select[5];
    $course_class->coname=$row_select[6];$course_class->cotype=$row_select[9];$course_class->college=$row_select[12];
    $course_class->plan=$row_select[17];$course_class->tname=$row_select[26];
    $arr_course[] = $course_class;
}
echo json_encode($arr_course);
?>