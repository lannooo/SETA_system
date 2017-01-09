<meta charset="utf-8">
<?php
    session_start();
    $coid=$_SESSION["coid"];

    $option=$_POST["option"];
    $content=$_POST["content"];

    $dbhost="localhost";
    $dbuser="sem";
    $dbpassword="sem2016";
    $dbname="software_eng";

    $mysqli=new mysqli($dbhost,$dbuser,$dbpassword,$dbname);

    if(mysqli_connect_errno()){
        echo mysqli_connect_error();
        die;
    }

    $mysqli->query("set names UTF-8");
    
    $sql="update course set {$option}='{$content}' where coid='{$coid}'";
    
    $result=$mysqli->query($sql);

    if(!$result){
        echo "<script language='javascript'>alert('Error!');window.location.href='admin_course.php';</script>";
    }
    else{
        echo "<script language='javascript'>alert('修改成功');window.location.href='admin_course.php';</script>";
    }

    $result->free();
    $mysqli->close();
?>