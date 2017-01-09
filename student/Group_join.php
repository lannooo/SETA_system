<?php
include "CommonData.php";

session_start();
if($_SESSION['userID'] == null)
    die("fail_no_user");
$sid = $_SESSION['userID'];
$gid = $_POST['gid'];
$clid = $_POST['clid'];
//$gid = 6;
//$clid = 1;
//$sid = 10001;

$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die("fail_no_connection"). mysqli_connect_error();
}
$mysqli->query('set names utf8');

$sql_group_select = "select gid from study_group";
$result_group_select = $mysqli->query($sql_group_select);
if($result_group_select == false)
    die("fail_select_error");
$result_group_number = mysqli_num_rows($result_group_select);
if($result_group_number == 0){
    die('fail_no_group');
}

$sql_attend_select = "select * from attend where clid = $clid and sid = $sid";
$result_attend_select = $mysqli->query($sql_attend_select);
if($result_attend_select == false)
    die("fail_select_error");
$result_attend_number = mysqli_num_rows($result_attend_select);
if($result_attend_number == 0){
    die("fail_select_error");
}
if($result_attend_select->fetch_array()[2] != null){
    die("fail_group_repeat");
}

$sql_attend_update = "update attend set gid = $gid where clid = $clid and sid = $sid";
$result_update = $mysqli->query($sql_attend_update);
if($result_update == false)
    die("fail_insert_error");

echo "success";
?>