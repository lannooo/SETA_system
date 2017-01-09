<meta charset="utf-8">
<?php
    $coname=$_POST["coname"];
    $textbook=$_POST["textbook"];
    $cocode=$_POST["cocode"];
    $cotype=$_POST["cotype"];
    $semster=$_POST["semster"];
    $coname_en=$_POST["coname_en"];
    $college=$_POST["college"];
    $credit=$_POST["credit"];
    $week_learn_time=$_POST["week_learn_time"];
    $weight=$_POST["weight"];
    $pre_learning=$_POST["pre_learning"];
    $plan=$_POST["plan"];
    $background=$_POST["background"];
    $assessment=$_POST["assessment"];
    $project_info=$_POST["project_info"];

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
    
    $sql="insert into course (coid,coname,textbook,cocode,cotype,semster,coname_en,college,credit,week_learn_time,weight,pre_learning,plan,background,assessment,project_info) values (null,'{$coname}','{$textbook}','{$cocode}','{$cotype}','{$semster}','{$coname_en}','{$college}','{$credit}','{$week_learn_time}','{$weight}','{$pre_learning}','{$plan}','{$background}','{$assessment}','{$project_info}')";
    
    $result=$mysqli->query($sql);

    if(!$result){
        echo "<script language='javascript'>alert('Error!');window.location.href='admin_course.php';</script>";
    }
    else{
        echo "<script language='javascript'>alert('添加课程成功');window.location.href='admin_course.php';</script>";
    }

    $result->free();
    $mysqli->close();
?>