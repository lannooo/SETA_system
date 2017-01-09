<?php
session_start();

if(isset($_SESSION['id'])&&isset($_SESSION['identity'])&&isset($_SESSION['name'])){
    $identity = $_SESSION['identity'];
    if($identity=='student'){
        echo "<script language='javascript' type='text/javascript'>";
        echo "window.location.href='../student/Course_list.html'";
        echo "</script>";
    }
    else if($identity=='teacher'){
        echo "<script language='javascript' type='text/javascript'>";
        echo "window.location.href='../teacher/teacher-center.html'";
        echo "</script>";
    }

}else{
    echo "<script language='javascript' type='text/javascript'>";
    echo "window.location.href='../common/login/login.html'";
    echo "</script>";
}

?>
