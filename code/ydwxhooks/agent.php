<?php

//引用你项目的文件及库

YDWXHook::add_hook ( YDWXHook::EVENT_COMPONENT_VERIFY_TICKET, function (YDWXEventComponent_verify_ticket $info) {

} );

YDWXHook::add_hook ( YDWXHook::GET_VERIFY_TICKET, function () {

} );

YDWXHook::add_hook ( YDWXHook::REFRESH_AGENT_ACCESS_TOKEN, function (YDWXAccessTokenResponse $info) {

} );

YDWXHook::add_hook ( YDWXHook::GET_AGENT_ACCESS_TOKEN, function () {

} );