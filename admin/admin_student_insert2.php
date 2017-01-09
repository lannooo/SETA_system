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

    $sql="select * from student where username='{$username}'";

    $result=$mysqli->query($sql);

    if(!$result){
        echo "<script language='javascript'>alert('Error!');window.location.href='admin_student.php';</script>";
    }
    else{
        if($result->num_rows>0){
            echo "<script language='javascript'>alert('添加学生成功');window.location.href='admin_student.php';</script>";
        }
        else{
            $sql="insert into student (sid,username,password,verified_email,verified_phone) values (null,'{$username}','{$username}',0,0)";
    
            $result=$mysqli->query($sql);

            if(!$result){
                echo "<script language='javascript'>alert('Error!');window.location.href='admin_student.php';</script>";
            }
            else{
                $sql="select sid from student where username='{$username}'";

                $result=$mysqli->query($sql);

                if(!$result){
                    echo "<script language='javascript'>alert('Error!');window.location.href='admin_student.php';</script>";
                }
                else{
                    if($result->num_rows>0){
                        while($row=$result->fetch_array()){
                            $sql="insert into student_info (sid,name,gender,college,major) values ({$row[0]},'{$name}','X','未填写','未填写')";

                            $result=$mysqli->query($sql);

                            if(!$result){
                                echo "<script language='javascript'>alert('Error!');window.location.href='admin_student.php';</script>";
                            }
                            else{
                                echo "<script language='javascript'>alert('添加学生成功');window.location.href='admin_student.php';</script>";
                            }
                        }
                    }
                }
            }
        }
    }
    
    $result->free();
    $mysqli->close();
?>