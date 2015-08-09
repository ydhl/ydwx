<?php
// 这是你写hook的地方，根据你系统的情况包含相关基础库代码，比如数据库等

YDWXHook::add_hook(YDWXHook::AUTH_CANCEL, function(){
    //用户取消登录了做什么，如header("Location: /signin");die;
});

YDWXHook::add_hook(YDWXHook::AUTH_FAIL, function($info){
    //用户登录是把了做什么，如header("Location: /signin?error=".$info['errmsg']);die;
});

YDWXHook::add_hook(YDWXHook::AUTH_INAPP_SUCCESS, function(array $info){
    //微信app内登录成功做什么，如判断该微信用户是否在系统中存在，不存在建立用户数据，存在标记为登录状态，并
    //导航到登录后看到的页面
});
YDWXHook::add_hook(YDWXHook::AUTH_WEB_SUCCESS, function(array $info){
    //网站上微信扫描登录成功做什么，如判断该微信用户是否在系统中存在，不存在建立用户数据，存在标记为登录状态，并
    //导航到登录后看到的页面
});
YDWXHook::add_hook(YDWXHook::AUTH_CROP_SUCCESS, function(array $info){
    //微信企业号app登录成功做什么，如判断该微信用户是否在系统中存在，不存在建立用户数据，存在标记为登录状态，并
    //导航到登录后看到的页面
});