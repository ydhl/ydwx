<?php
/**
 * 微信对接入口：
 * 1. 微信Token验证。GET提交
 * 2. 微信事件通知，POST提交
 */
include_once './libs/wx.php';


//Token 验证
if( ! $GLOBALS["HTTP_RAW_POST_DATA"]){
    if(WEIXIN_ACCOUNT_TYPE==WEIXIN_ACCOUNT_CROP){//企业号的url验证
        $signature  = $_GET["msg_signature"];
        $timestamp  = $_GET["timestamp"];
        $nonce      = $_GET["nonce"];
        $echostr    = $_GET["echostr"];
        
        $pc = new Prpcrypt(WEIXIN_ENCODING_AES_KEY);
        $sha1 = new SHA1;
        $array = $sha1->getSHA1(WEIXIN_TOKEN, $timestamp, $nonce, $echostr);
        $ret = $array[0];
        
        if ($ret != 0) {
            die();
        }
        
        $signature = $array[1];
        if ($signature != $signature) {
            die();
        }
        
        $result = $pc->decrypt($echostr, WEIXIN_CROP_ID);
        if ($result[0] != 0) {
            die();
        }
        
        echo $result[1];
    }else{//其它公众号的验证
        $signature  = $_GET["signature"];
        $timestamp  = $_GET["timestamp"];
        $nonce      = $_GET["nonce"];
        $echostr    = $_GET["echostr"];
        
        $token  = WEIXIN_TOKEN;
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
$pc        = new WXBizMsgCrypt(WEIXIN_TOKEN, WEIXIN_ENCODING_AES_KEY, WEIXIN_APP_ID);
$from_xml  = @$GLOBALS["HTTP_RAW_POST_DATA"];
$msg_sign  = $_GET["msg_signature"];
$timeStamp = $_GET["timestamp"];
$nonce     = $_GET["nonce"];

// insert("logs", array("content"=>"here"));

$msg = '';
$errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
if ($errCode != 0) {
    die("success");
}


//微信事件指派
$wxmsg  = WXMsg::build($msg);
// insert("logs", array("content"=>get_class($wxmsg).$msg));
if($wxmsg->Get(WXMsg::Event)){
    $hookname = strtoupper($wxmsg->Get(WXMsg::MsgType)."_".$wxmsg->Get(WXMsg::Event));
}else{
    $hookname = strtoupper($wxmsg->Get(WXMsg::MsgType));
}
insert("logs", array("content"=>"hook: WXHooks::$hookname constant ".constant("WXHooks::$hookname")));
YDHook::do_hook(constant("WXHooks::$hookname"), $wxmsg);