<?php

//引用你项目的文件及库

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

} );

YDWXHook::add_hook ( YDWXHook::EVENT_UNSUBSCRIBE, function ($info) {

} );