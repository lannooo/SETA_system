<?php
$severname = "localhost:3306";
$usrname = "sem";
$password = "sem2016";
$dbname = "software_eng";


//创建连接

$conn = new mysqli($severname, $usrname, $password, $dbname);
$conn->query("SET CHARACTER SET utf8");
$conn->query("SET NAMES UTF8");
//检测连接
if($conn->connect_error)
die("连接失败：".$conn->connect_error);

?>