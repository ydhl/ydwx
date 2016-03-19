<?php
/**
 * 微信对接入口，需要在微信后台开发者模式下配置：
 * 1. 微信Token验证。GET提交
 * 2. 微信事件通知，POST提交
 * 3. 代理的公众号的事件微信会推送到包含/$APPID$的地址中,
 * 这时只需在该地址的处理中require 本文件即可。并把地址上的APPID取出来放在$APPID变量中
 * 这时消息中$APPID既是appid，可以用它区分是那个公众号
 */
chdir(dirname(__FILE__));//把工作目录切换到文件所在目录
include_once dirname(__FILE__).'/__config__.php';
//Token 验证，微信验证主体身份。如果是第三方平台，则不存在token验证
if( ! $GLOBALS["HTTP_RAW_POST_DATA"]){
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
$from_xml  = @$GLOBALS["HTTP_RAW_POST_DATA"];
$msg_sign  = $_GET["msg_signature"];
$timeStamp = $_GET["timestamp"];
$nonce     = $_GET["nonce"];

$crypt = new WXBizMsgCrypt(YDWX_WEIXIN_COMPONENT_TOKEN, YDWX_WEIXIN_COMPONENT_ENCODING_AES_KEY, YDWX_WEIXIN_COMPONENT_APP_ID);

$msg = '';
$errCode = $crypt->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
if( ! $msg)die("success");

YDWXHook::do_hook(YDWXHook::YDWX_LOG, $msg.$APPID);

//微信事件指派
$wxevent  = YDWXEvent::CreateEventMsg($msg);
if(@$APPID){
   $wxevent->APPID = $APPID;
}
YDWXHook::do_hook($wxevent->HookName(), $wxevent);