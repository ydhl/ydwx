<?php
use app\home\Option_Model;
/**
 * 公众号微信OAuth登陆
 */
include_once dirname(__FILE__).'/libs/wx.php';

if( ! $state){
    $state = "fromydwx";
} 

$redirect = SITE_URI.'ydwx/auth.php';

$appid  = WEIXIN_ACCOUNT_TYPE == WEIXIN_ACCOUNT_CROP ? WEIXIN_CROP_ID : WEIXIN_APP_ID;
$secret = WEIXIN_ACCOUNT_TYPE == WEIXIN_ACCOUNT_CROP ? WEIXIN_CROP_SECRET : WEIXIN_APP_SECRET;

if( ! @$_GET['code'] &&  ! @$_GET['state']){
    ob_clean();
    header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid="
        .$appid."&redirect_uri={$redirect}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect");
    die;
}

if( ! @$_GET['code'] && @$_GET['state']){
    YDHook::do_hook(WXHooks::AUTH_CANCEL);
    die;
}

//企业号可直接获取用户的信息
if (WEIXIN_ACCOUNT_TYPE != WEIXIN_ACCOUNT_CROP){
    $http = new YDHttp();
    $info = json_decode($http->get("https://api.weixin.qq.com/sns/oauth2/access_token?appid="
            .$appid."&secret=".$secret."&code=".$_GET['code']."&grant_type=authorization_code"), true);
    
    if( !@$info['openid']){
        YDHook::do_hook(WXHooks::AUTH_FAIL, $info);
        die;
    }
    $info['state']       = $_GET['state'];
    
    YDHook::do_hook(WXHooks::AUTH_INAPP_SUCCESS, getUserInfo($info['access_token'],     $info['openid']));
}else{
    $access_token = YDHook::do_hook(WXHooks::GET_ACCESS_TOKEN);
    if($access_token){
        YDHook::do_hook(WXHooks::AUTH_CROP_SUCCESS,  getCropUserInfo($access_token, $_GET['code']));
    }else{
        YDHook::do_hook(WXHooks::AUTH_FAIL,   array());
    }
}
