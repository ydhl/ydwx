<?php
/**
 * 开发者 协助 制券 接口（子商户有公众号模式）
 */


/**
 * 母商户资质申请接口是第三方平台用以申请开发者协助制券能力，并提交自身资质资料
 * 的上传接口，只有上传相关资质，并审核通过后才可代名下子商户提交资质。
 * 母商户提交资质包括：注册资本、营业执照（扫描件）、税务登记证（扫描件）、上季度缴税清单（扫描件）；
 * 母商户必须先上传相应扫描件获取 media_id 后，传入 media_id。ydwx_media_upload 上传（传入第三方平台的access token）
 * 同一个 appid 的申请，仅当驳回时可以再次提交，审核中和审核通过时不可重复提交。"
 * @param unknown $accessToken 第三方平台的access token
 * @param YDWXCardAgentQualificationRequest $request
 * @throws YDWXException
 * @return boolean
 */
function ydwx_card_upload_agent_qualification($accessToken, YDWXCardAgentQualificationRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."component/upload_card_agent_qualification?access_token={$accessToken}",
    $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($request->toJSONString().$msg->errmsg, $msg->errcode);
}

/**
 * 该接口用于查询母商户资质审核的结果，审核通过后才能用接口继续提交子商户资质。
 * @param unknown $accessTokent  第三方平台的access token
 * @throws YDWXException
 * @return YDWX_CARD_CHECK_AGENT_QUALIFICATION_XX常量
 */
function ydwx_card_check_agent_qualification($accessToken){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL."component/check_card_agent_qualification?access_token={$accessToken}");
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->result;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 母商户（第三方平台）申请获得开发者协助制券能力后，才可提交名下子商户的资质。
 * 子商户资质审核通过后，才可进行后续的授权流程。
 * 子商户的资质包括：商户名称、商户 logo（图片）、卡券类目、AppID、营业执照或个
 * 体户经营执照（扫描件）、授权协议（扫描件）。
 * 注意：
 * 1、 请用母商户（第三方平台）的账号提交子商户资料。
 * 2、 母商户必须先上传子商户的相应扫描件获取 media_id 后，传入 media_id。ydwx_media_upload接口（传入第三方平台的access token）
 * 3、 母商户必须先通过卡券类目查询接口获取卡券实时对外开放的一级、二级类目 ID，传入类目 ID。
 * 4、 商户名称在 12 个汉字长度内。
 * 5、同一个 appid 的申请，仅当驳回时可再次提交，审核中和审核通过时不可重复提交。
 * 
 * @param unknown $accessTokent  第三方平台的access token
 * @param YDWXCardMerchantQualificationRequest $request
 * @throws YDWXException
 */
function ydwx_card_upload_mpmerchant_qualification($accessToken,YDWXCardMerchantQualificationRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."component/upload_card_merchant_qualification?access_token={$accessToken}",
    $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 该接口用于查询子商户资质审核的结果，审核通过后才能进行后续授权流程。注意，用母商户去调用接口，但接口内传入的是子商户的 appid。
 * @param unknown $accessTokent  第三方平台的access token
 * @param unknown $appid
 * @throws YDWXException
 * @return YDWX_CARD_CHECK_AGENT_QUALIFICATION_XX常量
 */
function ydwx_card_check_mpmerchant_qualification($accessToken, $appid){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."component/check_card_merchant_qualification?access_token={$accessToken}",
    ydwx_json_encode(array("appid"=>$appid)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->result;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 通过指定的子商户 appid，拉取该子商户的基础信息。
 * 注意，用母商户去调用接口，但接口内传入的是子商户的 appid。
 * 
 * @param unknown $accessTokent  第三方平台的access token
 * @param unknown $appid
 * @throws YDWXException
 * @return YDWXCardMPMerchantResponse
 */
function ydwx_card_get_mpmerchant($accessToken, $appid){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."component/get_card_merchant?access_token={$accessToken}",
    ydwx_json_encode(array("appid"=>$appid)));
    $msg  = new YDWXCardMPMerchantResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 母商户可以通过该接口批量拉取子商户的相关信息，一次调用最多拉取 100  个子商户
 * 的信息，可以通过多次拉去满足不同的查询需求。
 * 
 * @param unknown $accessTokent  第三方平台的access token
 * @throws YDWXException
 * @return YDWXCardMPMerchantBatchGetResponse
 */
function ydwx_card_batchget_mpmerchant($accessToken, $next_get){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."component/batchget_card_merchant?access_token={$accessToken}"
    ,ydwx_json_encode(array("next_get"=>$next_get)));
    $msg  = new YDWXCardMPMerchantBatchGetResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}