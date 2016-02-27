<?php


/**
 * 查询群发消息发送状态【订阅号与服务号认证后均可用】
 * 开发者可通过该接口发送消息给指定用户，在手机端查看消息的样式和排版。
 * 为了满足第三方平台开发者的需求，在保留对openID预览能力的同时，增加了对指定微信号发送预览的能力，
 * 但该能力每日调用次数有限制（100次），请勿滥用。
 * @param String $accessToken;
 * @param String msg_id 消息id
 * @return String msg_status 消息发送状态
 * @see http://mp.weixin.qq.com/wiki/15/5380a4e6f02f2ffdc7981a8ed7a40753.html#.E9.A2.84.E8.A7.88.E6.8E.A5.E5.8F.A3.E3.80.90.E8.AE.A2.E9.98.85.E5.8F.B7.E4.B8.8E.E6.9C.8D.E5.8A.A1.E5.8F.B7.E8.AE.A4.E8.AF.81.E5.90.8E.E5.9D.87.E5.8F.AF.E7.94.A8.E3.80.91
 */
function ydwx_message_status($accessToken, $msg_id){
    if( ! YDWX_WEIXIN_IS_AUTHED) throw new YDWXException("认证后才查询群发消息发送状态");

    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."message/mass/get?access_token={$accessToken}",  
        ydwx_json_encode(array("msg_id"=>$msg_id)));
    $rst = new YDWXResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg);

    return $rst->msg_status;
}

/**
 * 预览接口【订阅号与服务号认证后均可用】
 * 开发者可通过该接口发送消息给指定用户，在手机端查看消息的样式和排版。
 * 为了满足第三方平台开发者的需求，在保留对openID预览能力的同时，增加了对指定微信号发送预览的能力，
 * 但该能力每日调用次数有限制（100次），请勿滥用。
 * @param String $accessToken;
 * @param YDWXMassPreviewRequest $arg;
 * @return String msg_id 预览消息id
 * @see http://mp.weixin.qq.com/wiki/15/5380a4e6f02f2ffdc7981a8ed7a40753.html#.E9.A2.84.E8.A7.88.E6.8E.A5.E5.8F.A3.E3.80.90.E8.AE.A2.E9.98.85.E5.8F.B7.E4.B8.8E.E6.9C.8D.E5.8A.A1.E5.8F.B7.E8.AE.A4.E8.AF.81.E5.90.8E.E5.9D.87.E5.8F.AF.E7.94.A8.E3.80.91
 */
function ydwx_message_preview($accessToken, YDWXMassPreviewRequest $arg){
    if( ! YDWX_WEIXIN_IS_AUTHED) throw new YDWXException("认证后才可用预览群发");
    
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."message/mass/preview?access_token={$accessToken}",  $arg->toJSONString());
    $rst = new YDWXMassResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg);
    
    return $rst->msg_id;
}

/**
 * 删除群发【订阅号与服务号认证后均可用】
 * 1、只有已经发送成功的消息才能删除
 * 2、删除消息是将消息的图文详情页失效，已经收到的用户，还是能在其本地看到消息卡片。
 * 3、删除群发消息只能删除图文消息和视频消息，其他类型的消息一经发送，无法删除。
 * 4、如果多次群发发送的是一个图文消息，那么删除其中一次群发，就会删除掉整个图文消息、导致所有群发都失效
 * @param String $accessToken;
 * @param String $messageid;
 * @return void
 * @see http://mp.weixin.qq.com/wiki/15/5380a4e6f02f2ffdc7981a8ed7a40753.html#.E5.88.A0.E9.99.A4.E7.BE.A4.E5.8F.91.E3.80.90.E8.AE.A2.E9.98.85.E5.8F.B7.E4.B8.8E.E6.9C.8D.E5.8A.A1.E5.8F.B7.E8.AE.A4.E8.AF.81.E5.90.8E.E5.9D.87.E5.8F.AF.E7.94.A8.E3.80.91
 */
function ydwx_message_delete($accessToken, $messageid){
    if( ! YDWX_WEIXIN_IS_AUTHED) throw new YDWXException("认证后才可用删除群发");
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."message/mass/delete?access_token={$accessToken}", 
        ydwx_json_encode(array("msg_id"=>$messageid)));
    $rst = new YDWXResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg);
}

/**
 * 企业号发送消息
 * @param YDWXQyMsgRequest $arg;
 * @return YDWXResponse
 * @see http://qydev.weixin.qq.com/wiki/index.php?title=%E6%B6%88%E6%81%AF%E7%B1%BB%E5%9E%8B%E5%8F%8A%E6%95%B0%E6%8D%AE%E6%A0%BC%E5%BC%8F
 */
function ydwx_qy_message_send($accessToken, YDWXQyMsgRequest $arg){

    if( YDWX_WEIXIN_ACCOUNT_TYPE != YDWX_WEIXIN_ACCOUNT_TYPE_CROP){
        throw new YDWXException("不是企业号");
    }

    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_QY_BASE_URL."message/send?access_token={$accessToken}", $arg->toJSONString());
    $info = new YDWXResponse($info);
    if($info->isSuccess())return $info;
    throw new YDWXException($info->errmsg, $info->errcode);
}

/**
 * 向微信回复消息
 *
 * @param YDWXAnswerMsg $msg
 */
