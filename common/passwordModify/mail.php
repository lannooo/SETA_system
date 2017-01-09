<?php
// Pear Mail Library
require_once "Mail.php";

const SMTP = 'smtp.126.com';
const PORT = '25';
const USERNAME = 'luxuhui12345@126.com';
const PASSWORD = 'lxhxhd123';


function send_mail($subject, $body, $pmail) {
    $subject = "=?UTF-8?B?".base64_encode($subject)."?=";
    $body = "您的验证码为：".$body;
    $from = '<luxuhui12345@126.com>';
    $to = $pmail;
    $headers = array(
        'From' => $from,
        'To' => $to,
        'Subject' => $subject
    );

    $smtp = Mail::factory('smtp', array(
        'host' => SMTP,
        'port' => PORT,
        'auth' => true,
        'username' => USERNAME,
        'password' => PASSWORD
    ));

    $mail = $smtp->send($to, $headers, $body);

    if (PEAR::isError($mail)) {
        return true;
    } else {
        return false;
    }
}
?>