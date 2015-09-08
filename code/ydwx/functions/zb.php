<?php
/**
 * 接口说明 申请开通摇一摇周边功能。成功提交申请请求后，工作人员会在三个工作日内完成审核。
 * 若审核不通过，可以重新提交申请请求。若是审核中，请耐心等待工作人员审核，在审核中状态不能再提交申请请求。
 * 
 * @param unknown $accessToken
 * @param unknown $request
 * @throws YDWXException
 * @return bool
 */
function ydwx_shakearound_register($accessToken,  YDWXShakeAroundRegister $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/account/register?access_token={$accessToken}",
        $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 接口说明 查询已经提交的开通摇一摇周边功能申请的审核状态。在申请提交后，工作人员会在三个工作日内完成审核。
 * 
 * @param unknown $accessToken
 * @throws YDWXException
 * @return YDWXZBStatus
 */
function ydwx_shakearound_register_status($accessToken){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."shakearound/account/auditstatus?access_token={$accessToken}");
    $msg  = new YDWXZBStatus($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg);
}
/**
 * 申请设备ID
 * 
 * 接口说明 申请配置设备所需的UUID、Major、Minor。申请成功后返回批次ID，可用返回的批次ID通过“查询设备ID申请状态”接口查询目前申请的审核状态。
 * 
 * @param unknown $accessToken
 * @param YDWXZBDeviceRegister $request
 * @throws YDWXException
 * @return YDWXZBDeviceRegisterResponse
 */
function ydwx_shakearound_device_apply($accessToken, YDWXZBDeviceRegister $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/applyid?access_token={$accessToken}", $request->toJSONString());
    $msg  = new YDWXZBDeviceRegisterResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 查询设备ID申请审核状态
 * 
 * 查询设备ID申请的审核状态。
 * @param unknown $accessToken 
 * @param string $applyid ydwx_shakearound_device_apply返回的id
 * @throws YDWXException
 * @return YDWXZBStatus
 */
function ydwx_shakearound_device_apply($accessToken, $applyid){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/applystatus?access_token={$accessToken}",
        ydwx_json_encode(array("apply_id"=>$applyid)));
    $msg  = new YDWXZBStatus($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg);
}
/**
 * 编辑设备信息
 * 
 * 编辑设备的备注信息。可用设备ID或完整的UUID、Major、Minor指定设备，二者选其一
 * 
 * @param unknown $accessToken
 * @param YDWXZBDevice $device
 * @throws YDWXException
 * @return bool
 */
function ydwx_shakearound_device_update($accessToken, YDWXZBDevice $device){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/update?access_token={$accessToken}",
        $device->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 配置设备与门店的关联关系
 * 修改设备关联的门店ID。可用设备ID或完整的UUID、Major、Minor指定设备，二者选其一。
 * 
 * @param unknown $accessToken
 * @param YDWXZBDevice $device
 * @throws YDWXException
 * @return boolean
 */
function ydwx_shakearound_device_bindlocation($accessToken, YDWXZBDevice $device){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/bindlocation?access_token={$accessToken}",
    $device->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 查询设备指定设备信息
 * 
 * @param unknown $accessToken
 * @param YDWXZBDevice $device
 * @throws YDWXException
 * @return YDWXZBDeviceSearchResponse
 */
function ydwx_shakearound_device_search($accessToken, YDWXZBDevice $device){
    $http = new YDHttp();
    $array = array("type"=>1,"device_identifiers"=>$device->baseInfo());
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/search?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXZBDeviceSearchResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg);
}

/**
 * 分页查询设备
 *
 * @param unknown $accessToken
 * @param int $begin
 * @param int $count
 * @param string $apply_id 传入则只查询该批次的
 * @throws YDWXException
 * @return YDWXZBDeviceSearchResponse
 */
function ydwx_shakearound_device_search_range($accessToken, $begin, $count, $apply_id=null){
    $http = new YDHttp();
    $array = array("type"=>2,"begin"=>$begin,"count"=>$count);
    if($apply_id){
        $array['apply_id'] = $apply_id;
        $array['type'] = 3;
    }
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/search?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXZBDeviceSearchResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg);
}