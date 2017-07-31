<?php
/**
 * 微信对接入口，需要在微信后台开发者模式下配置：
 * 1. 微信Token验证。GET提交
 * 2. 微信事件通知，POST提交
 */
chdir(dirname(__FILE__));//把工作目录切换到文件所在目录
include_once dirname(__FILE__).'/../__config__.php';
//Token 验证，微信验证主体身份。如果是第三方平台，则不存在token验证
$raw = file_get_contents('php://input');
if( ! $raw){
	$signature  = $_GET["signature"];
    $timestamp  = $_GET["timestamp"];
    $nonce      = $_GET["nonce"];
    $echostr    = $_GET["echostr"];
        
    $token  = YDWX_WEIXIN_TOKEN;
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );
        
    if( $tmpStr == $signature ){
        echo $echostr;
    }
    die;
}


//微信通知处理
$from_xml  = @$raw;
$msg_sign  = $_GET["msg_signature"];
$timeStamp = $_GET["timestamp"];
$nonce     = $_GET["nonce"];

$crypt = new WXBizMsgCrypt(YDWX_WEIXIN_TOKEN, YDWX_WEIXIN_ENCODING_AES_KEY, YDWX_WEIXIN_APP_ID);

$msg = '';
$errCode = $crypt->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
if( ! $msg)die("success");

YDWXHook::do_hook(YDWXHook::YDWX_LOG, $msg);

//微信事件指派
$wxevent  = YDWXEvent::CreateEventMsg($msg);
YDWXHook::do_hook($wxevent->HookName(), $wxevent);