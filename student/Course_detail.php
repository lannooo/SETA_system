<?php

include "CommonData.php";



session_start();

if (!isset($_SESSION['userID']))

    die("fail_no_user");

$coid = json_decode($GLOBALS['HTTP_RAW_POST_DATA'])->coid;

//$coid = 1;

$mysqli = new mysqli($servername, $username, $password, $dbname);

if (mysqli_connect_errno()){

    die('fail_no_connection');

}

$mysqli->query('set names utf8');



$sql_select = "select * from course where coid = '$coid'";

$result_select = $mysqli->query($sql_select);



if($result_select == false)

    die("fail_select_error");

if(1 != mysqli_num_rows($result_select))

    die("fail_error_result");



$row = $result_select->fetch_array();

$course = new course();

$course->coid=$row[0];$course->coname=$row[1];$course->textbook=$row[2];

$course->cocode=$row[3];$course->cotype=$row[4];$course->semster=$row[5];

$course->coname_en=$row[6];$course->college=$row[7];$course->credit=$row[8];

$course->week_learn_time=$row[9];$course->weight=$row[10];$course->pre_learning=$row[11];

$course->plan=$row[12];$course->background=$row[13];$course->assessment=$row[14];

$course->project_info=$row[15];



echo json_encode($course, JSON_UNESCAPED_UNICODE);

?>