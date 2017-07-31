<?php
/**
 * 摇一摇抽红包js后端
 */
chdir(dirname(__FILE__));//把工作目录切换到文件所在目录
include_once dirname(__FILE__).'/../__config__.php';

$lottery_id = $_POST['lottery_id'];
$key        = $_POST['key'];
$noncestr   = uniqid();

$openid     = $_POST['openid'];
$captcha    = $_POST['captcha'];

$array = array(
        "lottery_id"=> $lottery_id,
        "noncestr"  => $noncestr,
        "userid"    => $openid
);
if($captcha){
    $array['captcha'] = $captcha;
}
ksort($array);
$sign = strtoupper(md5(urldecode(http_build_query($array))."&key=".$key));
echo json_encode(ydwx_success(array(
    "noncestr"  => $noncestr,
    "openid"    => $openid,
    "captcha"    => $captcha,
    "sign"     => $sign)
));
?>