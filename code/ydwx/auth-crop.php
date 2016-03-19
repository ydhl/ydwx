<?php
/**
 * 企业号认证流程会得到用户的完整信息
 * 
 * 公众号微信内Web OAuth登陆，企业号；
 * 该页面可通过Redirect方式进行访问，或者直接在需要的地方include_once
 * 
 * 传入参数：back:授权后跳转页面，appid，
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

if($_GET['realappid']){//第三方平台代替无oauth权限的公众号进行oauth登录时使用，其值为公众号的appid，这时的$_GET['appid']为第三方平台appid
    $state .= ">".$_GET['realappid'];
}

$state = urlencode(base64_encode($state));

$redirect = YDWX_SITE_URL.'ydwx/auth-crop.php';
$appid  = YDWX_WEIXIN_CROP_ID;
$secret = YDWX_WEIXIN_CROP_SECRET;
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
//企业号返回的是code，可直接获取用户的信息.
$access_token = YDWXHook::do_hook(YDWXHook::GET_QY_ACCESS_TOKEN);
if($access_token){
    YDWXHook::do_hook(YDWXHook::AUTH_CROP_SUCCESS,  ydwx_crop_user_info($access_token, $_GET['code'], $_GET['state']));
}else{
    YDWXHook::do_hook(YDWXHook::AUTH_FAIL,   YDWXAuthFailResponse::errMsg("未取得access token"));
}

