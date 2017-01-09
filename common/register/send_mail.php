<?php

/*require_once "mail.php";

$raw = file_get_contents('php://input');
$json = json_decode($raw);

setcookie("mailCode", "$json->body", time()+3600);
setcookie("mail","$json->mail",time()+3600);
if (!send_mail($json->subject, $json->body,$json->mail)) {
    setcookie("mail", $mail,time()+3600);
    echo('{"code":200, "message":"发送成功。"}');
} else {
    echo('{"code":1, "message":"发送失败。"}');
}*/
echo('{"code":1, "message":"发送失败。"}');
?>
