<?php
/**
 * 公众号微信内Web OAuth登陆，有两种情况
 * 一是公众号（订阅号、服务号）；一种是企业号
 * 该页面可通过Redirect方式进行访问，或者直接在需要的地方include_once
 * 该认证流程对于未关注用户不会得到完整用户信息,是静默授权的，用户无感知，非关注用户只能获得openid
 */

chdir(dirname(__FILE__));//把工作目录切换到文件所在目录

include_once dirname(__FILE__).'/__config__.php';

// state为交互时双方都会带着的get参数，用于做一些逻辑判断，如果没指定，则默认一个
if( ! @$_GET['state'] ){
    $state = "ydwx";
}

$redirect = YDWX_SITE_URL.'ydwx/auth.php';

$appid  = YDWX_WEIXIN_ACCOUNT_TYPE == YDWX_WEIXIN_ACCOUNT_TYPE_CROP ? YDWX_WEIXIN_CROP_ID : YDWX_WEIXIN_APP_ID;
$secret = YDWX_WEIXIN_ACCOUNT_TYPE == YDWX_WEIXIN_ACCOUNT_TYPE_CROP ? YDWX_WEIXIN_CROP_SECRET : YDWX_WEIXIN_APP_SECRET;

//第一步，引导用户到微信页面授权
if( ! @$_GET['code'] &&  ! @$_GET['state']){
    ob_clean();
    header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid="
        .$appid."&redirect_uri={$redirect}&response_type=code&scope=snsapi_base&state={$state}#wechat_redirect");
    die;
}

//第二步，用户授权后返回，获取授权用户信息
if (YDWX_WEIXIN_ACCOUNT_TYPE != YDWX_WEIXIN_ACCOUNT_TYPE_CROP){
    $http = new YDHttp();
    $info = json_decode($http->get("https://api.weixin.qq.com/sns/oauth2/access_token?appid="
            .$appid."&secret=".$secret."&code=".$_GET['code']."&grant_type=authorization_code"), true);
    
    if( !@$info['openid']){
        YDWXHook::do_hook(YDWXHook::AUTH_FAIL, YDWXAuthFailResponse::errMsg($info['errmsg'], $info['errcode']));
        die;
    }
    
    $access_token = YDWXHook::do_hook(YDWXHook::GET_ACCESS_TOKEN);
    if($access_token){
        YDWXHook::do_hook(YDWXHook::AUTH_INAPP_SUCCESS, ydwx_user_info($access_token,     $info['openid'], $_GET['state']));
    }else{
        $user = new YDWXSubscribeUser();
        $user->openid = $info['openid'];
        $user->state  = $_GET['state'];
        YDWXHook::do_hook(YDWXHook::AUTH_INAPP_SUCCESS, $user);
    }
}else{
    //企业号返回的是code，可直接获取用户的信息
    $access_token = YDWXHook::do_hook(YDWXHook::GET_ACCESS_TOKEN);
    if($access_token){
        YDWXHook::do_hook(YDWXHook::AUTH_CROP_SUCCESS,  ydwx_crop_user_info($access_token, $_GET['code'], $_GET['state']));
    }else{
        YDWXHook::do_hook(YDWXHook::AUTH_FAIL,   YDWXAuthFailResponse::errMsg("未取得access token"));
    }
}
