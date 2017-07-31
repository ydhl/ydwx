<?php
/**
 * 开发者 协助 制券 接口（子商户无公众号模式）
 */

/**
 * 创建无公众号卡券子商户，创建成功后便可代其发卡
 * 
 * @param unknown $accessToken  第三方平台的access token
 * @param YDWXCardSubmerchantRequest $request
 * @throws YDWXException
 * @return YDWXCardSubmerchantResponse
 */
function ydwx_card_submerchant_submit($accessToken, YDWXCardSubmerchantRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/submerchant/submit?access_token={$accessToken}",
    $request->toJSONString());
    $msg  = new YDWXCardSubmerchantResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 卡券类目查询接口
 * 通过调用该接口查询卡券开放的类目ID，类目会随业务发展变更，请每次用接口去查询获取实时卡券类目。
 * 注意：
 * 本接口查询的返回值还有卡券资质ID,此处的卡券资质为：已微信认证的公众号通过微信公众平台申请卡券功能时，所需的资质。
 * 对于开发者协助制券（无公众号）模式，子商户无论选择什么类目，均暂不需提供资质，所以不用考虑此处返回的资质字段，返回值仅参考类目ID 即可。
 * 
 * @param unknown $accessToken  第三方平台的access token
 * @throws YDWXException
 * @return array YDWXCardApplyProtocol 组成的数组
 */
function ydwx_card_getapplyprotocol($accessToken){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."card/getapplyprotocol?access_token={$accessToken}");
    $msg  = new YDWXResponse($info);
    $array= array();
    if($msg->isSuccess()){
        foreach ($msg->category as $category){
            $protocol = new YDWXCardApplyProtocol();
            $protocol->category_id      = $category['primary_category_id'];
            $protocol->category_name    = $category['category_name'];
            $protocol->secondary_category = array();
            foreach ($category['secondary_category'] as $sub){
                $secondary = new YDWXCardApplyProtocol();
                $secondary->category_id      = $sub['secondary_category_id'];
                $secondary->category_name    = $sub['category_name'];
                $secondary->can_choose_payment_card    = $sub['can_choose_payment_card'];
                $secondary->can_choose_prepaid_card    = $sub['can_choose_prepaid_card'];
                $protocol->secondary_category[] = $secondary;
            }
            $array[]  = $protocol;
        }
        return $array;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 更新子商户接口
 * 支持调用该接口更新子商户信息。
 * 注：只有审核驳回和过期两种状态的子商户才能调用更新接口
 * 
 * @param unknown $accessToken  第三方平台的access token
 * @param YDWXCardSubmerchantRequest $request
 * @throws YDWXException
 * @return YDWXCardSubmerchantResponse
 */
function ydwx_card_submerchant_update($accessToken, YDWXCardSubmerchantRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/submerchant/update?access_token={$accessToken}",
    $request->toJSONString());
    $msg  = new YDWXCardSubmerchantResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 支持通过指定子商户 ID 拉取 子商户信息。
 * @param unknown $accessToken  第三方平台的access token
 * @param unknown $merchant_id
 * @throws YDWXException
 * @return YDWXCardSubmerchantResponse
 */
function ydwx_card_submerchant_get($accessToken, $merchant_id){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/submerchant/get?access_token={$accessToken}",
    ydwx_json_encode(array("merchant_id"=>intval($merchant_id))));
    $msg  = new YDWXCardSubmerchantResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 批量拉取子商户信息接口
 * 母商户 可以通过该接口批量拉取 子商户的相关信息，一次调用最多拉起100个子商户的信息，可 以通过多次拉去满足不同的查询需求

 * @param unknown $accessToken  第三方平台的access token
 * @param unknown $begin_id merchant_id
 * @param unknown $limit 数量 最大100
 * @param unknown $status YDWX_CARD_MERCHANT_XX
 * @throws YDWXException
 * @return YDWXCardSubmerchantBatchGetResponse
 */
function ydwx_card_submerchant_batchget($accessToken, $begin_id, $limit, $status){
    if($limit>100){
        throw new YDWXException("商户批量查询数量最大100");
    }
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."card/submerchant/batchget?access_token={$accessToken}",
    ydwx_json_encode(array("begin_id"=>intval($begin_id),"limit"=>intval($limit),"status"=>$status)));
    $msg  = new YDWXCardSubmerchantBatchGetResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}
