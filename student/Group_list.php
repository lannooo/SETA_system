<?php
include "CommonData.php";

session_start();
if($_SESSION['userID'] == null)
    die("fail_no_user");
$clid = $_GET['clid'];
//$clid = 1;
$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die('fail_no_connection'). mysqli_connect_error();
}
$mysqli->query('set names utf8');

$sql_group = "select * from study_group where clid = $clid";

$result_group = $mysqli->query($sql_group);
if($result_group == false)
    die("fail_select_error");
if(mysqli_num_rows($result_group) == 0)
    die("fail_no_group");

while($row = $result_group->fetch_array()){
    $group = new group();
    $group->gid=$row[0];$group->clid=$row[1];
    $group->gname=$row[2];$group->teamleader_id=$row[3];

    $sql_leadername = "select * from student_info natural join student where sid = $group->teamleader_id";
    $result_leadername = $mysqli->query($sql_leadername);
    if($result_leadername == false)
        die("fail_select_error");
    if(mysqli_num_rows($result_leadername) == 0)
        die("fail_no_student");

    $row_leader = $result_leadername->fetch_array();
    $group->leader_name = $row_leader[1];$group->leader_username=$row_leader[7];
    $sql_count = "select * from attend where gid = $group->gid";
    $result_member = $mysqli->query($sql_count);
    $group->count = mysqli_num_rows($result_member);

    $arr[] = $group;
}
echo json_encode($arr);
?>