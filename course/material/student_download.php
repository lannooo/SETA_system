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

$sql_update = "update material set download = $row_select[9] + 1";
$result_update = $mysqli->query($sql_update);

$filename = "../../upload/material/".$url;
if (!file_exists($filename))
    die("Error when creating file, maybe no file selected...");
$file = fopen($filename, "r");
Header("Content-type: application/octet-stream");
Header("Accpet-Ranges: bytes");
Header("Accept-Length: " . filesize($filename));
Header("Content-Disposition: attachment; filename=".$name);
$buffer = 2048;
while (!feof($file)) {
    $file_data=fread($file, $buffer);
    echo $file_data;
}
fclose($file);
?>