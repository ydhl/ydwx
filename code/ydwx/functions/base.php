<?php
function ydwx_json_encode($array){
    return urldecode(json_encode(ydwx_url_encode($array)));
}

function ydwx_url_encode($array){
    $temp = array();
    foreach($array as $key=>$value){
        if(is_array($value)){
            $temp[$key] = ydwx_url_encode($value);
        }else if(is_numeric($value) || is_bool($value)){
            $temp[$key] = $value;
        }else{
            $temp[$key] = str_replace("%22",'"',urlencode($value));
        }
    }
    return $temp;
}
function ydwx_error($message="", $code=null){
    return array('success'=> false, "data"=>null,"msg"=>$message);
}

function ydwx_success($data=null){
    return array('success'=> true, "data"=>$data,"msg"=>null);
}

function ydwx_qy_refresh_access_token($appid, $appsecret){
    $http = new YDHttp();
    $msg = $http->get(YDWX_WEIXIN_QY_BASE_URL."gettoken?corpid=".$appid."&corpsecret=".$appsecret);
    $accessToken = new YDWXAccessTokenResponse($msg);
    if($accessToken->isSuccess()) {
        return $accessToken;
    }
    throw new YDWXException($accessToken->errmsg, $accessToken->errcode);
}

function ydwx_qy_refresh_jsapi_ticket($token){
    $http = new YDHttp();
    $msg = $http->get(YDWX_WEIXIN_QY_BASE_URL."get_jsapi_ticket?access_token=".$token);
    $ticket = new YDWXJsapiTicketResponse($msg);
    if($ticket->isSuccess()) {
        return $ticket;
    }
    throw new YDWXException($ticket->errmsg, $ticket->errcode);
}

function ydwx_refresh_access_token($appid, $appsecret){
    $http = new YDHttp();
    $msg = $http->get(YDWX_WEIXIN_BASE_URL."token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret);
    $accessToken = new YDWXAccessTokenResponse($msg);
    if($accessToken->isSuccess()) {
        return $accessToken;
    }
    throw new YDWXException($accessToken->errmsg, $accessToken->errcode);
}

function ydwx_refresh_jsapi_ticket($token){
    $http = new YDHttp();
    $msg    = $http->get(YDWX_WEIXIN_BASE_URL."ticket/getticket?type=jsapi&access_token=".$token);
    $ticket = new YDWXJsapiTicketResponse($msg);
    if($ticket->isSuccess()) {
        return $ticket;
    }
    throw new YDWXException($ticket->errmsg, $ticket->errcode);
}

function ydwx_refresh_card_jsapi_ticket($token){
    $http = new YDHttp();
    $msg = $http->get(YDWX_WEIXIN_BASE_URL."ticket/getticket?type=wx_card&access_token=".$token);
    $ticket = new YDWXJsapiTicketResponse($msg);
    if($ticket->isSuccess()) {
        return $ticket;
    }
    throw new YDWXException($ticket->errmsg, $ticket->errcode);
}