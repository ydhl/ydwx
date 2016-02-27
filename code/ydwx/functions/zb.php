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
    throw new YDWXException($msg->errmsg, $msg->errcode);
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
    throw new YDWXException($msg->errmsg, $msg->errcode);
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
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 根据e批次ID查询申请审核状态
 * 
 * @param unknown $accessToken 
 * @param string $applyid ydwx_shakearound_device_apply返回的id
 * @throws YDWXException
 * @return YDWXZBStatus
 */
function ydwx_shakearound_device_status($accessToken, $applyid){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/applystatus?access_token={$accessToken}",
        ydwx_json_encode(array("apply_id"=>intval($applyid))));
    $msg  = new YDWXZBStatus($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}
/**
 * 编辑设备信息
 * 
 * 编辑设备的备注信息。可用设备ID或完整的UUID、Major、Minor指定设备，二者选其一
 * 
 * @param unknown $accessToken
 * @param YDWXZBDeviceBase $device  设置device_id 或者 uuid,major,minor
 * @throws YDWXException
 * @return bool
 */
function ydwx_shakearound_device_update($accessToken, YDWXZBDeviceBase $device, $comment){
    $http  = new YDHttp();
    $array = $device->toArray();
    $array['comment'] = $comment;
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/update?access_token={$accessToken}",
        ydwx_json_encode($array));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 配置设备与门店的关联关系
 * 修改设备关联的门店ID。可用设备ID或完整的UUID、Major、Minor指定设备，二者选其一。
 * 
 * @param unknown $accessToken
 * @param YDWXZBDeviceBase $device $device 设置device_id 或者 uuid,major,minor
 * @throws YDWXException
 * @return boolean
 */
