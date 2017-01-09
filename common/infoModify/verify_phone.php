<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/20
 * Time: 上午12:59
 */
require_once "Auth.php";

$raw = file_get_contents('php://input');
$json = json_decode($raw);

$phone = $json->phone;
$phoneCode = $json->phoneCode;


$auth = new Auth();

$auth->CheckSmsYzm($phone, $phoneCode);

?>