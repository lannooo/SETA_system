<meta charset="utf-8">
<?php
    session_start();
    $coid=$_SESSION["coid"];
    $name=$_POST["name"];
    $cltime=$_POST["cltime"];
    $place=$_POST["place"];
    $student_num=$_POST["student_num"];
    
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
        
    $sql="select tid from teacher where name='{$name}'";

    $result=$mysqli->query($sql);

    if(!$result){
        echo "<script language='javascript'>alert('Error!');window.location.href='admin_course_detail.php?id1=$coid';</script>";
    }
    else{
        if($result->num_rows>0){
            $row=$result->fetch_array();
            $sql="insert into class values (null,{$coid},{$row[0]},'{$cltime}','{$place}',{$student_num})";
            $result1=$mysqli->query($sql);

            if(!$result1){
                echo "<script language='javascript'>alert('Error!');window.location.href='admin_course_detail.php?id1=$coid';</script>";
            }
        }
    }
    $result->free();
    $mysqli->close();

    echo "<script language='javascript'>alert('添加课程成功');window.location.href='admin_course_detail.php?id1=$coid';</script>";
?>