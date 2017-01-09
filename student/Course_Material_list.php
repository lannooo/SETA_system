<?php
include "CommonData.php";

session_start();
if($_SESSION['userID'] == null)
    die("fail_no_user");

$tid = $_GET['tid'];
$coid = $_GET['coid'];
$curr_dir = $_GET['dir'];

$mysqli = new mysqli($servername, $username, $password, $dbname);
if (mysqli_connect_errno()){
    die('fail_no_connection');
}
$mysqli->query('set names utf8');

if($curr_dir == "root"){
    $sql_select = "select * from material where tid = $tid and coid = $coid and isnull(father)";
    $result_select = $mysqli->query($sql_select);
    if ($result_select == false)
        die("fail_select_error");
    if (mysqli_num_rows($result_select) == 0)
        die("fail_no_material");

    $row_select = $result_select->fetch_array();
    $dir = $row_select[0];
    $path = new path();
    $path->mid = $row_select[0];$path->name = $row_select[3];
    $pathArr[] = $path;
}
else {
    $dir = $curr_dir;
    $sql_select = "select * from material where mid = $dir";
    $result_select = $mysqli->query($sql_select);
    if ($result_select == false)
        die("fail_select_error");
    if (mysqli_num_rows($result_select) == 0)
        die("fail_no_material");
    $row_select = $result_select->fetch_array();
    $path = new path();
    $path->mid = $row_select[0];$path->name = $row_select[3];
    $pathArr[] = $path;
    while ($row_select[1] != null){
        $sql_select = "select * from material where mid = $row_select[1]";
        $result_select = $mysqli->query($sql_select);
        if ($result_select == false)
            die("fail_select_error");
        if (mysqli_num_rows($result_select) == 0)
            die("fail_no_material");
        $row_select = $result_select->fetch_array();

        $path = new path();
        $path->mid = $row_select[0];$path->name = $row_select[3];
        $pathArr[] = $path;
    }
}

echo json_encode($pathArr);
echo "&&&";

$sql_select = "select * from material where father = $dir";

$result_select = $mysqli->query($sql_select);
if ($result_select == false)
    die("fail_select_error");
if (mysqli_num_rows($result_select) == 0)
    die("fail_no_material");

while ($row_select = $result_select->fetch_array()) {
    $material = new material();
    $material->mid = $row_select[0];$material->father = $row_select[1];$material->type = $row_select[2];
    $material->name = $row_select[3];$material->size = $row_select[4];$material->uploadtime = $row_select[5];
    $material->url = $row_select[6];$material->tid = $row_select[7];$material->coid = $row_select[8];
    $material->download = $row_select[9];

    $arr_material[] = $material;
}
echo json_encode($arr_material);

?>