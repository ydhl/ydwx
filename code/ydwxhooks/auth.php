<?php
/**
 * $oldcwd = getcwd();
 * #如需要把工作目录切换到你项目中去，并包含项目的库文件来实现hook中的逻辑
 * chdir($your_work_dir);
 * include_once 'your-lib-file.php';
 * chdir ( $oldcwd );
 */

YDWXHook::add_hook(YDWXHook::AUTH_CANCEL, function(){
    //用户取消登录了做什么，如
    header("Location: /signin");die;
});

YDWXHook::add_hook(YDWXHook::AUTH_FAIL, function(YDWXAuthFailResponse $info){
    //用户登录是把了做什么，如
    header("Location: /signin?error=".urlencode($info->errmsg));die;
});

YDWXHook::add_hook(YDWXHook::AUTH_INAPP_SUCCESS, function(YDWXOAuthUser $userinfo){
    //微信app内登录成功做什么，如判断该微信用户是否在系统中存在，不存在建立用户数据，存在标记为登录状态，并
    //导航到登录后看到的页面
    
});

YDWXHook::add_hook(YDWXHook::AUTH_WEB_SUCCESS, function(YDWXOAuthUser $wxuser){
    
});

//公众号授权成功
YDWXHook::add_hook(YDWXHook::AUTH_AGENT_SUCCESS, function(array $info){
   
});



YDWXHook::add_hook(YDWXHook::AUTH_CROP_SUCCESS, function(YDWXOAuthCropUser $info){
    //微信企业号app登录成功做什么，如判断该微信用户是否在系统中存在，不存在建立用户数据，存在标记为登录状态，并
    //导航到登录后看到的页面
});