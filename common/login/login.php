<?php
/**
 * Created by PhpStorm.
 * User: inaccessible
 * Date: 16/11/30
 * Time: 下午4:45
 */
session_start();

require_once "connect.php";




$raw = file_get_contents('php://input');
$json = json_decode($raw);

$username = $json->username;
$pwd = $json->password;
$group = $json->group;


if($group == "teacher"){
    $sql = "select tid,password from teacher where username=?";
    $stmt = $conn->prepare($sql);
//绑定参数
    $stmt->bind_param("s",$username);
//绑定结果集
    $stmt->bind_result($t_id,$t_pwd);
//执行
    $stmt->execute();

//取出绑定的结果集
    if($stmt->fetch()){

        if($t_pwd == $pwd) {
            echo('{"code":200, "message":"登录成功。"}');
            setcookie("userName", $username, time()+3600);
            setcookie("userType","teacher", time()+3600);
            setcookie("userID", $t_id, time()+3600);
            $_SESSION["userID"] = $t_id;
            $_SESSION["userName"] = $username;
            $_SESSION["userType"] = "teacher";
        }
        else {
            echo('{"code":1, "message":"用户名合法。"}');
        }
    }

    else {
        echo('{"code":2, "message":"不存在该账户。"}');
    }
}

if($group == "student"){
    $sql = "select sid,password from student where username=?";
    $stmt = $conn->prepare($sql);
//绑定参数
    $stmt->bind_param("s",$username);
//绑定结果集
    $stmt->bind_result($s_id,$s_pwd);
//执行
    $stmt->execute();

//取出绑定的结果集
    if($stmt->fetch()){

        if($s_pwd == $pwd) {
            echo('{"code":200, "message":"登录成功。"}');
            setcookie("userName", $usrname, time()+3600);
            setcookie("userType","student", time()+3600);
            setcookie("userID", $s_id, time()+3600);
            $_SESSION["userID"] = $s_id;
            $_SESSION["userName"] = $username;
            $_SESSION["userType"] = "student";
        }
        else {
            echo('{"code":1, "message":"用户名合法。"}');
        }
    }

    else {
        echo('{"code":2, "message":"不存在该账户。"}');
    }
}

if($group == "ta_assist"){
    $sql = "select taid,password from ta_assist where username=?";
    $stmt = $conn->prepare($sql);
//绑定参数
    $stmt->bind_param("s",$username);
//绑定结果集
    $stmt->bind_result($ta_id,$ta_pwd);
//执行
    $stmt->execute();

//取出绑定的结果集
    if($stmt->fetch()){

        if($ta_pwd == $pwd) {
            echo('{"code":200, "message":"登录成功。"}');
            setcookie("userName", $username, time()+3600);
            setcookie("userType","TA", time()+3600);
            setcookie("userID", $ta_id, time()+3600);
            $_SESSION["userID"] = $ta_id;
            $_SESSION["userName"] = $username;
            $_SESSION["userType"] = "TA";
        }
        else {
            echo('{"code":1, "message":"用户名合法。"}');
        }
    }

    else {
        echo('{"code":2, "message":"不存在该账户。"}');
    }

}

$conn->close();
?>
