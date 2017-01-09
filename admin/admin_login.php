<meta charset="utf-8">
<?php
    $username=$_POST["username"];
    $password=$_POST["password"];

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
    
    $sql="select aid from admin where username='{$username}' and password='{$password}'";
    
    $result=$mysqli->query($sql);

    if(!$result){
        echo "<script language='javascript'>alert('Error!');window.location.href='index.php';</script>";
    }
    else{
        if($result->num_rows>0){
            $row=$result->fetch_array();

            session_start();
            $_SESSION["userType"]="admin";
            $_SESSION["username"]=$username;
            $_SESSION["userName"]=$username;
            $_SESSION["userID"]=$row[0];

            header("location: admin_main.php");
        }
        else{
            echo "<script language='javascript'>alert('Wrong username or password! Re-login, please!');window.location.href='index.php';</script>";
        }
    }

    $result->free();
    $mysqli->close();
?>