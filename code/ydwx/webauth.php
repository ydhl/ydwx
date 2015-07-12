<?php
/**
 * 网站进行微信OAuth登陆
 */
include_once dirname(__FILE__).'/libs/wx.php';

if( ! $state){
    $state = "fromydwx";
} 

$redirect = SITE_URI.'ydwx/webauth.php';

if( ! @$_GET['code'] &&  ! @$_GET['state']){
    ob_clean();
    header("Location: https://open.weixin.qq.com/connect/qrconnect?appid="
        .WEIXIN_WEB_APP_ID."&redirect_uri={$redirect}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect");
    die;
}

if( ! @$_GET['code'] && @$_GET['state']){
    YDHook::do_hook(WXHooks::AUTH_CANCEL);
    die;
}

$http = new YDHttp();
$info = json_decode($http->get("https://api.weixin.qq.com/sns/oauth2/access_token?appid="
        .WEIXIN_WEB_APP_ID."&secret=".WEIXIN_WEB_APP_SECRET."&code=".$_GET['code']."&grant_type=authorization_code"), true);

if( !@$info['openid']){
    YDHook::do_hook(WXHooks::AUTH_FAIL, $info);
    die;
}
$info['state'] = $_GET['state'];

YDHook::do_hook(WXHooks::AUTH_SUCCESS, $info);