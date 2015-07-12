<?php
/**
 * 公众号微信OAuth登陆
 */
include_once dirname(__FILE__).'/libs/wx.php';

if( ! $state){
    $state = "fromydwx";
} 

$redirect = SITE_URI.'ydwx/auth.php';

if( ! @$_GET['code'] &&  ! @$_GET['state']){
    ob_clean();
    header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid="
        .WEIXIN_APP_ID."&redirect_uri={$redirect}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect");
    die;
}

if( ! @$_GET['code'] && @$_GET['state']){
    YDHook::do_hook(WXHooks::AUTH_CANCEL);
    die;
}

$http = new YDHttp();
$info = json_decode($http->get("https://api.weixin.qq.com/sns/oauth2/access_token?appid="
        .WEIXIN_APP_ID."&secret=".WEIXIN_APP_SECRET."&code=".$_GET['code']."&grant_type=authorization_code"), true);

if( !@$info['openid']){
    YDHook::do_hook(WXHooks::AUTH_FAIL);
    die;
}
$info['state'] = $_GET['state'];

YDHook::do_hook(WXHooks::AUTH_SUCCESS, $info);