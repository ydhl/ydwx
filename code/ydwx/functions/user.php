<?php

/**
 * 微信内应用获取用户信息
 * 
 * @param unknown $accessToken
 * @param unknown $openid
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
 * @return multitype:|Ambigous <multitype:, mixed>
 */
function getWebUserInfo($accessToken, $openid){
    $http = new YDHttp();
    $user = json_decode($http->get(WEIXIN_WEB_BASE_URL."userinfo?access_token={$accessToken}&openid=$openid&lang=zh_CN"), true);
    return @$user['openid'] ? $user : array();
}