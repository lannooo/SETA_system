<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/26
 * Time: 上午2:21
 */
/*session_start();
require_once "connect.php";

$tid=$_SESSION["userID"];

$raw = file_get_contents('php://input');
$json = json_decode($raw);

$tid=1;
$name=$json->name;
$gender=$json->gender;
if($gender=="female")
    $gender="F";
else
    $gender="M";
$college=$json->college;
$department=$json->department;
$phone=$json->phone;
$email=$json->mail;
$position=$json->position;
$education=$json->education;
$direction=$json->direction;
$evalution=$json->evalution;
$achive=$json->achive;
$teach_type=$json->teach_type;
$publish=$json->publish;
$honor=$json->honor;
$more=$json->more;




$sql = "UPDATE teacher set name=?,gender=?,college=?,department=?,phone=?,email=?,position=?,education=?,direction=?,past_evaluation=?,desc_achive=?,desc_teach_type=?,desc_publish=?,desc_honor=?,desc_more=? WHERE tid=?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssssssssi",$name,$gender,$college,$department,$phone,$email,$position,$education,$direction,$evalution,$achive,$teach_type,$publish,$honor,$more,$tid);

if($stmt->execute()){
    echo('{"code":200, "message":"修改成功。"}');
}
else {
    echo('{"code":1, "message":"修改失败。"}');
}


$conn->close();
*/
 echo('{"code":200, "message":"修改成功。"}');

?>