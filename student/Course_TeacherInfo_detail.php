<?php
include "CommonData.php";

$raw = file_get_contents('php://input');
$json = json_decode($raw);

$tid = $json->tid;

$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die('fail_no_connection'). mysqli_connect_error();
}
$mysqli->query('set names utf8');

$sql_select = "select * from teacher where tid = ".$tid;
$result_select = $mysqli->query($sql_select);

if($result_select == false)
    die("fail_select_error");
if(1 != mysqli_num_rows($result_select))
    die("fail_no_teacher");

$row = $result_select->fetch_array();
$teacher = new teacher_info();
$teacher->tid=$row[0];$teacher->name=$row[5];$teacher->gender=$row[6];
$teacher->phone=$row[7];$teacher->email=$row[8];$teacher->college=$row[9];
$teacher->department=$row[10];$teacher->position=$row[11];$teacher->education=$row[12];
$teacher->direction=$row[13];$teacher->past_evaluation=$row[14];$teacher->desc_achieve=$row[15];
$teacher->desc_teach_type=$row[16];$teacher->desc_publish=$row[17];$teacher->desc_honor=$row[18];
$teacher->desc_more=$row[19];

echo json_encode($teacher);
?>