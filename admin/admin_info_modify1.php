<meta charset="utf-8">
<?php
    session_start();
    $username=$_SESSION["username"];

    $name=$_POST["name"];
    $phone=$_POST["phone"];
    $email=$_POST["email"];

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
    
    $sql="update admin set name='{$name}',phone='{$phone}',email='{$email}' where username='{$username}'";
    
    $result=$mysqli->query($sql);

    if(!$result){
        echo "<script language='javascript'>alert('Error!');window.location.href='admin_info_modify.php';</script>";
    }
    else{
        echo "<script language='javascript'>alert('修改成功');window.location.href='admin_info.php';</script>";
    }

    $result->free();
    $mysqli->close();
?>