<?php
/**
 * Created by PhpStorm.
 * User: 51499
 * Date: 2017/1/1 0001
 * Time: 0:52
 */

session_start();
require "commonAPI/database.php";
if(isset($_SESSION['userType'])&&$_SESSION['userName']&&$_SESSION['userID']){
    $identity = $_SESSION['userType'];
    $pmid = $_GET['pmid'];
    if($identity=='admin'){
        $con = get_connect();
        $sql = "delete from passby_message where pmid=".$pmid;
        if($result = mysqli_query($con, $sql)){
            echo "<script language='javascript' type='text/javascript'>alert('删除留言成功');";
            echo "history.go(-1);";
            echo "</script>";
        }
        else{
            echo "<script language='javascript' type='text/javascript'>alert('删除留言失败');";
            echo "history.go(-1);";
            echo "</script>";
        }
    }
}else{
    echo "<script language='javascript' type='text/javascript'>alert('请先登录');";
    echo "window.location.href='/admin/admin_login.php'";
    echo "</script>";
}

?>
