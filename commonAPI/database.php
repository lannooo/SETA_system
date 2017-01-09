<?php

function get_connect(){
    /*数据库的连接可以写一起*/
    $con = mysqli_connect("localhost:3306","root","root");
    if(!$con) {
        die('connect error'.mysqli_error());
    }
    mysqli_select_db($con, "software_eng");
    mysqli_query($con,"set names 'utf8'");  /*设置编码为utf-8*/
    return $con;
}
