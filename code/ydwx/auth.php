<?php
/**
 * 该认证流程会得到用户的完整信息
 * 
 * 公众号微信内Web OAuth登陆，
 * 公众号（订阅号、服务号）；
 * 该页面可通过Redirect方式进行访问，或者直接在需要的地方include_once
 * 
 * 传入参数：back:授权后跳转页面，
 * 地址上不能有state参数，有将导致失败；state为ydwx和微信交互用，不能主动传入
 */

chdir(dirname(__FILE__));//把工作目录切换到文件所在目录

include_once dirname(__FILE__).'/__config__.php';

// state为交互时双方都会带着的get参数，用于做一些逻辑判断，如果没指定，则默认一个
if( ! @$_GET['back'] ){
    $state = "ydwx";
}else{
    $state = $_GET['back'];
}

$state = urlencode(base64_encode($state));

$redirect = YDWX_SITE_URL.'ydwx/auth.php';

$appid  = YDWX_WEIXIN_APP_ID;
$secret = YDWX_WEIXIN_APP_SECRET;
$authorize_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid="
		.$appid."&redirect_uri=".urlencode($redirect)."&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";

$access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid="
		.$appid."&secret=".$secret."&code=%s&grant_type=authorization_code";

//第一步，引导用户到微信页面授权
if( ! @$_GET['code'] &&  ! @$_GET['state']){
    ob_clean();
    header("Location: {$authorize_url}");
    die;
}

//用户取消授权后返回本页面
if( ! @$_GET['code'] && @$_GET['state']){
    YDWXHook::do_hook(YDWXHook::AUTH_CANCEL);
    die;
}

//第二步，用户授权后返回，获取授权用户信息
$http = new YDHttp();
$accesstoken = $http->get(sprintf($access_token_url, $_GET['code']));
$info = json_decode($accesstoken, true);
    
if( !@$info['openid']){
    YDWXHook::do_hook(YDWXHook::AUTH_FAIL, YDWXAuthFailResponse::errMsg($info['errmsg'].$accesstoken, $info['errcode']));
    die;
}
    
try{
    $state = base64_decode($_GET['state']);
    $user = ydwx_sns_userinfo($info['access_token'], $info['openid']);
    $user->state = $state;
    YDWXHook::do_hook(YDWXHook::AUTH_INAPP_SUCCESS, $user);
}catch (\Exception $e){
    YDWXHook::do_hook(YDWXHook::AUTH_FAIL, YDWXAuthFailResponse::errMsg($e->getMessage(), $e->getCode()));
}
die();
