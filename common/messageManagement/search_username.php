<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/25
 * Time: 下午8:53
 */
require_once "connect.php";


$raw=file_get_contents('php://input');
$json=json_decode($raw);

$toid=$json->toid;
$type=$json->totype;

if($type==0)
    $sql = "SELECT username FROM student WHERE sid=?";
else if($type==1)
    $sql = "SELECT username FROM teacher WHERE tid=?";
else
    $sql = "SELECT username FROM ta_assist WHERE taid=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$toid);
$stmt->bind_result($username);
$stmt->execute();

if($stmt->fetch()){
    echo('{"code":200, "username":"'.$username.'"}');
}
else {
    echo('{"code":1, "username":"错误的用户id"}');
}

?>