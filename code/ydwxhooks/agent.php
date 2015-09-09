<?php
// 如果你作为第三方平台，你需要注意一下hook
YDWXHook::add_hook ( YDWXHook::EVENT_COMPONENT_VERIFY_TICKET, function (YDWXEventComponent_verify_ticket $info) {
    // 微信推送过来的ticket，注意保存ticket
} );

YDWXHook::add_hook ( YDWXHook::GET_VERIFY_TICKET, function (YDWXEventComponent_verify_ticket $info) {
    // 返回保存在本地的ticket
} );