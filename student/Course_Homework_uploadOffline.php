<?php
include "CommonData.php";

session_start();
if($_SESSION['userID'] == null)
    die("fail_no_user");
$sid = $_SESSION['userID'];
$hid =  $_POST["hid"];
$type_int = $_POST["type_int"];

$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die('fail_no_connection').mysqli_connect_error();
}

$sql = "select * from homework where hid = $hid";
$result = $mysqli->query($sql);
if($result == false)
    die("fail_select_error");
if(mysqli_num_rows($result) == 0)
    die("fail_no_homework");
$row = $result->fetch_array();
$hard_ddl=$row[5];

$curr_time=date("y-m-d h:i:s");
if(strtotime($curr_time)>=strtotime($hard_ddl)){
    die("fail_overtime");
}

if ($type_int == 2){
    $sql_group = "select * from study_group inner join attend on study_group.teamleader_id = attend.sid and study_group.clid = attend.clid where sid = $sid";
    $sql_group_result = $mysqli->query($sql_group);
    if(1 != mysqli_num_rows($sql_group_result)){
        die("fail_not_groupleader");
    }
}

$sql_select = "select * from hw_result where hid = $hid and sid = $sid";
$result_select = $mysqli->query($sql_select);
if($result_select == false)
    die("fail_select_error");
if(0 == mysqli_num_rows($result_select)){
    $flag = false;
    $answer_url = $sid."_".$hid."_".$_FILES['file']['name'];
}
else {
    $flag = true;
    $row_select = $result_select->fetch_array();
    if($row_select[4] == 1)
        die("fail_isCorrected");
    $answer_url = $row_select[8];
}

$fileName = $_FILES['file']['name'];
if(!move_uploaded_file($_FILES['file']['tmp_name'], "../upload/hw_stu/".$answer_url)){
    die("fail_cannot_save");
}

if($flag == false) {
    if($type_int == 0)
        $sql_insert = "insert into hw_result(hid, sid, type, uploadtime, url) values($hid, $sid, 'F', now(), '$answer_url')";
    else $sql_insert = "insert into hw_result(hid, sid, type, uploadtime, url) values($hid, $sid, 'G', now(), '$answer_url')";
    $result_insert = $mysqli->query($sql_insert);
    if($result_insert == false)
        die("fail_insert_error");
}
else {
    $sql_update = "update hw_result set uploadtime = now()";
    $result_update = $mysqli->query($sql_update);
    if($result_update == false)
        die("fail_update_error");
}

echo "success";
?>