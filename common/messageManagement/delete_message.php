<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/26
 * Time: 上午1:42
 */
require_once "connect.php";

$raw = file_get_contents('php://input');
$json = json_decode($raw);

$mid=$json->mid;

//$conn->query("SET SQL_SAFE_UPDATES = 0");

$sql = "DELETE FROM message WHERE mid=?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$mid);

if($stmt->execute()){
    echo('{"code":200, "message":"删除成功。"}');
}
else {
    echo('{"code":1, "message":"删除失败。"}');
}


//$conn->query("SET SQL_SAFE_UPDATES = 1");

$conn->close();
?>