<?php
session_start();
    // echo $_POST['id'];
    // echo $_POST['identity'];
    // echo $_POST['title'];
    // echo $_POST['content'];
/*身份验证，没有则返回登陆界面*/
if(isset($_SESSION['id'])&&isset($_SESSION['name'])&&isset($_SESSION['identity'])){
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
    $identity = $_SESSION['identity'];

}else{
    echo "<script language='javascript' type='text/javascript'>alert('请先登录');";
    echo "window.location.href='../common/login.html'";
    echo "</script>";
}

require "../commonAPI/database.php";

$con = get_connect();


$id=$_POST['id'];
$identity=$_POST['identity'];
$title=$_POST['title'];
$content=$_POST['content'];
// if($identity==='teacher')
//     echo $_POST['select_course'];
// else
//     echo $_POST['select_group'];

if($identity=='student'){
    $group = $_POST['select_group'];
    $gid='';
    $name = '';
    $sql = 'select gid from study_group where gname="'.$group.'"';
    if($result = mysqli_query($con, $sql)){
        if($row = mysqli_fetch_row($result)){
            $gid=$row[0];
        }
    }
    $sql = 'select name from student_info where sid='.$id;
    if($result = mysqli_query($con, $sql)){
        if($row = mysqli_fetch_row($result)){
            $name=$row[0];
        }
    }
    $sql = 'insert into group_post(gid, sid, name, title, post_date, frozon,content) values('.$gid.','.$id.',"'.$name.'","'.$title.'",now(), 0,"'.$content.'")';
    $result = mysqli_query($con, $sql);
    if($result){
        echo "<script language='javascript' type='text/javascript'>alert('发表成功');";
        echo "history.go(-1);";
        echo "</script>";
    }else{
        echo "<script language='javascript' type='text/javascript'>alert('发表失败');";
        echo "history.go(-1);";
        echo "</script>";
    }
}
else if($identity=='teacher'){
    $course = $_POST['select_course'];
    $name='';
    $coid='';
    $sql = 'select name from teacher where tid='.$id;
    if($result = mysqli_query($con, $sql)){
        if($row = mysqli_fetch_row($result)){
            $name=$row[0];
        }
    }
    $sql = 'select coid from course where coname="'.$course.'"';
    if($result = mysqli_query($con, $sql)){
        if($row = mysqli_fetch_row($result)){
            $coid=$row[0];
        }
    }
    $sql = 'insert into homework_post(coid, tid, name, title, post_date, frozon, content) values('.$coid.','.$id.',"'.$name.'","'.$title.'",now(),0,"'.$content.'")';
    $result = mysqli_query($con, $sql);
    if($result){
        echo "<script language='javascript' type='text/javascript'>alert('发表成功');";
        echo "history.go(-1);";
        echo "</script>";
    }else{
        echo "<script language='javascript' type='text/javascript'>alert('发表失败');";
        echo "history.go(-1);";
        echo "</script>";
    }

}

?>
