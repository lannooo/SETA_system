<?php
include "../../student/CommonData.php";

session_start();
if($_SESSION['userID'] == null)
    die("fail_no_user");

$mid = $_GET["mid"];
$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die('fail_no_connection');
}
$mysqli->query('set names utf8');

$sql_select = "select * from material where mid = $mid";
$result_select = $mysqli->query($sql_select);
if ($result_select == false)
    die("fail_select_error");
if (mysqli_num_rows($result_select) == 0)
    die("fail_no_material");
$row_select = $result_select->fetch_array();
$url = $row_select[6];$name = $row_select[3];

$file = "../../upload/material/".$url;
$newfile = "../../upload/material/".$name;

copy($file, $newfile);

header('Content-type: application/pdf');
header('filename='.$newfile);
readfile($newfile);

