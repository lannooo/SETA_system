<?php
include "CommonData.php";

session_start();
if($_SESSION['userID'] == null)
    die("fail_no_user");
$clid = $_GET['clid'];
$sid = $_SESSION['userID'];
//$clid = 1;
//$sid = 10001;

$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die('fail_no_connection');
}
$mysqli->query('set names utf8');

$sql = "select * from homework where clid = $clid order by begin_t desc";

$result = $mysqli->query($sql);
if($result == false)
    die("fail_select_error");
if(mysqli_num_rows($result) == 0)
    die("fail_no_homework");
while($row = $result->fetch_array()){
    $hw = new student_homework();
    $hw->hid=$row[0];$hw->type=$row[2];$hw->name=$row[3];
    $hw->end_t=$row[4];$hw->hard_ddl=$row[5];$hw->begin_t=$row[6];
    $hw->punish_weight=$row[7];$hw->score_face=$row[8];$hw->score_weight=$row[9];

    $sql_result = "select * from hw_result where hid = $hw->hid and  sid = $sid";
    $result_hw_result = $mysqli->query($sql_result);
    if(0 == mysqli_num_rows($result_hw_result)){
        $hw->result = null;
    }
    else {
        $row_result = $result_hw_result->fetch_array();
        $hw_result = new student_homework_result();
        $hw_result->rid=$row_result[0];$hw_result->hid=$row_result[1];$hw_result->sid=$row_result[2];
        $hw_result->type=$row_result[3];$hw_result->ifcorrected=$row_result[5];$hw_result->score=$row_result[6];
        $hw->result = $hw_result;
    }
    $arr[] = $hw;
}
echo json_encode($arr);
?>
