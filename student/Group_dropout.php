<?php
include "CommonData.php";

session_start();
if($_SESSION['userID'] == null)
    die("fail_no_user");
$sid = $_SESSION['userID'];
$gid = $_POST['gid'];
$clid = $_POST['clid'];
//$type = $_POST['type'];
//$gid = 6;
//$sid = 10002;
//$clid = 1;
//$type = "leader";

$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die("fail_no_connection").mysqli_connect_error();
}
$mysqli->query('set names utf8');

$sql_group_select = "select * from study_group where gid = $gid";
$result_group_select = $mysqli->query($sql_group_select);
$result_group_number = mysqli_num_rows($result_group_select);
if($result_group_select == false)
    die('fail_select_error');
if($result_group_number == 0){
    die('fail_no_group');
}
if($result_group_select->fetch_row()[3] == $sid)
    $type = "leader";
else $type = "member";

$sql_attend_select = "select * from attend where gid = $gid";
$result_attend_select = $mysqli->query($sql_attend_select);
$result_attend_number = mysqli_num_rows($result_attend_select);
if($result_attend_select == false)
    die('fail_select_error');
if($result_attend_number == 0){
    die("fail_no_attend");
}
while($row = $result_attend_select->fetch_array()){
    $arr_sid[] = $row[0];
}

if($type == "member") {
    $sql_attend_update = "update attend set gid = null where clid = $clid and sid = $sid";
    $result_update = $mysqli->query($sql_attend_update);
    if($result_update == false)
        die('fail_delete_error');
}
else {
    foreach($arr_sid as $member_sid){
        $sql_attend_update = "update attend set gid = null where clid = $clid and sid = $member_sid";
        $result_update = $mysqli->query($sql_attend_update);
        if($result_update == false)
            die('fail_delete_error');
    }
    $sql_group_delete = "delete from study_group where gid = $gid";
    $result_delete = $mysqli->query($sql_group_delete);
    if($result_delete == false)
        die('fail_delete_error');
}

echo "success";
?>