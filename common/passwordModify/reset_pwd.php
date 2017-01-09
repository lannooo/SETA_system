<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/23
 * Time: 上午2:35
 */
require_once "connect.php";

$raw = file_get_contents('php://input');
$json = json_decode($raw);

$username=$json->username;
$group=$json->group;
$newPassword=$json->newPassword;

$conn->query("SET SQL_SAFE_UPDATES = 0");

if($group=="teacher") {
    $sql = "UPDATE teacher SET password=? WHERE username=?";
}
else if($group=="student"){
    $sql = "UPDATE student SET password=? WHERE username=?";
}
else if($group=="ta_assist"){
    $sql = "UPDATE ta_assist SET password=? WHERE username=?";
}

$stmt = $conn->prepare($sql);

$stmt->bind_param('ss',$newPassword,$username);

$stmt->execute();


if($stmt->execute()){
    echo('{"code":200, "message":"修改成功。"}');
}
else {
    echo('{"code":1, "message":"修改失败。"}');
}

$conn->query("SET SQL_SAFE_UPDATES = 1");

$conn->close();

?>