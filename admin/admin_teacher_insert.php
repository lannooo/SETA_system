<meta charset="utf-8">
<?php
    $username=$_POST["username"];
    $name=$_POST["name"];

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
    
    $sql="insert into teacher (tid,username,password,verified_email,verified_phone,name) values (null,'{$username}','{$username}',0,0,'{$name}')";
    
    $result=$mysqli->query($sql);

    if(!$result){
        echo "<script language='javascript'>alert('Error!');window.location.href='admin_teacher.php';</script>";
    }
    else{
        echo "<script language='javascript'>alert('添加教师成功');window.location.href='admin_teacher.php';</script>";
    }

    $result->free();
    $mysqli->close();
?>