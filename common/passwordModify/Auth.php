<?php
/**
 * Created by PhpStorm.
 * User: leif
 * Date: 2016/9/10
 * Time: 9:20
 */

//Auth 类，直接引入就行

class Auth
{

//将你注册的 key和 secret 定义好。

    const APP_KEY = '95f1ba2452105aeed8e240b8acc9b76d';
    const APP_SECRET = 'f3ca2181f3da';
//发送验证码函数，传入手机号即可
    public function SendSmsCode($mobile){
        $appKey = self::APP_KEY;
        $appSecret = self::APP_SECRET;
        $nonce = '100';
        $curTime = time();
        $checkSum = sha1($appSecret . $nonce . $curTime);
        $data  = array(
            'mobile'=> $mobile,
        );
        $data = http_build_query($data);
        $opts = array (
            'http' => array(
                'method' => 'POST',
                'header' => array(
                    'Content-Type:application/x-www-form-urlencoded;charset=utf-8',
                    "AppKey:$appKey",
                    "Nonce:$nonce",
                    "CurTime:$curTime",
                    "CheckSum:$checkSum"
                ),
                'content' =>  $data
            ),
        );
        $context = stream_context_create($opts);
        $html = file_get_contents("https://api.netease.im/sms/sendcode.action", false, $context);
        echo $html;
    }
//验证码校验函数，传入手机号，以及该手机号反馈给你的验证码，
    public function CheckSmsYzm($mobile,$Code){
        $appKey = self::APP_KEY;
        $appSecret = self::APP_SECRET;
        $nonce = '100';
        $curTime = time();
        $checkSum = sha1($appSecret . $nonce . $curTime);
        $data  = array(
            'mobile'=> $mobile,
            'code' => $Code,
        );
        $data = http_build_query($data);
        $opts = array (
            'http' => array(
                'method' => 'POST',
                'header' => array(
                    'Content-Type:application/x-www-form-urlencoded;charset=utf-8',
                    "AppKey:$appKey",
                    "Nonce:$nonce",
                    "CurTime:$curTime",
                    "CheckSum:$checkSum"
                ),
                'content' =>  $data
            ),
        );
        $context = stream_context_create($opts);
        $html = file_get_contents("https://api.netease.im/sms/verifycode.action", false, $context);
        echo $html;
    }
}