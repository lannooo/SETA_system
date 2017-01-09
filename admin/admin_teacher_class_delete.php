<meta charset="utf-8">
<?php
    $tid=$_GET["id1"];
    $clid=$_GET["id2"];

    $dbhost="localhost";
    $dbuser="root";
    $dbpassword="3573656";
    $dbname="software_eng";

    $mysqli=new mysqli($dbhost,$dbuser,$dbpassword,$dbname);

    if(mysqli_connect_errno()){
        echo mysqli_connect_error();
        die;
    }

    $mysqli->query("set names UTF-8");
    
    $sql="delete from class where clid='{$clid}'";
    
    $result=$mysqli->query($sql);

    if(!$result){
        echo "<script language='javascript'>alert('Error!');window.location.href='admin_teacher_detail.php?id1=$tid';</script>";
    }
    else{
        echo "<script language='javascript'>alert('删除成功');window.location.href='admin_teacher_detail.php?id1=$tid';</script>";
    }

    $result->free();
    $mysqli->close();
?>