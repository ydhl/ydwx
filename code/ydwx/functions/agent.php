<?php
/**
 * 公众号第三方平台生成授权连接
 */
function ydwx_agent_create_preauthcode(){
    $accessToken = YDWXHook::do_hook(YDWXHook::GET_ACCESS_TOKEN);
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."component/api_create_preauthcode?component_access_token={$accessToken}",
        ydwx_json_encode(array("component_appid"=>YDWX_WEIXIN_COMPONENT_APP_ID)));
    $msg  = new YDWXResponse($info);
    if( ! $msg->isSuccess()){
        return "#";
    }
    
    return "https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid="
            .YDWX_WEIXIN_COMPONENT_APP_ID."&pre_auth_code=".$msg->pre_auth_code
            ."&redirect_uri=".YDWX_SITE_URL."ydwx/agentauth.php";
}

/**
 * 得到授权码后，第三方平台方可以使用授权码换取授权公众号的授权信息，再通过公众号授权信息调用公众号相关API
 * @param unknown $auth_code
 * @return YDWXAgentAuthInfo
 */
function ydwx_agent_query_auth($auth_code){
    $accessToken = YDWXHook::do_hook(YDWXHook::GET_ACCESS_TOKEN);
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."component/api_query_auth?component_access_token={$accessToken}",
        ydwx_json_encode(array(
            "component_appid"=>YDWX_WEIXIN_COMPONENT_APP_ID,
            "authorization_code"=>$auth_code)));
    $msg  = new YDWXAgentAuthInfo($info);
    if( $msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 或者授权的公众号信息
 * 
 * @param unknown $appid
 * @throws YDWXException
 * @return YDWXAgentAuthInfo
 */
function ydwx_agent_get_auth_account($appid){
    $accessToken = YDWXHook::do_hook(YDWXHook::GET_ACCESS_TOKEN);
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."component/api_get_authorizer_info?component_access_token={$accessToken}",
    ydwx_json_encode(array(
            "component_appid" =>YDWX_WEIXIN_COMPONENT_APP_ID,
            "authorizer_appid"=>$appid)));
    $msg  = new YDWXAgentAuthInfo($info);
    if( $msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 获取第三方平台access_token
 * 
 * @param unknown $verify_ticket 是微信没10分钟推送得到，需要注册hook
 * @throws YDWXException
 * @return YDWXAccessTokenResponse
 */
function ydwx_agent_access_token($verify_ticket){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."component/api_component_token",
    ydwx_json_encode(array(
            "component_appid" =>YDWX_WEIXIN_COMPONENT_APP_ID,
            "component_appsecret"=>YDWX_WEIXIN_COMPONENT_APP_SECRET,
            "component_verify_ticket"=>$verify_ticket)));
    $msg  = new YDWXAccessTokenResponse($info);
    $msg->access_token = @$msg->rawData['component_access_token'];
    if( $msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 属性授权的公众号的令牌
 * @param 授权公众号的 appid
 * @param 授权公众号的 refreshToken
 * @return string access token
 */
function ydwx_agent_refresh_auth_token($appid, $refreshToken){
    $accessToken = YDWXHook::do_hook(YDWXHook::GET_ACCESS_TOKEN);
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."component/api_authorizer_token?component_access_token={$accessToken}",
    ydwx_json_encode(array(
	            "component_appid"   => YDWX_WEIXIN_COMPONENT_APP_ID,
	            "authorizer_appid"  => $appid,
	            "authorizer_refresh_token"=>$refreshToken
	    )));
    $msg  = new YDWXAuthorizerTokenResponse($info);
    if( $msg->isSuccess()){
        return YDWXAuthorizerTokenResponse;
    }
    throw new YDWXException($msg->errmsg);
}