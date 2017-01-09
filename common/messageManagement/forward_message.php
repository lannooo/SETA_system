<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/25
 * Time: 下午5:08
 */
require_once "connect.php";

session_start();

function switchToId($type){
    if($type=="student")
        return 0;
    else if($type=="teacher")
        return 1;
    else
        return 2;
}


$raw=file_get_contents("php://input");
$json=json_decode($raw);


$content=$json->content;
$title=$json->title;
$touser=$json->touser;
$toid="";
$fid=$json->fid;
$totype=switchToId($json->totype);
$fromtype=switchToId($_SESSION["userType"]);
$fromid=$_SESSION["userID"];
//$fromid=10001;
//$fromtype=0;
date_default_timezone_set('prc');
$mdate=date('y-m-d H:i:s',time());


if($totype==0) {
    $result = $conn->query("SELECT sid FROM student WHERE username='" . $touser."'");
    $row = $result->fetch_assoc();
    $toid = $row['sid'];
}
else if($totype==1){
    $result = $conn->query("SELECT tid FROM teacher WHERE username='" . $touser."'");
    $row = $result->fetch_assoc();
    $toid = $row['tid'];
}
else{
    $result = $conn->query("SELECT taid FROM ta_assist WHERE username=" . $touser);
    $row = $result->fetch_assoc();
    $toid = $row['taid'];
}


if($fid==-1){
    $sql = "INSERT INTO message (`fromid`,`toid`,`fromtype`,`totype`,`mdate`, `content`, `title`, `ifread`)
	VALUES (?, ?, ?, ?, ?, ?, ?, b'0')";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("iiiisss",$fromid,$toid,$fromtype,$totype,$mdate,$content,$title);
}
else {
    $sql = "INSERT INTO message (`refer_mid`,`fromid`,`toid`,`fromtype`,`totype`,`mdate`, `content`, `title`, `ifread`)
	VALUES (?, ?, ?, ?, ?, ?, ?, ?, b'0')";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("iiiiisss",$fid,$fromid,$toid,$fromtype,$totype,$mdate,$content,$title);
}

if($stmt->execute()){
    echo('{"code":200, "result":"发送成功！"}');
}
else {
    echo('{"code":1, "result":"发送失败！"}');
}

?>