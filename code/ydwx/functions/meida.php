<?php

/**
 * 上传临时文件, 只对认证的公众号和第三方平台开放，文件会在3天后删除; 
 * 
 * @param unknown $accessToken
 * @param unknown $type 图片（image）、语音（voice）、视频（video）和缩略图（thumb）
 * @param unknown $media 文件绝对路径
 * @return string MEDIA_ID
 */
function ydwx_media_upload($accessToken, $type, $media){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."media/upload?access_token={$accessToken}&type=$type", 
            array("media"=>"@".$media) ,true);
    $msg  = new YDWXResponse($info); 
    if($msg->isSuccess()){
        return $msg->media_id; 
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 新增永久图文素材
 * 
 * @param unknown $accessToken
 * @param array $articles YDWXMpNewsMsg 组成的数组
 * @return mediaid
 * @throws YDWXException
 */
function ydwx_material_add_news($accessToken, array $articles){
    $array = array();
    foreach ($articles as $article){
        $array[] = $article->toArray();
    }
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."material/add_news?access_token={$accessToken}",
    ydwx_json_encode(array("articles"=>$array)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->media_id;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 上传图片得到URL，
 * 上传图文消息内的图片获取URL 
 * 请注意，本接口所上传的图片不占用公众号的素材库中图片数量的5000个的限制。
 * 图片仅支持jpg/png格式，大小必须在1MB以下。
 * @param unknown $accessToken
 * @param string $media 绝对路径
 * @return url
 * @throws YDWXException
 */
function ydwx_media_uploadimg($accessToken, $media){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."media/uploadimg?access_token={$accessToken}", 
            array("media"=>"@".$media) ,true);
    $msg  = new YDWXResponse($info); 
    if($msg->isSuccess()){
        return $msg->url; 
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 新增其他类型永久素材
 * 
 * @param unknown $accessToken
 * @param unknown $type 媒体文件类型，分别有图片（image）、语音（voice）和缩略图（thumb）
 * @param unknown $media
 * @throws YDWXException
 * @return YDWXMaterialResponse
 */
function ydwx_media_add_material($accessToken, $type, $media){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."material/add_material?access_token={$accessToken}&type=$type",
    array("media"=>"@".$media) ,true);

    $msg  = new YDWXMaterialResponse($info);

    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg);
}
/**
 * 新增视频类型永久素材
 *
 * @param unknown $accessToken
 * @param unknown $media
 * @param unknown $title 视频素材的标题
 * @param unknown $introduction 视频素材的描述
 * @throws YDWXException
 * @return YDWXMaterialResponse
 */
function ydwx_media_add_material_video($accessToken, $media, $title, $introduction){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."material/add_material?access_token={$accessToken}&type=video",
    array("media"=>"@".$media, 
            "description"=>ydwx_json_encode(array("title"=>$title, "introduction"=>$introduction))) ,true);
    $msg  = new YDWXMaterialResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 下载临时文件
 * 
 * @param unknown $accessToken
 * @param unknown $mediaid
 * @param string $isVideo
 * @return boolean|content
 */
function ydwx_media_get($accessToken, $mediaid, $isVideo=false){
    $http    = new YDHttp();
    $content = $http->get( ($isVideo ? YDWX_WEIXIN_NO_SSL_URL : YDWX_WEIXIN_BASE_URL)."media/get?access_token={$accessToken}&media_id={$mediaid}");
    $info    = json_decode($content, true);
    if( array_key_exists("errcode", $info))return false;
    return $content;
}