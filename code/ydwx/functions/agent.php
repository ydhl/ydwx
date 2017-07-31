<?php

/**
 * 公众号第三方平台生成授权连接
 * @param $queryString 参数，如a=b&c=d，授权后返回，在AUTH_AGENT_SUCCESS回调中可通过YDWXAgentAuthInfo->query获取
 */
function ydwx_agent_create_preauthcode($queryString=""){
    $accessToken = YDWXHook::do_hook(YDWXHook::GET_AGENT_ACCESS_TOKEN);
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."component/api_create_preauthcode?component_access_token={$accessToken}",
        ydwx_json_encode(array("component_appid"=>YDWX_WEIXIN_COMPONENT_APP_ID)));
    $msg  = new YDWXResponse($info);
    if( ! $msg->isSuccess()){
        throw new YDWXException($msg->errmsg.$msg->errcode, $msg->errcode);
    }
    $queryString = trim($queryString,"?");
    return "https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid="
            .YDWX_WEIXIN_COMPONENT_APP_ID."&pre_auth_code=".$msg->pre_auth_code
            ."&redirect_uri=".urlencode(YDWX_SITE_URL."ydwx/agentauth.php".($queryString ? "?{$queryString}" :""));
}


/**
 * 得到授权码后，第三方平台方可以使用授权码换取授权公众号的授权信息，再通过公众号授权信息调用公众号相关API
 * @param unknown $auth_code
 * @return YDWXAgentAuthInfo
 */
function ydwx_agent_query_auth($auth_code){
    $accessToken = YDWXHook::do_hook(YDWXHook::GET_AGENT_ACCESS_TOKEN);
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
 * 获取授权的公众号信息
 * 
 * @param unknown $appid
 * @throws YDWXException
 * @return YDWXAgentAuthInfo
 */
function ydwx_agent_get_auth_account($appid){
    $accessToken = YDWXHook::do_hook(YDWXHook::GET_AGENT_ACCESS_TOKEN);
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."component/api_get_authorizer_info?component_access_token={$accessToken}",
    ydwx_json_encode(array(
            "component_appid" =>YDWX_WEIXIN_COMPONENT_APP_ID,
            "authorizer_appid"=>$appid)));
    $msg  = new YDWXAgentAuthUser($info);
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
    $msg->access_token = $msg->component_access_token;
    if( $msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 刷新授权的公众号的令牌
 * @param 授权公众号的 appid
 * @param 授权公众号的 refreshToken
 * @return string access token
 */
function ydwx_agent_refresh_auth_token($appid, $refreshToken){
    $accessToken = YDWXHook::do_hook(YDWXHook::GET_AGENT_ACCESS_TOKEN);
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."component/api_authorizer_token?component_access_token={$accessToken}",
    ydwx_json_encode(array(
	            "component_appid"   => YDWX_WEIXIN_COMPONENT_APP_ID,
	            "authorizer_appid"  => $appid,
	            "authorizer_refresh_token"=>$refreshToken
	    )));
    $msg  = new YDWXAuthorizerTokenResponse($info);
    if( $msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 该 API 用于第三方平台确认接受公众号将某权限集高级权限的授权
 * @param unknown $appid
 * @param unknown $funcscope_category_id 功能集合，见YDWX_FUNC_XX常量
 * @param boolean $confirm true 确认 false取消
 * @throws YDWXException
 * @return boolean
 */
function ydwx_agent_confirm_authorization($appid, $funcscope_category_id, $confirm){
    $accessToken = YDWXHook::do_hook(YDWXHook::GET_AGENT_ACCESS_TOKEN);
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."component/api_confirm_authorization?component_access_token={$accessToken}",
    ydwx_json_encode(array(
            "component_appid"   => YDWX_WEIXIN_COMPONENT_APP_ID,
            "authorizer_appid"  => $appid,
            "funcscope_category_id"=>$funcscope_category_id,
            "confirm_value"     => $confirm ? 1 :2
    )));
    $msg  = new YDWXResponse($info);
    if( $msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg);
}