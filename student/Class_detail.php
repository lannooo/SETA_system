<?php
include "CommonData.php";

session_start();
if($_SESSION['userID'] == null)
    die("fail_no_user");
$clid = $_GET['clid'];

$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die('fail_no_connection');
}
$mysqli->query('set names utf8');

$sql_select = "select * from course natural join class where class.clid = $clid";
$result_select = $mysqli->query($sql_select);

if($result_select == false)
    die("fail_select_error");
if(1 != mysqli_num_rows($result_select))
    die("fail_error_result");

$row = $result_select->fetch_array();
$class = new course_class();
$class->coid=$row[0];$class->coname=$row[1];$class->textbook=$row[2];
$class->cocode=$row[3];$class->cotype=$row[4];$class->semster=$row[5];
$class->coname_en=$row[6];$class->college=$row[7];$class->credit=$row[8];
$class->week_learn_time=$row[9];$class->weight=$row[10];$class->pre_learning=$row[11];
$class->plan=$row[12];$class->background=$row[13];$class->assessment=$row[14];
$class->project_info=$row[15];$class->clid=$row[16];$class->tid=$row[17];
$class->cltime=$row[18];$class->place=$row[19];$class->student_num=$row[20];

echo json_encode($class);
?>