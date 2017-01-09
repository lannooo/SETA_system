<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/19
 * Time: 下午11:20
 */
require_once "Auth.php";

$raw = file_get_contents('php://input');
$json = json_decode($raw);

setcookie("phone",$json->phone,time()+3600);
$auth = new Auth();

$auth->SendSmsCode($json->phone);


?>