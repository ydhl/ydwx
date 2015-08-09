<?php
/**
 * 企业号微信内应用获取用户信息
 *
 * @param unknown $accessToken 企业号的access_token
 * @param unknown $code 用户同意后得到的code
 * @return YDWXOAuthCropUser
 */
function ydwx_crop_user_info($accessToken, $code, $state){
    if( ! WEIXIN_ACCOUNT_TYPE != WEIXIN_ACCOUNT_CROP){
        throw new YDWXException("该方法只有企业号才能调用");
    }
    
    $http = new YDHttp();
    $user = $http->get(WEIXIN_QY_BASE_URL."user/getuserinfo?access_token={$accessToken}&code=$code&lang=zh_CN");
    $user = new YDWXOAuthCropUser($user);
    $user->state = $state;
    return $user;
}
/**
 * 微信内应用获取用户信息.用于用户关注了公众号或者与公众号产生了交互（菜单点击、消息会话），其他情况可能只能得到openid
 * 要求公众必须认证
 * @param unknown $accessToken oauth流程得到的token或者是ydwx/refresh.php定时刷新下来的access token
 * @param unknown $openid
 * 
 * @return YDWXOAuthUser
 */
function ydwx_user_info($accessToken, $openid, $state){
    if( ! WEIXIN_IS_AUTHED){
        throw new YDWXException("公众号未认证，无法获取用户信息");
    }
    $http = new YDHttp();
    $userinfo = $http->get(WEIXIN_BASE_URL."user/info?access_token={$accessToken}&openid=$openid&lang=zh_CN");
    $user = new YDWXOAuthUser($userinfo);
    $user->state = $state;
    return $user;
}

/**
 * 网站应用获取用户信息
 * @param unknown $accessToken 注意，这里的access token是oauth认证第二步微信返回的
 * @param unknown $openid
 *
 * @return YDWXOAuthSnsUser
 */
function ydwx_sns_userinfo($accessToken, $openid, $state){
    $http = new YDHttp();
    $user = $http->get(WEIXIN_WEB_BASE_URL."userinfo?access_token={$accessToken}&openid=$openid&lang=zh_CN");
    $user = new YDWXOAuthSnsUser($user);
    $user->state = $state;
    return $user;
}