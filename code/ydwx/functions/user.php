<?php

/**
 * 企业号微信内应用获取用户信息
 *
 * @param unknown $accessToken 企业号的access_token
 * @param unknown $code 用户同意后得到的code
 * @return YDWXOAuthCropUser
 * @throws YDWXException
 */
function ydwx_crop_user_info($accessToken, $code){
    $http = new YDHttp();
    $user = $http->get(YDWX_WEIXIN_QY_BASE_URL."user/getuserinfo?access_token={$accessToken}&code=$code&lang=zh_CN");
    $user = new YDWXOAuthCropUser($user);
    if($user->isSuccess()){
        return $user;
    }
    throw new YDWXException($user->errmsg);
}
/**
 * 微信内应用获取用户信息.用于用户关注了公众号或者与公众号产生了交互（菜单点击、消息会话），其他情况可能只能得到openid
 * 要求公众必须认证
 * @param unknown $accessToken oauth流程得到的token或者是ydwx/refresh.php定时刷新下来的access token
 * @param unknown $openid
 * @throws YDWXException 
 * @return YDWXSubscribeUser
 */
function ydwx_user_info($accessToken, $openid){
    $http = new YDHttp();
    $userinfo = $http->get(YDWX_WEIXIN_BASE_URL."user/info?access_token={$accessToken}&openid=$openid&lang=zh_CN");
    $user = new YDWXSubscribeUser($userinfo);
    if($user->isSuccess()){
        return $user;
    }
    throw new YDWXException($user->errmsg);
}

/**
 * 网站应用获取用户信息
 * @param unknown $accessToken 注意，这里的access token是oauth认证第二步微信返回的
 * @param unknown $openid
 * @throws YDWXException
 * @return YDWXOAuthUser
 */
function ydwx_sns_userinfo($accessToken, $openid){
    $http = new YDHttp();
    $user = $http->get(YDWX_WEIXIN_WEB_BASE_URL."userinfo?access_token={$accessToken}&openid=$openid&lang=zh_CN");
    $user = new YDWXOAuthUser($user);
    if($user->isSuccess()){
        return $user;
    }
    throw new YDWXException($user->errmsg);
}