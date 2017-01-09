<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/26
 * Time: 上午2:21
 */

session_start();
require_once "connect.php";

$tid=$_SESSION["userID"];
$tid=1;


$sql = "SELECT username,name,gender,college,department,phone,email,position,education,direction,past_evaluation,desc_achive,desc_teach_type,desc_publish,desc_honor,desc_more FROM teacher WHERE tid=?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$tid);
$stmt->bind_result($username,$name,$gender,$college,$department,$phone,$email,$position,$education,$direction,$evalution,$achive,$teach_type,$publish,$honor,$more);
$stmt->execute();

if($stmt->fetch()){
    if($gender=='M'){
        $gender="male";
    }
    else {
        $gender="female";
    }
    echo('{"code":200, "username":"'.$username.'","name":"'.$name.'", "college":"'.$college.'", "department":"'.$department.'", "phone":"'.$phone.'", "mail":"'.$email.'","position":"'.$position.'","education":"'.$education.'","direction":"'.$direction.'","evalution":"'.$evalution.'","achive":"'.$achive.'","teach_type":"'.$teach_type.'","publish":"'.$publish.'","honor":"'.$honor.'","more":"'.$more.'"}');
}
else {
    echo('{"code":1, "message":"不存在该用户名。"}');
}


$conn->close();


?>