<?php
/**
 * 企业号微信内应用获取用户信息
 *
 * @param unknown $accessToken 企业号的access_token
 * @param unknown $code 用户同意后得到的code
 * @return array(UserId=>"该用户在企业号后台的账号","OpenId"=>"非企业成员时返回openid", DeviceId=>"手机设备号") 注意大小写
 */
function getCropUserInfo($accessToken, $code){
    $http = new YDHttp();
    $user = json_decode($http->get(WEIXIN_QY_BASE_URL."user/getuserinfo?access_token={$accessToken}&code=$code&lang=zh_CN"), true);
    return ! @$user['errcode'] ? $user : array();
}
/**
 * 微信内应用获取用户信息
 * @param unknown $accessToken 注意，这里的access token为微信分配给app的，也就是ydwx/refresh.php定时刷新下来的access token
 * @param unknown $openid
 * 
 * @return multitype:|Ambigous <multitype:, mixed>
 */
function getUserInfo($accessToken, $openid){
    if( ! WEIXIN_IS_AUTHED)return array();
    $http = new YDHttp();
    $user = json_decode($http->get(WEIXIN_BASE_URL."user/info?access_token={$accessToken}&openid=$openid&lang=zh_CN"), true);
    return @$user['openid'] ? $user : array();
}

/**
 * 网站应用获取用户信息
 * @param unknown $accessToken 注意，这里的access token是oauth认证第二步微信返回的
 * @param unknown $openid
 *
 * @return multitype:|Ambigous <multitype:, mixed>
 */
function getWebUserInfo($accessToken, $openid){
    $http = new YDHttp();
    $user = json_decode($http->get(WEIXIN_WEB_BASE_URL."userinfo?access_token={$accessToken}&openid=$openid&lang=zh_CN"), true);
    return @$user['openid'] ? $user : array();
}