<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/25
 * Time: 下午11:40
 */

require_once "connect.php";

function switchToId($type){
    if($type=="student")
        return 0;
    else if($type=="teacher")
        return 1;
    else
        return 2;
}


$raw=file_get_contents('php://input');
$json=json_decode($raw);

$touser=$json->touser;
$type=switchToId($json->totype);

if($type==0) {
    $result=$conn->query("SELECT sid FROM student WHERE username='".$touser."'");
    $row = $result->fetch_assoc();
    $toid = $row['sid'];
    $sql = "SELECT name FROM student_info WHERE sid=?";
}
else if($type==1) {
    $result=$conn->query("SELECT tid FROM teacher WHERE username='" . $touser . "'");
    $row = $result->fetch_assoc();
    $toid = $row['tid'];
    $sql = "SELECT name FROM teacher WHERE tid=?";
}
else{
    $result=$conn->query("SELECT taid FROM ta_assist WHERE username='".$touser."'");
    $row = $result->fetch_assoc();
    $toid = $row['taid'];
    $sql = "SELECT name FROM ta_info WHERE taid=?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$toid);
$stmt->bind_result($name);
$stmt->execute();

if($stmt->fetch()){
    echo('{"code":200, "name":"'.$name.'"}');
}
else {
    echo('{"code":1, "name":""}');
}

?>