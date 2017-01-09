<?php

require_once "connect.php";

$raw = file_get_contents('php://input');
$json = json_decode($raw);

$sid=$_COOKIE["sid"];
$username=$json->username;
$name=$json->name;
if($json->gender=="male"){
    $gender='m';
}
else{
    $gender='f';
}
$college=$json->college;
$major=$json->major;
$password=md5($json->password);
$phone=$json->phone;
$mail=$json->mail;


$sql = "INSERT INTO student_info VALUES (?,?,?,?,?,?,?);UPDATE student SET password=?, verified_mail=1, verified_phone=1 WHERE sid=?";

$stmt = $conn->prepare($sql);
//绑定参数
$stmt->bind_param('sssssssss',$sid,$name,$gender,$phone,$mail,$college,$major,$password,$sid);


if($stmt->execute()){
    echo('{"code":200, "message":"注册成功。"}');
}
else {
    echo('{"code":1, "message":"注册失败"}');
}


$conn->close();

?>