<?php

/**
 * 上传文件, 账号需要认证
 * 
 * @param unknown $accessToken
 * @param unknown $type 图片（image）、语音（voice）、视频（video）和缩略图（thumb）
 * @return array  {"type":"TYPE","media_id":"MEDIA_ID","created_at":123456789}
 */
function uploadTempMeida($accessToken, $type, $media){
    if( ! WEIXIN_IS_AUTHED)return array();
    
    $http = new YDHttp();
    $user = json_decode(
            $http->post(WEIXIN_BASE_URL."media/upload?access_token={$accessToken}&type=$type", 
            array("media"=>$media) ,true), 
        true);
    return @$user['openid'] ? $user : array();
}

/**
 * 下载临时文件
 * 
 * @param unknown $accessToken
 * @param unknown $mediaid
 * @param string $isVideo
 * @return boolean|content
 */
function downloadTempMedia($accessToken, $mediaid, $isVideo=false){
    $http    = new YDHttp();
    $content = $http->get( ($isVideo ? WEIXIN_BASE_URL2 : WEIXIN_BASE_URL)."media/get?access_token={$accessToken}&media_id={$mediaid}");
    $info    = json_decode($content, true);
    if( array_key_exists("errcode", $info))return false;
    return $content;
}