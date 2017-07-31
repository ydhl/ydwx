<?php
/**
 * 门店管理相关接口
 */

/**
 * 上传图片
 * 
 * @param unknown $accessToken
 * @param unknown $buffer
 * @throws YDWXException
 * @return string logo url地址
 */
function ydwx_poi_uploadimage($accessToken, $buffer){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."media/uploadimg?access_token={$accessToken}",
    array("buffer"=>"@".$buffer) ,true);
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->url;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 
 * 创建门店接口是为商户提供创建自己门店数据的接口，门店数据字段越完整，商户页面展示越丰富，越能够吸引更多用户，并提高曝光度。
 * 创建门店接口调用成功后会返回bool，但不会实时返回poi_id。
 * 成功创建后，会生成poi_id，但该id不一定为最终id。门店信息会经过审核，审核通过后方可获取最终poi_id(YDWXHook::Event_poi_check_notify 钩子)，该id为门店的唯一id，强烈建议自行存储审核通过后的最终poi_id，并为后续调用使用。
 * @param unknown $accessToken
 * @param YDWXPoiAddRequest $request
 * @throws YDWXException
 * @return boolean
 */
function ydwx_poi_add($accessToken, YDWXPoiAddRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."poi/addpoi?access_token={$accessToken}",
    $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 查询门店信息
 * 创建门店后获取poi_id 后，商户可以利用poi_id，查询具体某条门店的信息。 若在查询时，
 * update_status 字段为1，表明在5 个工作日内曾用update 接口修改过门店扩展字段，
 * 该扩展字段为最新的修改字段，尚未经过审核采纳，因此不是最终结果。最终结果会在5 个工作日内，
 * 最终确认是否采纳，并前端生效（但该扩展字段的采纳过程不影响门店的可用性，即available_state仍为审核通过状态）
 * 注：扩展字段为公共编辑信息（大家都可修改），修改将会审核，并决定是否对修改建议进行采纳，
 * 但不会影响该门店的生效可用状态。
 * 
 * @param unknown $accessToken
 * @param unknown $poiid
 * @throws YDWXException
 * @return YDWXPoiGetResponse
 */
function ydwx_poi_get($accessToken, $poiid){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."poi/getpoi?access_token={$accessToken}",
    ydwx_json_encode(array("poi_id"=>$poiid)));
    $msg  = new YDWXPoiGetResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 查询门店列表
 * 商户可以通过该接口，批量查询自己名下的门店list，并获取已审核通过的poi_id（所有状态均会返回poi_id，但该poi_id不一定为最终id）、商户自身sid 用于对应、商户名、分店名、地址字段
 * 
 * @param unknown $accessToken
 * @param unknown $begin 开始位置，0 即为从第一条开始查询
 * @param unknown $limit 返回数据条数，最大允许50，默认为20
 * @throws YDWXException
 * @return YDWXPoiGetListResponse
 */
function ydwx_poi_list($accessToken, $begin, $limit){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."poi/getpoilist?access_token={$accessToken}",
    ydwx_json_encode(array("begin"=>$begin, "limit"=>$limit)));
    $msg  = new YDWXPoiGetListResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 修改门店服务信息
 * 商户可以通过该接口，修改门店的服务信息，包括：图片列表、营业时间、推荐、特色服务、简介、人均价格、电话7 个字段（名称、坐标、地址等不可修改）修改后需要人工审核。
 * 若有填写内容则为覆盖更新，若无内容则视为不修改，维持原有内容。 photo_list 字段为全列表覆盖，若需要增加图片，需将之前图片同样放入list 中，在其后增加新增图片。如：已有A、B、C 三张图片，又要增加D、E 两张图，则需要调用该接口，photo_list 传入A、B、C、D、E 五张图片的链接。
 * @param unknown $accessToken
 * @param unknown $begin
 * @param unknown $limit
 * @throws YDWXException
 * @return boolean
 */
function ydwx_poi_update($accessToken, YDWXPoiAddRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."poi/updatepoi?access_token={$accessToken}",
    $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg);
}
/**
 * 删除门店
 * 商户可以通过该接口，删除已经成功创建的门店。请商户慎重调用该接口，门店信息被删除后，可能会影响其他与门店相关的业务使用，如卡券等。同样，该门店信息也不会在微信的商户详情页显示，不会再推荐入附近功能。
 * 
 * @param unknown $accessToken
 * @param unknown $poiid
 * @throws YDWXException
 * @return boolean
 */
function ydwx_poi_delete($accessToken, $poiid){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."poi/delpoi?access_token={$accessToken}",
    ydwx_json_encode(array("poi_id"=>$poiid)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 取得poi类目
 * @param unknown $accessToken
 * @throws YDWXException
 * @return array
 */
function ydwx_poi_getwxcategory($accessToken){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL."api_getwxcategory?access_token={$accessToken}");
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->category_list;
    }
    throw new YDWXException($msg->errmsg);
}
