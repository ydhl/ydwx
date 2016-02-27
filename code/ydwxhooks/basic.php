<?php
use app\wxmsg\Auto_Reply_Model;
use app\sp\Service_Provider_Model;
use app\common\User_Model;
use app\sp\Consumer_Model;
use app\common\Option_Model;
$oldcwd = getcwd ();
chdir ( dirname ( __FILE__ ) . '/../' );
require_once 'init.php';
chdir ( $oldcwd );

// access token 刷新
YDWXHook::add_hook ( YDWXHook::REFRESH_ACCESS_TOKEN, function (YDWXAccessTokenResponse $info) {
    Option_Model::option_save ( Option_Model::OP_ACCESS_TOKEN, $info->access_token );
    Option_Model::option_save ( Option_Model::OP_ACCESS_TOKEN_EXPIRES_IN, $info->expires_in );
} );

YDWXHook::add_hook ( YDWXHook::REFRESH_JSAPI_TICKET, function (YDWXJsapiTicketResponse $info) {
    Option_Model::option_save ( Option_Model::OP_JSAPI_TICKET, $info->ticket );
    Option_Model::option_save ( Option_Model::OP_JSAPI_TICKET_EXPIRES_IN, $info->expires_in );
} );

YDWXHook::add_hook ( YDWXHook::REFRESH_CARD_JSAPI_TICKET, function (YDWXJsapiTicketResponse $info) {
    Option_Model::option_save ( Option_Model::OP_CARD_JSAPI_TICKET, $info->ticket );
    Option_Model::option_save ( Option_Model::OP_CARD_JSAPI_TICKET_EXPIRES_IN, $info->expires_in );
} );

// access token 刷新
YDWXHook::add_hook ( YDWXHook::GET_ACCESS_TOKEN, function ($info) {
    $option = Option_Model::option_get ( Option_Model::OP_ACCESS_TOKEN );
    if ($option)
        return $option->get ( "option_value" );
    return "";
} );

YDWXHook::add_hook ( YDWXHook::GET_JSAPI_TICKET, function ($info) {
    $option = Option_Model::option_get ( Option_Model::OP_JSAPI_TICKET );
    if ($option)
        return $option->get ( "option_value" );
    return "";
} );
YDWXHook::add_hook ( YDWXHook::GET_CARD_JSAPI_TICKET, function ($info) {
    $option = Option_Model::option_get ( Option_Model::OP_CARD_JSAPI_TICKET );
    if ($option)
        return $option->get ( "option_value" );
    return "";
} );

//获取托管的公众号的jsapi ticket
YDWXHook::add_hook ( YDWXHook::GET_HOST_JSAPI_TICKET, function ($appid) {
    $sp = reset(Service_Provider_Model::find_by_attrs(array("appid"=>$appid)));
    if( ! $sp) return;
    $sp_id = $sp->get_key();
    $option = Option_Model::option_get ( Option_Model::OP_HOST_JSAPI_TICKET , $sp_id);
    if ($option)
        return $option->get ( "option_value" );
    return "";
            
} );
//获取托管的公众号的card jsapi ticket
YDWXHook::add_hook ( YDWXHook::GET_HOST_CARD_JSAPI_TICKET, function ($appid) {
    $sp = reset(Service_Provider_Model::find_by_attrs(array("appid"=>$appid)));
    if( ! $sp) return;
    $sp_id = $sp->get_key();
    $option = Option_Model::option_get ( Option_Model::OP_HOST_CARD_JSAPI_TICKET , $sp_id);
    if ($option)
        return $option->get ( "option_value" );
    return "";    
} );
YDWXHook::add_hook ( YDWXHook::EVENT_SUBSCRIBE, function (YDWXEventSubscribe $info) {
    // 用户订阅后的回调
    $consumer = Consumer_Model::get_by_openid ( $info->FromUserName, $info->APPID );
    if ( ! $consumer ) {
        $consumer = register_consumer ( $info->FromUserName, $info->APPID, null, true);
    } else {
        $consumer->set("is_focus", 1)
            ->set("focus_time", yd_date())
            ->save();
    }
    auto_reply ( $consumer->get ( "sp_id" ), "focus", $info );
} );

YDWXHook::add_hook ( YDWXHook::EVENT_UNSUBSCRIBE, function ($info) {
    // 用户取消订阅后的回调
    $openid = $info->FromUserName;
    $consumer = Consumer_Model::get_by_openid ( $openid );
    if ($consumer)
        $consumer->set ( "is_focus", 0 )->save ();
} );