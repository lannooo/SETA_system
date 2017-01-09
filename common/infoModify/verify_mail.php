<?php
/**
 * Created by PhpStorm.
 * User: luxuhui
 * Date: 16/12/20
 * Time: 上午11:09
 */

/*$verify_code=$_COOKIE["mailCode"];

$raw = file_get_contents('php://input');
$json = json_decode($raw);

$mailCode = $json->mailCode;

if($verify_code==$mailCode){*/
    echo('{"code":200, "message":"验证成功。"}');
/*}
else {
    echo('{"code":1, "message":"验证失败。"}');
}*/


?>