function ydwx_shakearound_device_bindlocation($accessToken, YDWXZBDeviceBase $device, $poiid){
    $http = new YDHttp();
    $array = $device->toArray();
    $array['poi_id'] = intval($poiid);
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/bindlocation?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 查询设备指定设备信息
 * 
 * @param unknown $accessToken
 * @param YDWXZBDeviceBase $device $device 设置device_id 或者 uuid,major,minor
 * @throws YDWXException
 * @return YDWXZBDeviceSearchResponse
 */
function ydwx_shakearound_device_search($accessToken, YDWXZBDeviceBase $device){
    $http = new YDHttp();
    $array = array("type"=>1,"device_identifiers"=>$device->baseInfo());
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/search?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXZBDeviceSearchResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 分页查询设备
 *
 * @param unknown $accessToken
 * @param int $begin 设备列表的起始索引值
 * @param int $count 待查询的设备数量，不能超过50个
 * @param string $apply_id 传入则只查询该批次的
 * @throws YDWXException
 * @return YDWXZBDeviceSearchResponse
 */
function ydwx_shakearound_device_search_range($accessToken, $begin, $count, $apply_id=null){
    $http = new YDHttp();
    $array = array("type"=>2,"begin"=>intval($begin),"count"=>intval($count));
    if($apply_id){
        $array['apply_id'] = intval($apply_id);
        $array['type'] = 3;
    }
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/search?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXZBDeviceSearchResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 增加摇一摇页面，返回页面id
 * @param unknown $accessToken
 * @param YDWXZBPage $page
 * @throws YDWXException
 */
function ydwx_shakearound_page_add($accessToken, YDWXZBPage $page){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/page/add?access_token={$accessToken}",
    $page->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->data['page_id'];
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}
/**
 * 编辑摇一摇页面，成功返回true
 * @param unknown $accessToken
 * @param YDWXZBPage $page
 * @throws YDWXException
 * @return boolean
 */
function ydwx_shakearound_page_update($accessToken, YDWXZBPage $page){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/page/update?access_token={$accessToken}",
    $page->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}
/**
 * 删除页面
 * @param unknown $accessToken
 * @param string $pageid
 * @throws YDWXException
 * @return boolean
 */
function ydwx_shakearound_page_delete($accessToken, $pageid){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/page/delete?access_token={$accessToken}",
        ydwx_json_encode(array("page_id"=>intval($pageid))));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}
/**
 * 分页查询摇一摇页面
 * @param unknown $accessToken
 * @param unknown $begin
 * @param unknown $count
 * @throws YDWXException
 * @return YDWXZBPageSearchResponse
 */
function ydwx_shakearound_page_search_range($accessToken, $begin, $count){
    $http = new YDHttp();
    $array = array("type"=>2,"begin"=>intval($begin),"count"=>intval($count));
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/page/search?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXZBPageSearchResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 
 * 
 * @param unknown $accessToken
 * @param string|array $pageid 如123 或则array(123,456)
 * @throws YDWXException
 * @return YDWXZBPageSearchResponse
 */
function ydwx_shakearound_page_search($accessToken, $pageid){
    $http = new YDHttp();
    $ids  = array_map(function($item){
        return intval($item);
    }, (array)$pageid);
    $array = array("type"=>1,"page_ids"=>$ids);
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/page/search?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXZBPageSearchResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 上传图片素材
 * 
 * 图片大小建议120px*120 px，限制不超过200 px *200 px，图片需为正方形。
 * 
 * @param unknown $accessToken
 * @param unknown $media
 * @throws YDWXException
 * @return string 图片地址 可用在“新增页面”和“编辑页面”的“icon_url”字段
 */
function ydwx_shakearound_upload($accessToken, $media){
    list($width, $height, $orig_type, $attr) = @getimagesize($media);
    if($width != $height) throw new YDWXException("图片大小建议120px*120 px，限制不超过200 px *200 px，图片需为正方形");
    if($width>200) throw new YDWXException("图片大小建议120px*120 px，限制不超过200 px *200 px，图片需为正方形");
    
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/material/add?access_token={$accessToken}", 
            array("media"=>"@".$media,"type"=>"icon") ,true);
    $msg  = new YDWXResponse($info); 
    if($msg->isSuccess()){
        return $msg->data['pic_url']; 
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 上传资质文件图片
 *
 * 申请开通摇一摇周边功能需要上传的资质文件图片，则其素材为license类型的图片，图片的文件大小不超过2MB，尺寸不限，形状不限。
 *
 * @param unknown $accessToken
 * @param unknown $media
 * @throws YDWXException
 * @return string 图片地址 可用在“申请入驻”的“qualification_cert_urls”字段
 */
function ydwx_shakearound_upload_license($accessToken, $media){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/material/add?access_token={$accessToken}",
    array("media"=>"@".$media,"type"=>"license") ,true);
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->data['pic_url'];
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 配置设备与页面的关联关系
 * 若设备配置多个页面，则随机出现页面信息
 * 
 * @param unknown $accessToken
 * @param YDWXZBDeviceBase $device 设置device_id 或者 uuid,major,minor
 * @param string|array $pageids
 * @throws YDWXException
 * @return boolean
 */
function ydwx_shakearound_device_bind_page($accessToken, YDWXZBDeviceBase $device, $pageids){
    $http = new YDHttp();
    $array= $device->toArray();
    $array['page_ids'] = array_map(function($item){
        return intval($item);
    }, (array)$pageids);
    
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/bindpage?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 根据设备ID或完整的UUID、Major、Minor查询该设备所关联的所有页面信息。
 * 
 * @param unknown $accessToken
 * @param YDWXZBDeviceBase $device 设置device_id 或者 uuid,major,minor
 * @throws YDWXException
 * @return YDWXZBRelationSearchResponse
 */
function ydwx_shakearound_relation_search($accessToken, YDWXZBDeviceBase $device){
    $http = new YDHttp();
    $array= $device->toArray();
    $array['type'] = 1;
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/relation/search?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXZBRelationSearchResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 指定页面ID分页查询该页面所关联的所有的设备信息
 * @param unknown $accessToken
 * @param string $page_id
 * @param string $begin 关联关系列表的起始索引值
 * @param string $count 待查询的关联关系数量，不能超过50个
 * @throws YDWXException
 * @return YDWXZBRelationSearchResponse
 */
function ydwx_shakearound_relation_search_range($accessToken, $page_id, $begin, $count){
    $http = new YDHttp();
    $array= array('type' => 2, "page_id"=>intval($page_id), "begin"=>intval($begin), "count"=>intval($count));
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/relation/search?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXZBRelationSearchResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 获取摇周边的设备及用户信息
 * 获取设备信息，包括UUID、major、minor，以及距离、openID等信息。
 * @param unknown $accessToken
 * @param unknown $ticket 摇周边业务的ticket，可在摇到的URL中得到，ticket生效时间为30分钟，每一次摇都会重新生成新的ticket
 * @throws YDWXException
 * @return YDWXZBShakeInfoResponse
 */
function ydwx_shakearound_get_shake_info($accessToken, $ticket){
    $http = new YDHttp();
    $array= array('ticket' => $ticket, "need_poi"=>1);
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/user/getshakeinfo?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXZBShakeInfoResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 在摇一摇的结果页面中实现关注功能，该函数返回javascript代码，直接在页面合适的位置输出即可
 */
function ydwx_shakearound_follow_me(){
    ob_start()
?>
    <script type="text/javascript" src="http://zb.weixin.qq.com/nearbycgi/addcontact/BeaconAddContactJsBridge.js"></script>
    <script type="text/javascript">
	BeaconAddContactJsBridge.ready(function(){
		BeaconAddContactJsBridge.invoke('checkAddContactStatus',{} ,function(apiResult){
			if(apiResult.err_code == 0){
				var status = apiResult.data;
				if(status != 1){
				  BeaconAddContactJsBridge.invoke('jumpAddContact');
				}else{
					alert("已关注");
				}
			}else{
				alert(apiResult.err_msg)
			}
		});
 	});
</script>
<?php 
    return ob_get_clean();
}

/**
 * 以设备为维度的数据统计接口
 * 接口说明 查询单个设备进行摇周边操作的人数、次数，点击摇周边消息的人数、次数；查询的最长时间跨度为30天。只能查询最近90天的数据。
 * 此接口无法获取当天的数据，最早只能获取前一天的数据。由于系统在凌晨处理前一天的数据，太早调用此接口可能获取不到数据，建议在早上8：00之后调用此接口。
 * 
 * @param unknown $accessToken
 * @param YDWXZBDeviceBase $device 设备编号，若填了UUID、major、minor，即可不填设备编号，二者选其一
 * @param unknown $beginDate
 * @param unknown $endDate
 * @throws YDWXException
 * @return array YDWXZBStatistic组成的数组
 */
function ydwx_shakearound_statistics_device($accessToken, YDWXZBDeviceBase $device, $beginDate, $endDate){
    $http = new YDHttp();
    $array= $device->toArray();
    $array['begin_date'] = $beginDate;
    $array['end_date']   = $endDate;
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/statistics/device?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        $array = array();
        foreach ($msg->data as $info){
            $stistic = new YDWXZBStatistic();
            $stistic->click_pv = $info['click_pv'];
            $stistic->click_uv = $info['click_uv'];
            $stistic->ftime    = $info['ftime'];
            $stistic->shake_pv = $info['shake_pv'];
            $stistic->shake_uv = $info['shake_uv'];
            $array[] = $stistic;
        }
        return $array;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 批量查询设备统计数据接口 
 * 
 * 接口说明 查询指定时间商家帐号下的每个设备进行摇周边操作的人数、次数，点击摇周边消息的人数、次数。
 * 只能查询最近90天内的数据，且一次只能查询一天。
 * 此接口无法获取当天的数据，最早只能获取前一天的数据。由于系统在凌晨处理前一天的数据，太早调用此接口可能获取不到数据，建议在早上8：00之后调用此接口。
 * 注意：对于摇周边人数、摇周边次数、点击摇周边消息的人数、点击摇周边消息的次数都为0的设备，不在结果列表中返回。
 * 
 * @param unknown $accessToken
 * @param unknown $date 指定查询日期时间戳，单位为秒
 * @param unknown $page 指定查询的结果页序号；返回结果按摇周边人数降序排序，每50条记录为一页
 * @throws YDWXException
 * @return YDWXZBDeviceStatisticResult
 */
function ydwx_shakearound_statistics_devicelist($accessToken, $date, $page){
    $http = new YDHttp();
    $array= array();
    $array['page_index'] = $page;
    $array['date']       = $date;
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/statistics/devicelist?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXZBDeviceStatisticResult($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 
 * 以页面为维度的数据统计接口
 * 接口说明 查询单个页面通过摇周边摇出来的人数、次数，点击摇周边页面的人数、次数；查询的最长时间跨度为30天。只能查询最近90天的数据。
 * 此接口无法获取当天的数据，最早只能获取前一天的数据。由于系统在凌晨处理前一天的数据，太早调用此接口可能获取不到数据，建议在早上8：00之后调用此接口。
 * 
 * @param unknown $accessToken
 * @param unknown $pageid
 * @param unknown $begin
 * @param unknown $end
 * @throws YDWXException
 * @return multitype:YDWXZBStatistic YDWXZBStatistic组成的数组
 */
function ydwx_shakearound_statistics_page($accessToken, $pageid, $begin, $end){
    $http = new YDHttp();
    $array= array();
    $array['page_id']    = intval($pageid);
    $array['begin_date'] = $beginDate;
    $array['end_date']   = $endDate;
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/statistics/page?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        $array = array();
        foreach ($msg->data as $info){
            $stistic = new YDWXZBStatistic();
            $stistic->click_pv = $info['click_pv'];
            $stistic->click_uv = $info['click_uv'];
            $stistic->ftime    = $info['ftime'];
            $stistic->shake_pv = $info['shake_pv'];
            $stistic->shake_uv = $info['shake_uv'];
            $array[] = $stistic;
        }
        return $array;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 批量查询页面统计数据接口
 * 接口说明 查询指定时间商家帐号下的每个页面进行摇周边操作的人数、次数，点击摇周边消息的人数、次数。
 * 只能查询最近90天内的数据，且一次只能查询一天。
 * 此接口无法获取当天的数据，最早只能获取前一天的数据。由于系统在凌晨处理前一天的数据，太早调用此接口可能获取不到数据，建议在早上8：00之后调用此接口。
 * 注意：对于摇周边人数、摇周边次数、点击摇周边消息的人数、点击摇周边消息的次数都为0的页面，不在结果列表中返回。
 * @param unknown $accessToken
 * @param unknown $date
 * @param unknown $page
 * @throws YDWXException
 * @return YDWXZBPageStatisticResult
 */
function ydwx_shakearound_statistics_pagelist($accessToken, $date, $page){
    $http = new YDHttp();
    $array= array();
    $array['page_index'] = $page;
    $array['date']       = $date;
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/statistics/pagelist?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXZBPageStatisticResult($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 新建设备分组，每个帐号下最多只有1000个分组。
 * 
 * @param unknown $accessToken
 * @param unknown $group_name 分组名称，不超过100汉字或200个英文字母
 * @throws YDWXException
 * @return groupid
 */
function ydwx_shakearound_device_group_add($accessToken, $group_name){
    $http = new YDHttp();
    $array= array();
    $array['group_name'] = $group_name;
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/group/add?access_token={$accessToken}",
    ydwx_json_encode($array));

    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->data['group_id'];
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 编辑设备分组信息，目前只能修改分组名。
 * 
 * @param unknown $accessToken
 * @param unknown $group_id
 * @param unknown $group_name 分组名称，不超过100汉字或200个英文字母
 * @throws YDWXException
 * @return boolean
 */
function ydwx_shakearound_device_group_update($accessToken, YDWXZBDeviceGroup $group){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/group/update?access_token={$accessToken}",
    $group->toJSONString());

    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 删除设备分组，若分组中还存在设备，则不能删除成功。需把设备移除以后，才能删除。
 * 
 * @param unknown $accessToken
 * @param unknown $group_id
 * @throws YDWXException
 * @return boolean
 */
function ydwx_shakearound_device_group_delete($accessToken, $group_id){
    $http = new YDHttp();
    $array= array();
    $array['group_id'] = intval($group_id);
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/group/delete?access_token={$accessToken}",
    ydwx_json_encode($array));

    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 查询账号下所有的分组。
 * 
 * @param unknown $accessToken
 * @param unknown $begin 分组列表的起始索引值
 * @param unknown $count 待查询的分组数量，不能超过1000个
 * @throws YDWXException
 * @return multitype:YDWXZBDeviceGroup
 */
function ydwx_shakearound_device_group_getlist($accessToken, $begin=0, $count=1000){
    $http = new YDHttp();
    $array= array();
    $array['begin'] = intval($begin);
    $array['count'] = intval($count);
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/group/getlist?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        $array = array();
        foreach ($msg->data->groups as $info){
            $stistic = new YDWXZBDeviceGroup();
            $stistic->group_id   = $info['group_id'];
            $stistic->group_name = $info['group_name'];
            $array[] = $stistic;
        }
        return $array;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 查询分组详情，包括分组名，分组id，分组里的设备列表。
 * @param unknown $accessToken
 * @param unknown $groupid
 * @param number $begin 分组里设备的起始索引值
 * @param number $count 待查询的分组里设备的数量，不能超过1000个
 * @throws YDWXException
 * @return YDWXZBDeviceGroupDevices
 */
function ydwx_shakearound_device_group_getdetail($accessToken, $groupid, $begin=0, $count=1000){
    $http = new YDHttp();
    $array= array();
    $array['groupid'] = intval($groupid);
    $array['begin'] = intval($begin);
    $array['count'] = intval($count);
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/group/getdetail?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        $detail = new YDWXZBDeviceGroupDevices();
        $detail->total_count = $msg->data->total_count;
        $group = new YDWXZBDeviceGroup();
        $group->group_id    = $msg->data->group_id;
        $group->group_name  = $msg->data->group_name;
        $detail->group = $group;
        $array = array();
        foreach ($msg->data->devices as $info){
            $stistic = new YDWXZBDevice();
            $stistic->comment   = $info['comment'];
            $stistic->poi_id    = $info['poi_id'];
            $stistic->device_id = $info['device_id'];
            $stistic->major   = $info['major'];
            $stistic->minor   = $info['minor'];
            $stistic->uuid    = $info['uuid'];
            $array[] = $stistic;
        }
        $detail->devices = $array;
        return $detail;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 添加设备到分组，每个分组能够持有的设备上限为10000，并且每次添加操作的添加上限为1000。只有在摇周边申请的设备才能添加到分组。
 * 
 * @param unknown $accessToken
 * @param unknown $group_id
 * @param array $devices YDWXZBDevice数组 设备编号，若填了UUID、major、minor，即可不填设备编号，二者选其一
 * @throws YDWXException
 * @return boolean
 */
function ydwx_shakearound_device_group_adddevice($accessToken, $group_id, array $devices){
    $http = new YDHttp();
    $array= array();
    $array['group_id'] = intval($group_id);
    foreach ($devices as $device){
        $array['device_identifiers'][] = $device->baseInfo();
    }
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/group/adddevice?access_token={$accessToken}",
    ydwx_json_encode($array));

    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 从分组中移除设备，每次删除操作的上限为1000。
 * 
 * @param unknown $accessToken
 * @param unknown $group_id
 * @param array $devices YDWXZBDevice数组 设备编号，若填了UUID、major、minor，即可不填设备编号，二者选其一
 * @throws YDWXException
 * @return boolean
 */
function ydwx_shakearound_device_group_deletedevice($accessToken, $group_id, array $devices){
    $http = new YDHttp();
    $array= array();
    $array['group_id'] = intval($group_id);
    foreach ($devices as $device){
        $array['device_identifiers'][] = $device->baseInfo();
    }
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/device/group/deletedevice?access_token={$accessToken}",
    ydwx_json_encode($array));

    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}
/**
 * 开启查找周边ibeacon设备接口,该方法输出接口ydwx_startSearchBeacons(ticket, cb, listener)
 * ticket:摇周边的业务ticket，系统自动添加在摇出来的页面链接后面,可不传
 * cb: 回调，参数status. 返回相关的状态
 * listener： 获取设备的回调，当检测到设备后会调用，参数是设备数组，每个设备的数据有
 * 示例：
 * 
 * ydwx_startSearchBeacons(ticket, function(status){
 *  if(status=='startSearchBeacons:ok'){//打开成功返回
 *  }else if(status=='startSearchBeacons:already started'){//打开后未stop再次打开 
 *  }else if(status=='startSearchBeacons:bluetooth power off'){//蓝牙未打开返回
 *  }else if(status=='startSearchBeacons:location service disable'){//地理位置服务未打开返回
 *  }else if(status=='startSearchBeacons:system unsupported'){//系统不支持返回
 *  }else{//其他错误
 *  }
 * }, function(devices){
 * for(var i=0; i<devices.length; i++){
 *  devices[i].major
 *  devices[i].minor
 *  devices[i].uuid
 *  devices[i].accuracy 距离，单位为米
 *  devices[i].rssi 接收信号的强度指示
 *  devices[i].proximity 精度，0：CLProximityUnknown, 1：CLProximityImmediate, 2：CLProximityNear, 3：CLProximityFar
 *  devices[i].heading 接收信号时设备的方向（安卓设备返回有此字段，iOS无）；iOS设备若需要获取方向，可以利用HTML5标准API获取 http://o2o.gtimg.com/ibeacon/download/html5_compass_direction.html
 * }
 * });
 * 
 * @return string
 */
function ydwx_startSearchBeacons_script(){
    ob_start();
    ?>
    function ydwx_startSearchBeacons(ticket, cb, listener){
        wx.startSearchBeacons({
           ticket:ticket,
           complete:function(argv){
            cb(argv);
            if(argv=='startSearchBeacons:ok'){
                wx.onSearchBeacons({
                       complete:function(argv){
                        listener(argv.onSearchBeacons.beacons);
                       }
                    });
                }
           }
        });
    }
<?php 
return ob_get_clean();
}

/**
 * 关闭查找周边设备接口ydwx_stopSearchBeacons(cb);
 * cb: 回调，参数status.
 * 示例：
 * 
 * ydwx_stopSearchBeacons(function(status){
 *  if(status=='stopSearchBeacons:ok'){//成功
 *  }else{//其他错误
 *  }
 * });
 * @return string
 */
function ydwx_stopSearchBeacons_script(){
    ob_start();
    ?>
    function ydwx_stopSearchBeacons(cb){
        wx.stopSearchBeacons({
           complete:function(argv){
            if(cb)cb(argv);
           }
        });
    }
<?php 
return ob_get_clean();
}

/**
 * 查询红包活动信息。
 * @param unknown $lotteryid
 * @throws YDWXException
 * @return YDWXZBLotteryInfo
 */
function ydwx_shakearound_querylottery($accessToken, $lotteryid){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."shakearound/lottery/querylottery?access_token={$accessToken}&lottery_id={$lotteryid}");
    $msg  = new YDWXZBLotteryInfo($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}
?>