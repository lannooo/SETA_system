<meta charset="utf-8">
<?php
    session_start();
    $username=$_SESSION["username"];

    $old_password=$_POST["old_password"];
    $new_password1=$_POST["new_password1"];
    $new_password2=$_POST["new_password2"];

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
    
    $sql="select password from admin where username='{$username}'";
    
    $result=$mysqli->query($sql);

    if(!$result){
        echo "<script language='javascript'>alert('Error!');window.location.href='admin_info_password.php';</script>";
    }
    else{
        if($result->num_rows>0){
            while($row =$result->fetch_array()){
                if($row[0]!=$old_password){
                    echo "<script language='javascript'>alert('旧密码错误');window.location.href='admin_info_password.php';</script>";
                }
                else{
                    if($new_password1!=$new_password2){
                        echo "<script language='javascript'>alert('新密码不一致');window.location.href='admin_info_password.php';</script>";
                    }
                    else{
                        $sql="update admin set password='{$new_password1}' where username='{$username}'";
                        $result=$mysqli->query($sql);
                        if(!$result){
                            echo "<script language='javascript'>alert('Error!');window.location.href='admin_info_password.php';</script>";
                        }
                        else{
                            echo "<script language='javascript'>alert('修改成功');window.location.href='admin_info.php';</script>";
                        }
                    }
                }
            }
        }
        else{
            echo "<script language='javascript'>alert('Error!');window.location.href='admin_info_password.php';</script>";
        }
    }

    $result->free();
    $mysqli->close();
?>