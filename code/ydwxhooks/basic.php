<?php
// 这是你写hook的地方，根据你系统的情况包含相关基础库代码，比如数据库等

//access token 刷新
YDHook::add_hook(WXHooks::ACCESS_TOKEN_REFRESH, function($info){
   //token 刷新后的回调，你需要在自己的系统中进行更新
});

YDHook::add_hook(WXHooks::JSAPI_TICKET_REFRESH, function($info){
    //token 刷新后的回调，你需要在自己的系统中进行更新
});

YDHook::add_hook ( WXHooks::EVENT_SUBSCRIBE, function ($info) {
    //  用户订阅后的回调
} );

YDHook::add_hook ( WXHooks::EVENT_UNSUBSCRIBE, function ($info) {
    // 用户取消订阅后的回调
} );