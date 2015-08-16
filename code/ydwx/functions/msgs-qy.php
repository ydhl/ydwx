<?php

/**
 * 企业号发送消息
 * @param YDWXQyMsgRequest $arg;
 * @return YDWXResponse
 * @see http://qydev.weixin.qq.com/wiki/index.php?title=%E6%B6%88%E6%81%AF%E7%B1%BB%E5%9E%8B%E5%8F%8A%E6%95%B0%E6%8D%AE%E6%A0%BC%E5%BC%8F
 */
function ydwx_qy_message_send($accessToken, YDWXQyMsgRequest $arg){

    if( WEIXIN_ACCOUNT_TYPE != WEIXIN_ACCOUNT_CROP){
        throw new YDWXException("不是企业号");
    }
    
    $http = new YDHttp();
    $info = $http->post(WEIXIN_QY_BASE_URL."message/send?access_token={$accessToken}", $arg->toJSONString());
    return new YDWXResponse($info);
}