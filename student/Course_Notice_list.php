<?php
include "CommonData.php";

session_start();
if($_SESSION['userID'] == null)
    die("fail_no_user");
$tid = $_GET['tid'];
$coid = $_GET['coid'];
//$tid = 1;
//$coid = 1;

$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die('fail_no_connection'). mysqli_connect_error();
}
$mysqli->query('set names utf8');

$sql_select = "select * from anouncement where tid = $tid and coid = $coid order by adate desc";
$result_select = $mysqli->query($sql_select);
if($result_select == false)
    die("fail_select_error");
if(mysqli_num_rows($result_select) == 0)
    die("fail_no_notice");

while($row_select = $result_select->fetch_array()){
    $announcement = new announcement();
    $announcement->anid=$row_select[0];$announcement->tid=$row_select[1];$announcement->coid=$row_select[2];
    $announcement->adate=$row_select[3];$announcement->title=$row_select[4];$announcement->content=$row_select[5];
    $announcement->type=$row_select[6];$announcement->read_count=$row_select[7];

    $arr_announcement[] = $announcement;
}
echo json_encode($arr_announcement);
?>