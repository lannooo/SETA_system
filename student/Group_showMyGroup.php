<?php
include "CommonData.php";

session_start();
$clid = $_GET['clid'];
$sid = $_SESSION['userID'];
if($_SESSION['userID'] == null)
    die("fail_no_user");
//$sid = 10002;
//$clid = 1;

$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die('fail_no_connection');
}
$mysqli->query('set names utf8');

$sql_attend_select = "select * from attend where sid = $sid and clid = $clid";
$result_attend_select = $mysqli->query($sql_attend_select);
if($result_attend_select == false)
    die("fail_select_error");
$result_attend_number = mysqli_num_rows($result_attend_select);
if($result_attend_number == 0){
    die("fail_select_error");
}
$row_select = $result_attend_select->fetch_array();
if($row_select[2] == null){
    die("fail_no_group");
}
$gid = $row_select[2];

$sql_group_select = "select * from study_group where gid = $gid";
$result_group_select = $mysqli->query($sql_group_select);
if($result_group_select == false)
    die("fail_select_error");
$result_group_number = mysqli_num_rows($result_group_select);
if($result_group_number == 0){
    die("fail_no_group");
}
$row_group = $result_group_select->fetch_array();
$group = new group();
$group->gid=$row_group[0];$group->clid=$row_group[1];$group->gname=$row_group[2];
echo json_encode($group);
echo "&&&";
$teamleader_id = $row_group[3];

$sql_attend = "select * from attend where gid = $gid";
$result_attend = $mysqli->query($sql_attend);
if($result_attend == false)
    die("fail_select_error");

while($row = $result_attend->fetch_array()){
    $group_member = new group_member();
    $group_member->sid = $row[0];
    if($group_member->sid == $teamleader_id)
        $group_member->type = "leader";
    else $group_member->type = "member";

    $sql_name = "select * from student_info natural join student where sid = $group_member->sid;";
    $result_name = $mysqli->query($sql_name);
    if($result_name == false)
        die("fail_select_error");
    if(mysqli_num_rows($result_name) == 0){
        die("fail_no_student");
    }
    $row_student = $result_name->fetch_row();
    $group_member->name=$row_student[1];$group_member->gender=$row_student[2];$group_member->phone=$row_student[3];
    $group_member->email=$row_student[4];$group_member->college=$row_student[5];$group_member->major=$row_student[6];
    $group_member->username=$row_student[7];

    $arr[] = $group_member;
}

echo json_encode($arr);
?>
