<?php
// 这是你写hook的地方，根据你系统的情况包含相关基础库代码，比如数据库等

YDHook::add_hook(WXHooks::PAY_NOTIFY_ERROR, function($error){
    //支付失败，error为错误字符串
});

YDHook::add_hook(WXHooks::PAY_NOTIFY_SUCCESS, function(WXMsg $wxmsg){
    //支付成功的处理
});
