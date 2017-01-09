<?php
// echo $_POST['email'];
// echo $_POST['message_text'];
require 'commonAPI/passbyAPI.php';

$email = $_POST['email'];
$content = $_POST['message_text'];

if(leave_message($email, $content)){
    echo "<script language='javascript' type='text/javascript'>alert('留言成功');";
    echo "history.go(-1);";
    echo "</script>";
}else{
    echo "<script language='javascript' type='text/javascript'>alert('留言失败');";
    echo "history.go(-1);";
    echo "</script>";
}

?>
