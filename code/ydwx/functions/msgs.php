<?php

/**
 * 发送模板消息, 要求是认证的服务号
 * 
 * @param unknown $accessToken
 * @param YDWXTemplateRequest $tpl
 * @return YDWXTemplateSendResponse
 */
function ydwx_message_template_send($accessToken,  YDWXTemplateRequest $tpl){
    if( ! WEIXIN_IS_AUTHED || WEIXIN_ACCOUNT_TYPE != WEIXIN_ACCOUNT_SERVICE){
        throw new YDWXException("发送模板消息, 要求是认证的服务号");
    }
    
    $http = new YDHttp();
    $info = $http->post(WEIXIN_BASE_URL."message/template/send?access_token={$accessToken}",
    $tpl->toJSONString());
    return new YDWXTemplateSendResponse($info);
}

/**
 * 根据openid群发消息, 要求认证
 * 
 * @see http://mp.weixin.qq.com/wiki/15/5380a4e6f02f2ffdc7981a8ed7a40753.html#.E6.A0.B9.E6.8D.AEOpenID.E5.88.97.E8.A1.A8.E7.BE.A4.E5.8F.91.E3.80.90.E8.AE.A2.E9.98.85.E5.8F.B7.E4.B8.8D.E5.8F.AF.E7.94.A8.EF.BC.8C.E6.9C.8D.E5.8A.A1.E5.8F.B7.E8.AE.A4.E8.AF.81.E5.90.8E.E5.8F.AF.E7.94.A8.E3.80.91
 * @param unknown $accessToken
 * @param YDWXMassByOpenIdRequest $arg
 * @return YDWXMassSendResponse
 */
function ydwx_message_send_by_openid($accessToken,  YDWXMassByOpenIdRequest $arg){
    $openids = (array)$openids;
    if( ! WEIXIN_IS_AUTHED){
        throw new YDWXException("群发模板消息, 要求先认证");
    }
    
    $http = new YDHttp();
    $info = $http->post(WEIXIN_BASE_URL."message/mass/send?access_token={$accessToken}", 
        $arg->toJSONString());
    return new YDWXMassSendResponse($info);
}

/**
 * 根据分组群发消息, 要求认证
 *
 * @see http://mp.weixin.qq.com/wiki/15/5380a4e6f02f2ffdc7981a8ed7a40753.html#.E6.A0.B9.E6.8D.AE.E5.88.86.E7.BB.84.E8.BF.9B.E8.A1.8C.E7.BE.A4.E5.8F.91.E3.80.90.E8.AE.A2.E9.98.85.E5.8F.B7.E4.B8.8E.E6.9C.8D.E5.8A.A1.E5.8F.B7.E8.AE.A4.E8.AF.81.E5.90.8E.E5.9D.87.E5.8F.AF.E7.94.A8.E3.80.91
 * @param unknown $accessToken
 * @param YDWXMassByGroupRequest $arg
 * @return YDWXMassSendResponse
 */
function ydwx_message_send_by_group($accessToken,  YDWXMassByGroupRequest $arg){
    $openids = (array)$openids;
    if( ! WEIXIN_IS_AUTHED){
        throw new YDWXException("群发模板消息, 要求先认证");
    }

    $http = new YDHttp();
    $info = $http->post(WEIXIN_BASE_URL."message/mass/sendall?access_token={$accessToken}",
    $arg->toJSONString());
    return new YDWXMassSendResponse($info);
}