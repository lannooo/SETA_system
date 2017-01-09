<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/28
 * Time: 下午1:32
 */
session_start();

unset($_SESSION['userID']);
unset($_SESSION['userType']);
unset($_SESSION['uesrName']);
setcookie("userName", $username, time()-3600);
setcookie("userType","teacher", time()-3600);
setcookie("userID", $t_id, time()-3600);

Header("Location: http://zjuse.lannooo.cn"); 
?>