function ydwx_answer_msg(YDWXAnswerMsg $msg){
    ob_start();
    if(YDWX_WEIXIN_COMPONENT_APP_ID){//第三方平台要加密
        $crypt = new WXBizMsgCrypt(YDWX_WEIXIN_COMPONENT_TOKEN, YDWX_WEIXIN_COMPONENT_ENCODING_AES_KEY, YDWX_WEIXIN_COMPONENT_APP_ID);
        $encryptMsg = "";
        $crypt->encryptMsg($msg->toXMLString(), time(), uniqid(), $encryptMsg);
        echo $encryptMsg;
    }else{
        echo $msg->toXMLString();
    }
    ob_end_flush();
}
/**
 * 从行业模板库选择模板到账号后台，获得模板ID
 *
 * @param String $accessToken
 * @param String $template_id 模板库中模板的编号，有“TM**”和“OPENTMTM**”等形式
 *
 * @return string 模板的id
 */
function ydwx_message_template_add($accessToken,  $template_id){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."template/api_add_template?access_token={$accessToken}",
    ydwx_json_encode(array("template_id_short"=>$template_id)));
    $rst = new YDWXResponse($info);
    if( ! $rst->isSuccess())throw new YDWXException($rst->errmsg);
    return $rst->template_id;
}
/**
 * 设置行业可在MP中完成，每月可修改行业1次，账号仅可使用所属行业中相关的模板，
 * 为方便第三方开发者，提供通过接口调用的方式来修改账号所属行业
 * 
 * @param String $accessToken
 * @param String $id1 见 YDWX_INDUSTRY_XX
 * @param String $id2 见 YDWX_INDUSTRY_XX
 * 
 * @return void
 */
function ydwx_message_template_set_industry($accessToken,  $id1, $id2){
    if( ! YDWX_WEIXIN_IS_AUTHED || YDWX_WEIXIN_ACCOUNT_TYPE != YDWX_WEIXIN_ACCOUNT_TYPE_SERVICE){
        throw new YDWXException("发送模板消息, 要求是认证的服务号");
    }
    
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."template/api_set_industry?access_token={$accessToken}",
    ydwx_json_encode(array("industry_id1"=>$id1, "industry_id2"=>$id2)));
    $rst = new YDWXResponse($info);
    if( ! $rst->isSuccess())throw new YDWXException($rst->errmsg);
}

/**
 * 发送模板消息, 要求是认证的服务号
 * 
 * @param unknown $accessToken
 * @param YDWXTemplateRequest $tpl
 * @return YDWXTemplateResponse
 */
function ydwx_message_template_send($accessToken,  YDWXTemplateRequest $tpl){
    if( ! YDWX_WEIXIN_IS_AUTHED || YDWX_WEIXIN_ACCOUNT_TYPE != YDWX_WEIXIN_ACCOUNT_TYPE_SERVICE){
        throw new YDWXException("发送模板消息, 要求是认证的服务号");
    }
    
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."message/template/send?access_token={$accessToken}",
    $tpl->toJSONString());
    $info = new YDWXTemplateResponse($info);
    if($info->isSuccess())return $info;
    throw new YDWXException($info->errmsg, $info->errcode);
}

/**
 * 根据openid群发消息, 要求认证
 * 
 * @see http://mp.weixin.qq.com/wiki/15/5380a4e6f02f2ffdc7981a8ed7a40753.html#.E6.A0.B9.E6.8D.AEOpenID.E5.88.97.E8.A1.A8.E7.BE.A4.E5.8F.91.E3.80.90.E8.AE.A2.E9.98.85.E5.8F.B7.E4.B8.8D.E5.8F.AF.E7.94.A8.EF.BC.8C.E6.9C.8D.E5.8A.A1.E5.8F.B7.E8.AE.A4.E8.AF.81.E5.90.8E.E5.8F.AF.E7.94.A8.E3.80.91
 * @param unknown $accessToken
 * @param YDWXMassRequest $arg
 * @return YDWXMassResponse
 */
function ydwx_message_send_by_openid($accessToken,  YDWXMassRequest $arg){

    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."message/mass/send?access_token={$accessToken}", 
        $arg->toJSONString());
    $info = new YDWXMassResponse($info);
    if($info->isSuccess())return $info;
    throw new YDWXException($info->errmsg, $info->errcode);
}

/**
 * 根据分组群发消息, 要求认证
 *
 * @see http://mp.weixin.qq.com/wiki/15/5380a4e6f02f2ffdc7981a8ed7a40753.html#.E6.A0.B9.E6.8D.AE.E5.88.86.E7.BB.84.E8.BF.9B.E8.A1.8C.E7.BE.A4.E5.8F.91.E3.80.90.E8.AE.A2.E9.98.85.E5.8F.B7.E4.B8.8E.E6.9C.8D.E5.8A.A1.E5.8F.B7.E8.AE.A4.E8.AF.81.E5.90.8E.E5.9D.87.E5.8F.AF.E7.94.A8.E3.80.91
 * @param unknown $accessToken
 * @param YDWXMassByGroupRequest $arg
 * @return YDWXMassResponse
 */
function ydwx_message_send_by_group($accessToken,  YDWXMassByGroupRequest $arg){
    $openids = (array)$openids;
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."message/mass/sendall?access_token={$accessToken}",
    $arg->toJSONString());
    $info = new YDWXMassResponse($info);
    if($info->isSuccess())return $info;
    throw new YDWXException($info->errmsg, $info->errcode);
}
