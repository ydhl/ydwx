<?php
/**
 * 卡券相关js处理的后端
 */
chdir(dirname(__FILE__));//把工作目录切换到文件所在目录
include_once dirname(__FILE__).'/../__config__.php';

$action = $_POST['action'];
if( ! $action) {
    echo json_encode(ydwx_error("非法请求"));
    die;
}

if($action == "chooseCard"){
    $shopId     = $_POST['shopId'];
    $cardType   = $_POST['cardType'];
    $cardId     = $_POST['cardId'];
    $appid      = $_POST['appid'];
    $nonceStr   = uniqid();
    $time       = time();
    
    if($appid){
        $card_jsapi_ticket = YDWXHook::do_hook(YDWXHook::GET_HOST_CARD_JSAPI_TICKET, $appid);
    }else{
        $card_jsapi_ticket = YDWXHook::do_hook(YDWXHook::GET_CARD_JSAPI_TICKET);
    }
    
    $array = array($nonceStr,$card_jsapi_ticket,$time,$shopId,$cardType,$cardId);
    $array = YDWXRequest::ignoreNull($array);
    sort($array);
    $cardSignStr    = sha1(join("", $array));
    echo json_encode(ydwx_success(array(
            "shopId"    => $shopId,
            "cardType"  => $cardType,
            "cardId"    => $cardId,
            "nonceStr"  => $nonceStr,
            "time"      => $time,
            "cardSign"  => $cardSignStr,
    )));
    die;
}


if($action == "addCard"){
    $exts     = $_POST['exts'];
    $appid    = $_POST['appid'];
    if($appid){
        $card_jsapi_ticket = YDWXHook::do_hook(YDWXHook::GET_HOST_CARD_JSAPI_TICKET, $appid);
    }else{
        $card_jsapi_ticket = YDWXHook::do_hook(YDWXHook::GET_CARD_JSAPI_TICKET);
    }
    $array = array();
    
    foreach ($exts as $ext){
        $extObj = new YDWXCardExt();
        $extObj->cardId = $ext['cardId'];
        $extObj->code   = @$ext['code'];
        $extObj->openid = @$ext['openid'];
        $extObj->jsApiTicket = $card_jsapi_ticket;
        $array[] = $extObj->toArray();
    }
    echo json_encode(ydwx_success($array));
    die;
}
?>