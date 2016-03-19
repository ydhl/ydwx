<?php
/**
 * 该认证流程对于未关注用户不会得到完整用户信息,是静默授权的，用户无感知，非关注用户只能获得openid
 * 
 * 
 * 第三方平台代托管的公众号授权，这时第一次访问get参数需有apppid；这是托管的公众号appid，在公众号托管绑定后获得
 *  
 * 该页面可通过Redirect方式进行访问，或者直接在需要的地方include_once
 * 
 * 传入参数：back:授权后跳转页面，appid，第三方平台代托管的公众号appi
 * 地址上不能有state参数，有将导致失败；state为ydwx和微信交互用，不能主动传入
 */

chdir(dirname(__FILE__));//把工作目录切换到文件所在目录

include_once dirname(__FILE__).'/__config__.php';

// state为交互时双方都会带着的get参数，用于做一些逻辑判断，如果没指定，则默认一个
if( ! @$_GET['back'] ){
    $state = "ydwx";
}else{
    $state = urlencode($_GET['back']);
}

$redirect = YDWX_SITE_URL.'ydwx/auth.php';

$appid  = $_GET['appid'];
$authorize_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid="
            .$appid."&redirect_uri=".urlencode($redirect)."&response_type=code&scope=snsapi_base&state={$state}&component_appid="
            .YDWX_WEIXIN_COMPONENT_APP_ID."#wechat_redirect";

$access_token_url = "https://api.weixin.qq.com/sns/oauth2/component/access_token?appid="
            .$appid."&code=%s&grant_type=authorization_code&component_appid=".YDWX_WEIXIN_COMPONENT_APP_ID
            ."&component_access_token=".YDWXHook::do_hook(YDWXHook::GET_AGENT_ACCESS_TOKEN);



//第一步，引导用户到微信页面授权
if( ! @$_GET['code'] &&  ! @$_GET['state']){
    ob_clean();
    header("Location: {$authorize_url}");
    die;
}

//第二步，用户授权后返回，获取授权用户信息

$http = new YDHttp();
$info = json_decode($http->get(sprintf($access_token_url, $_GET['code'])), true);
    
if( !@$info['openid']){
    YDWXHook::do_hook(YDWXHook::AUTH_FAIL, YDWXAuthFailResponse::errMsg($info['errmsg'], $info['errcode']));
    die;
}
    
$access_token = YDWXHook::do_hook(YDWXHook::GET_HOST_ACCESS_TOKEN, $appid);
   
    
if($access_token){
	try{
		$user = ydwx_user_info($access_token,     $info['openid']);
	}catch (\Exception $e){
		$user = new YDWXSubscribeUser();
		$user->openid  = $info['openid'];
	}
}else{
	$user = new YDWXSubscribeUser();
	$user->openid  = $info['openid'];
}

$user->appid  = $appid;
$user->state  = $_GET['state'];
YDWXHook::do_hook(YDWXHook::AUTH_INAPP_SUCCESS, $user);
die();