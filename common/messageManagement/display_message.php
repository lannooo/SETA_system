<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/24
 * Time: 下午12:03
 */
require_once 'connect.php';

$raw=file_get_contents('php://input');
$json=json_decode($raw);

$mid=$json->mid;
$type=$json->type;


if($type=="receive")
    $sql = "SELECT title,mdate,content,fromid,fromtype, ifread FROM message WHERE mid=?";
else if($type=="send")
    $sql = "SELECT title,mdate,content,toid, totype, ifread FROM message WHERE mid=?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$mid);
$stmt->bind_result($title,$date,$content,$tfid,$tftype,$ifread);
$stmt->execute();

$num = 0;
if($stmt->fetch()) {
    $stmt->close();

    if($type=="receive"){
        $sql= "UPDATE message SET ifread=1 WHERE mid=".$mid;
        $conn->query($sql);
    }

    if ($tftype == 0) {
        $sql = "SELECT name FROM student_info WHERE sid=" . $tfid;
    } else if ($tftype == 1) {
        $sql = "SELECT name FROM teacher WHERE tid=" . $tfid;
    } else {
        $sql = "SELECT name FROM ta_info WHERE taid=" . $tfid;
    }
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name'];


    if ($tftype == 0) {
        $sql = "SELECT username FROM student WHERE sid=" . $tfid;
    } else if ($tftype == 1) {
        $sql = "SELECT username FROM teacher WHERE tid=" . $tfid;
    } else {
        $sql = "SELECT username FROM ta_assist WHERE taid=" . $tfid;
    }
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $username = $row['username'];



    $returnResult = '{"mid":' . $mid . ',"content":"' . $content. '", "name":"' . $name . '", "title":"' . $title . '", "date":"' . $date . '", "tfid":'. $tfid. ',"tftype":'. $tftype. ',"username":"' . $username. '"}';
    $num += 1;
}


echo($returnResult);

$conn->close();


?>