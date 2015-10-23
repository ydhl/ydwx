<?php
/**
 * 微信对接入口，需要在微信后台开发者模式下配置：
 * 1. 微信Token验证。GET提交
 * 2. 微信事件通知，POST提交
 * 3. 对于第三方托管平台，代理的公众号的事件微信会推送到包含/$APPID$的地址中,
 * 这时只需在该地址的处理中require 本文件即可。并把地址上的APPID取出来放在$APPID变量中
 * 这时消息中$APPID既是appid，可以用它区分是那个公众号
 */
chdir(dirname(__FILE__));//把工作目录切换到文件所在目录
include_once dirname(__FILE__).'/__config__.php';
//Token 验证，微信验证主体身份。如果是第三方平台，则不存在token验证
if( ! $GLOBALS["HTTP_RAW_POST_DATA"]){
    if(YDWX_WEIXIN_ACCOUNT_TYPE==YDWX_WEIXIN_ACCOUNT_TYPE_CROP){//企业号的url验证
        $signature  = $_GET["msg_signature"];
        $timestamp  = $_GET["timestamp"];
        $nonce      = $_GET["nonce"];
        $echostr    = $_GET["echostr"];
        
        $pc = new Prpcrypt(YDWX_WEIXIN_ENCODING_AES_KEY);
        $sha1 = new SHA1;
        $array = $sha1->getSHA1(YDWX_WEIXIN_TOKEN, $timestamp, $nonce, $echostr);
        $ret = $array[0];
        
        if ($ret != 0) {
            die();
        }
        
        $signature = $array[1];
        if ($signature != $signature) {
            die();
        }
        
        $result = $pc->decrypt($echostr, YDWX_WEIXIN_CROP_ID);
        if ($result[0] != 0) {
            die();
        }
        
        echo $result[1];
    }else{//其它公众号的验证
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
    }
    die;
}


//微信通知处理
$from_xml  = @$GLOBALS["HTTP_RAW_POST_DATA"];
$msg_sign  = $_GET["msg_signature"];
$timeStamp = $_GET["timestamp"];
$nonce     = $_GET["nonce"];

if(YDWX_WEIXIN_COMPONENT_APP_ID){
    $crypt = new WXBizMsgCrypt(YDWX_WEIXIN_COMPONENT_TOKEN, YDWX_WEIXIN_COMPONENT_ENCODING_AES_KEY, YDWX_WEIXIN_COMPONENT_APP_ID);
}else{
    $crypt = new WXBizMsgCrypt(YDWX_WEIXIN_TOKEN, YDWX_WEIXIN_ENCODING_AES_KEY, YDWX_WEIXIN_APP_ID);
}

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