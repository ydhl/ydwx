<?php
/**
 * 微信对接入口，需要在微信后台开发者模式下配置：
 * 1. 微信Token验证。GET提交
 * 2. 微信事件通知，POST提交
 */
include_once '__config__.php';


//Token 验证，微信验证主体身份
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
$pc        = new WXBizMsgCrypt(YDWX_WEIXIN_TOKEN, YDWX_WEIXIN_ENCODING_AES_KEY, YDWX_WEIXIN_APP_ID);
$from_xml  = @$GLOBALS["HTTP_RAW_POST_DATA"];
$msg_sign  = $_GET["msg_signature"];
$timeStamp = $_GET["timestamp"];
$nonce     = $_GET["nonce"];


$msg = '';
$errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
if ($errCode != 0) {
    die("success");
}


//微信事件指派
$wxevent  = YDWXEvent::CreateEventMsg($msg);
YDWXHook::do_hook($wxevent->HookName(), $wxevent);