<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/26
 * Time: 上午2:21
 */
require_once "connect.php";

$taid=$_COOKIE["userID"];

$sql = "SELECT username FROM ta_assist WHERE taid=?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$taid);
$stmt->bind_result($username);
$stmt->execute();

if(!$stmt->fetch()){
    echo('{"code":1, "message":"用户ID错误。"}');
}


$stmt->close();

$sql = "SELECT name,gender,college,major,phone, email FROM ta_info WHERE sid=?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$sid);
$stmt->bind_result($name,$gender,$college,$major,$phone,$email);
$stmt->execute();

if($stmt->fetch()){
    if($gender=='M'){
        $gender="male";
    }
    else {
        $gender="female";
    }
    echo('{"code":200, "username":"'.$username.'","name":"'.$name.'", "college":"'.$college.'", "major":"'.$major.'", "phone":"'.$phone.'", "mail":"'.$email.'"}');
}
else {
    echo('{"code":1, "message":"不存在该用户名。"}');
}


$conn->close();


?>