<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/22
 * Time: 下午3:13
 */
require_once "connect.php";

$raw = file_get_contents('php://input');
$json = json_decode($raw);

$username=$json->username;
$group=$json->group;

if($group=="teacher")
    $sql = "SELECT tid,password FROM teacher WHERE username=?";
else if($group=="student")
    $sql = "SELECT sid,password FROM student WHERE username=?";
else if($group=="ta_assist")
    $sql = "SELECT taid,password FROM ta_assist WHERE username=?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s",$username);

$stmt->bind_result($id,$pwd);

$stmt->execute();

if(!$stmt->fetch()){
    $stmt->close();
    echo('{"code":1, "message":"不存在该用户名。"}');
}
else {
    $stmt->close();
    if ($group == "teacher") {
        $sql = "SELECT mail FROM teacher WHERE tid=" . $id;

        $result = $conn->query($sql);
        if ($result == false) {
            echo('{"code":0, "message": "查询失败"}');
        } else {
            $row = $result->fetch_assoc();
            echo('{"code":200, "message": "合法用户名", "mail":' . $row['email'] . '}');
        }
    } else if ($group == "student") {
        $sql = "SELECT verified_phone, verified_email FROM student WHERE sid=" . $id;

        $result = $conn->query($sql);

        $row = $result->fetch_assoc();

        $v = $row['verified_phone'] & $row['verified_email'];


        if ($v) {
            $sql = "SELECT email FROM student_info WHERE sid=" . $id;

            $result = $conn->query($sql);

            if ($result == false) {
                echo('{"code":0, "message": "查询失败"}');
            } else {
                $row = $result->fetch_assoc();
                echo('{"code":200, "message": "合法用户名", "mail":"' . $row['email'] . '"}');
            }
        } else {
            echo('{"code":1, "message": "未认证用户"}');
        }
    } else if ($group == "ta_assist") {

        $sql = "SELECT email FROM ta_info WHERE taid=" . $id;

        $result = $conn->query($sql);

        if ($result == false) {
            echo('{"code":0, "message": "查询失败"}');
        } else {
            $row = $result->fetch_assoc();
            echo('{"code":200, "message": "合法用户名", "mail":"' . $row['email'] . '"}');
        }
    } else {
        echo('{"code":1, "message": "未认证用户"}');
    }
}


?>