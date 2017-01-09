<meta charset="utf-8">
<?php
    $coid=$_GET["id1"];

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
    
    $sql="delete from course where coid='{$coid}'";
    
    $result=$mysqli->query($sql);

    if(!$result){
        echo "<script language='javascript'>alert('Error!');window.location.href='admin_course.php';</script>";
    }
    else{
        echo "<script language='javascript'>alert('删除成功');window.location.href='admin_course.php';</script>";
    }

    $result->free();
    $mysqli->close();
?>