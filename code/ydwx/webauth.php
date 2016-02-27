<?php
/**
 * 网站进行微信OAuth登陆
 * 该页面可通过Redirect方式进行访问，或者直接在需要的地方include_once
 */
chdir(dirname(__FILE__));//把工作目录切换到文件所在目录

include_once dirname(__FILE__).'/__config__.php';

// state为交互时双方都会带着的get参数，用于做一些逻辑判断，如果没指定，则默认一个
if( ! $state){
    $state = "fromydwx";
} 

$redirect = YDWX_SITE_URL.'ydwx/webauth.php';

if( ! @$_GET['code'] &&  ! @$_GET['state']){
    ob_clean();
    header("Location: https://open.weixin.qq.com/connect/qrconnect?appid="
        .YDWX_WEIXIN_WEB_APP_ID."&redirect_uri={$redirect}&response_type=code&scope=snsapi_login&state={$state}#wechat_redirect");
    die;
}

if( ! @$_GET['code'] && @$_GET['state']){
    YDWXHook::do_hook(YDWXHook::AUTH_CANCEL);
    die;
}

$http = new YDHttp();
$info = json_decode($http->get("https://api.weixin.qq.com/sns/oauth2/access_token?appid="
        .YDWX_WEIXIN_WEB_APP_ID."&secret=".YDWX_WEIXIN_WEB_APP_SECRET."&code=".$_GET['code']."&grant_type=authorization_code"), true);

if( !@$info['openid']){
    YDWXHook::do_hook(YDWXHook::AUTH_FAIL, YDWXAuthFailResponse::errMsg($info['errmsg'], $info['errcode']));
    die;
}
$user = ydwx_sns_userinfo($info['access_token'], $info['openid']);
$user->state = $_GET['state'];
YDWXHook::do_hook(YDWXHook::AUTH_WEB_SUCCESS, $user);