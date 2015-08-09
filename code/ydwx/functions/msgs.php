<?php

/**
 * 发送模板消息, 要求是认证的服务号
 * 
 * @param unknown $accessToken
 * @param YDWXTemplate $tpl
 * @return YDWXTemplateResult
 */
function ydwx_message_template_send($accessToken,  YDWXTemplate $tpl){
    if( ! WEIXIN_IS_AUTHED || WEIXIN_ACCOUNT_TYPE != WEIXIN_ACCOUNT_SERVICE){
        throw new YDWXException("发送模板消息, 要求是认证的服务号");
    }
    
    $http = new YDHttp();
    $info = $http->post(WEIXIN_BASE_URL."message/template/send?access_token={$accessToken}",
    $tpl->toJSONString());
    return new YDWXTemplateResult($info);
}

/**
 * 发送模板消息, 要求是认证的服务号
 * 
 * @param unknown $accessToken
 * @param YDWXTemplate $tpl
 * @return YDWXMassResult
 */
function ydwx_message_mass_send($accessToken,  YDWXMsgArg $arg){
    $openids = (array)$openids;
    if( ! WEIXIN_IS_AUTHED || WEIXIN_ACCOUNT_TYPE != WEIXIN_ACCOUNT_SERVICE){
        throw new YDWXException("发送模板消息, 要求是认证的服务号");
    }
    
    $http = new YDHttp();
    $info = $http->post(WEIXIN_BASE_URL."message/mass/send?access_token={$accessToken}", 
        $arg->toJSONString());
    return new YDWXMassResult($info);
}