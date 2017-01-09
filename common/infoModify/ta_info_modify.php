<?php

session_start();
require_once "connect.php";

$raw = file_get_contents('php://input');
$json = json_decode($raw);

$taid=$_SESSION["userID"];
$username=$json->username;
$name=$json->name;
if($json->gender=="male"){
    $gender='M';
}
else{
    $gender='F';
}
$college=$json->college;
$major=$json->major;
$password=$json->password;
$phone=$json->phone;
$mail=$json->mail;


$sql = "UPDATE ta_info set name=?,gender=?,college=?,major=?,phone=?,email=? WHERE taid=?";

$stmt = $conn->prepare($sql);
//绑定参数
$stmt->bind_param('ssssssi',$name,$gender,$college,$major,$phone,$mail,$taid);


if($stmt->execute()){
    echo('{"code":200, "message":"修改成功。"}');
}
else {
    echo('{"code":1, "message":"修改失败"}');
}


$conn->close();

?>