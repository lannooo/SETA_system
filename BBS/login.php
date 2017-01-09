<?php

session_start();

if(isset($_SESSION['userType'])&&$_SESSION['userName']&&$_SESSION['userID']){
    $identity = $_SESSION['userType'];
    $id = $_SESSION['userID'];

    require "../commonAPI/database.php";

    $con = get_connect();


    /*判断用户类型*/
    if($identity == 'teacher') {
        $table = 'teacher';
        $info_table = 'teacher';
        $idtype = 'tid';
    }
    else if($identity == 'student'){
        $table = 'student';
        $info_table = 'student_info';
        $idtype = 'sid';
    }
    else if($identity=='TA'){
        $info_table = 'ta_info';
        $idtype = 'taid';
    }
    /*根据用户类别不同，分别查找姓名*/
    $sql = "select name from ".$info_table." where ".$idtype."=".$id;
    if($result_userinfo = mysqli_query($con, $sql)) {
        if ($row_userinfo = mysqli_fetch_row($result_userinfo)) {
            $name = $row_userinfo[0];
        }
        mysqli_free_result($result_userinfo);
    }

    mysqli_close($con);

    $_SESSION['id'] = $id;           /*保存id到session中*/
    $_SESSION['name'] = $name;
    $_SESSION['identity'] = $identity;

    echo "<script language='javascript' type='text/javascript'>";
    echo "window.location.href='index.php'";
    echo "</script>";

}

else if(isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['identity'])){
    /*数据库的连接可以写一起*/
    $con = mysqli_connect("localhost:3306","root","root");
    if(!$con) {
        die('connect error'.mysqli_error());
    }
    mysqli_select_db($con, "software_eng");  /*设置编码为utf-8*/
    mysqli_query($con,"set names 'utf8'");

    $username = $_POST['username'];
    $password = $_POST['password'];
    $identity = $_POST['identity'];

    /*判断用户类型*/
    if($identity == 'teacher') {
        $table = 'teacher';
        $info_table = 'teacher';
        $idtype = 'tid';
    }
    else if($identity == 'student'){
        $table = 'student';
        $info_table = 'student_info';
        $idtype = 'sid';
    }

    /*查找用户是否valid,并取出用户的id作为后续查找依据*/
    $sql = "select * from ".$table." where username='".$username."' and password='".$password."'";
    if($result = mysqli_query($con, $sql)) {
        if($row=mysqli_fetch_row($result)) {
            $id = $row[0];
        }
        mysqli_free_result($result);
    }else{
        mysqli_close($con);
        echo "<script language='javascript' type='text/javascript'>alert('密码或者账号不正确！');";
        echo "window.location.href='login.html'";
        echo "</script>";
    }

    /*根据用户类别不同，分别查找姓名*/
    $sql = "select name from ".$info_table." where ".$idtype."=".$id;
    if($result_userinfo = mysqli_query($con, $sql)) {
        if ($row_userinfo = mysqli_fetch_row($result_userinfo)) {
            $name = $row_userinfo[0];
        }
        mysqli_free_result($result_userinfo);
    }

    mysqli_close($con);

    $_SESSION['id'] = $id;           /*保存id到session中*/
    $_SESSION['name'] = $name;
    $_SESSION['identity'] = $identity;

    echo "<script language='javascript' type='text/javascript'>";
    echo "window.location.href='index.php'";
    echo "</script>";

}else{
    echo "<script language='javascript' type='text/javascript'>alert('请先登录');";
    echo "window.location.href='../common/login.html'";
    echo "</script>";
}

