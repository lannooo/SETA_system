<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/20
 * Time: 下午10:13
 */
require_once "connect.php";

$raw = file_get_contents('php://input');
$json = json_decode($raw);

$username = $json->username;

$sql = "SELECT sid,password FROM student WHERE username=?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$username);
$stmt->bind_result($s_id,$t_pwd);
$stmt->execute();

if($stmt->fetch()){
    setcookie("sid","$s_id",time()+3600);
    echo('{"code":200, "message":"用户名合法。"}');
}
else {
    echo('{"code":1, "message":"不存在该用户名。"}');
}

$conn->close();

?>