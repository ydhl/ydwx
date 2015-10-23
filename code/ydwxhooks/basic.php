<?php
/**
 * $oldcwd = getcwd();
 * #如需要把工作目录切换到你项目中去，并包含项目的库文件来实现hook中的逻辑
 * chdir($your_work_dir);
 * include_once 'your-lib-file.php';
 * chdir ( $oldcwd );
 */

// access token 刷新
YDWXHook::add_hook ( YDWXHook::REFRESH_ACCESS_TOKEN, function (YDWXAccessTokenResponse $info) {
    
} );

YDWXHook::add_hook ( YDWXHook::REFRESH_JSAPI_TICKET, function (YDWXJsapiTicketResponse $info) {
    
} );

YDWXHook::add_hook ( YDWXHook::REFRESH_CARD_JSAPI_TICKET, function (YDWXJsapiTicketResponse $info) {
    
} );

// access token 刷新
YDWXHook::add_hook ( YDWXHook::GET_ACCESS_TOKEN, function ($info) {
    
} );

YDWXHook::add_hook ( YDWXHook::GET_JSAPI_TICKET, function ($info) {
    
} );
YDWXHook::add_hook ( YDWXHook::GET_CARD_JSAPI_TICKET, function ($info) {
   
} );

//获取托管的公众号的jsapi ticket
YDWXHook::add_hook ( YDWXHook::GET_HOST_JSAPI_TICKET, function ($appid) {
    
            
} );
//获取托管的公众号的card jsapi ticket
YDWXHook::add_hook ( YDWXHook::GET_HOST_CARD_JSAPI_TICKET, function ($appid) {
       
} );
YDWXHook::add_hook ( YDWXHook::EVENT_SUBSCRIBE, function (YDWXEventSubscribe $info) {
    // 用户订阅后的回调
    
} );

YDWXHook::add_hook ( YDWXHook::EVENT_UNSUBSCRIBE, function ($info) {
    // 用户取消订阅后的回调
    
} );