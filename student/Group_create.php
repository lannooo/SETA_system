<?php
include "CommonData.php";

session_start();
if($_SESSION['userID'] == null)
    die("fail_no_user");
$teamleader_id = $_SESSION['userID'];
$clid = $_POST['clid'];
$gname = $_POST['gname'];
//$clid = 1;
//$gname = "groupA";
//$teamleader_id = 10002;

if($gname == null || $gname == "")
    die("fail_no_gname");

$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die("fail_no_connection"). mysqli_connect_error();
}
$mysqli->query('set names utf8');

$sql_attend_select = "select gid from attend where clid = $clid and sid = $teamleader_id";
$result_attend_select = $mysqli->query($sql_attend_select);
if($result_attend_select == false)
    die("fail_select_error");
$result_attend_number = mysqli_num_rows($result_attend_select);
if($result_attend_number == 0){
    die("fail_select_error");
}
if($result_attend_select->fetch_array()[0] != null)
    die("fail_sid_repeat");

$sql_group_select = "select gid from study_group where clid = $clid and teamleader_id = $teamleader_id";
$result_group_select = $mysqli->query($sql_group_select);
if($result_group_select == false)
    die("fail_select_error");
$result_group_number = mysqli_num_rows($result_group_select);
if($result_group_number != 0){
    die("fail_group_repeat");
}

$sql_group_insert = "insert into study_group(clid, gname, teamleader_id) values($clid, '$gname', $teamleader_id)";
$result_group_insert = $mysqli->query($sql_group_insert);
if($result_group_insert == false)
    die("fail_insert_error");

$result_group_select = $mysqli->query($sql_group_select);
if($result_group_select == false)
    die("fail_select_error");
$gid = $result_group_select->fetch_array()[0];

$sql_attend_update = "update attend set gid = $gid where sid = $teamleader_id and clid = $clid";
$result_attend_update = $mysqli->query($sql_attend_update);
if($result_attend_update == false)
    die("fail_update_error");
echo "success";
?>
