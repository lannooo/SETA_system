<?php


session_start();

require_once "connect.php";

$raw = file_get_contents('php://input');
$json = json_decode($raw);

$sid=$_SESSION["userID"];
$name=$json->name;
if($json->gender=="male"){
    $gender='M';
}
else{
    $gender='F';
}
$college=$json->college;
$major=$json->major;
$phone=$json->phone;
$mail=$json->mail;


$sql = "UPDATE student_info set name=?,gender=?,college=?,major=?,phone=?,email=? WHERE sid=?";
$stmt = $conn->prepare($sql);
//绑定参数
$stmt->bind_param('ssssssi',$name,$gender,$college,$major,$phone,$mail,$sid);


if($stmt->execute()){
    echo('{"code":200, "message":"修改成功。"}');
}
else {
    echo('{"code":1, "message":"修改失败"}');
}


$conn->close();

?>