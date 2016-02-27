<?php
/**
 * 上传图片，返回图片url
 * @param unknown $accessToken
 * @param unknown $media 图片绝对路径
 * @throws YDWXException
 * @return YDWXYaoTVImgUploadResponse
 * @author leeboo@yidianhulian.com
 */
function ydwx_yaotv_upload_img($accessToken, $media){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/resource/imgUpload.json?access_token={$accessToken}", 
            array("detail"=>"@".$media) ,true);
    $msg  = new YDWXYaoTVImgUploadResponse($info); 
    if($msg->isSuccess()){
        return $msg; 
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 调用接口，以天为单位，将最新的节目单同步到微信后台。
 * 同步范围约束
 * 以节目的开始时间为参考，以天为单位同步节目。一天从00:00:00 ~ 23:59:59，24:00:00 作为第二天的00:00:00。
 * 例1：
 * 节目1：2014-06-18 23:00:00 ~ 2014-06-18 23:30:00 
 * 节目2：2014-06-18 23:30:00 ~ 2014-06-19 00:10:00 (跨天) 
 * 节目3：2014-06-19 00:10:00 ~ 2014-06-19 02:10:00
 * 则： 
 * 节目1、节目2属于 2014-06-18; 节目3属于2014-06-19
 * 例2：
 * 节目1：2014-06-18 23:00:00 ~ 2014-06-19 00:00:00
 * 节目2：2014-06-19 00:00:00 ~ 2014-06-19 00:10:00 （24点整开始)
 * 则：
 * 节目1属于2014-06-18; 节目2属于2014-06-19 
 * 更新策略说明：
 * 微信后台通过节目ID（programId）来识别是否是相同的节目。
 * API请求		
 * ID1	==>	ID1	节目时间有变
 * ID2	==>	ID2	节目时间不变
 * ID3			微信后台没有当前节目
 *          ID4	API中没有传当前节目
 * ID5		ID5	没有变化
 * 对ID1：更新节目，节目下配的活动失效。节目ID（ProgramId）会放在列表中返回。
 * 对ID2：更新节目，活动不受影响
 * 对ID3：保存，新增。
 * 对ID4：微信后台删除当前节目，并将节目上配置的活动置为失效，节目ID（ProgramId）放在列表中返回。
 * 对ID5：节目不变。
 * 返回：ID1，ID4
 * 
 * @param unknown $accessToken
 * @param YDWXYaoTVProgramSyncRequest $request
 * @throws YDWXException
 * @return YDWXYaoTVProgramSyncResponse
 * @author leeboo@yidianhulian.com
 */
function ydwx_yaotv_program_sync($accessToken, YDWXYaoTVProgramSyncRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/program/sync.json?access_token={$accessToken}",
        $request->toJSONString());
    $msg  = new YDWXYaoTVProgramSyncResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}
/**
 * 调用接口，以节目为单位，将最新的活动列表同步到微信后台，同时可选择设置节目互动描述。
 * 同步范围约束
 * 以节目为单位整体同步，同步节目下的所有活动列表。活动的开始结束时间为相对时间，总时长不能超过节目的时长。每个活动的开始和结束时间不能有重叠。活动的时间区间为左闭右开。
 * 例:
 * 允许：
 * 活动1：00:00 ~ 20:00
 * 活动2：20:00 ~ 40:00
 * 不允许：
 * 活动1：00:00 ~ 20:01
 * 活动2：20:00 ~ 40:00
 * 更新策略说明： 
 * 每次同步均全量更新。
 * 
 * @param unknown $accessToken
 * @param YDWXYaoTVActivitySyncRequest $request
 * @throws YDWXException
 * @return YDWXYaoTVActivitySyncResponse
 */
function ydwx_yaotv_activity_sync($accessToken, YDWXYaoTVActivitySyncRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/activity/sync.json?access_token={$accessToken}",
    $request->toJSONString());
    $msg  = new YDWXYaoTVActivitySyncResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 调用接口，以天为单位查询节目单和配置的活动。只能拉一天的数据。不分页。
 * @param string $accessToken
 * @param string $date 日期，格式为 YYYYMMdd
 * @throws YDWXException
 * @return YDWXYaoTVProgramQueryResponse
 */
function ydwx_yaotv_program_query($accessToken, $date){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/program/query.json?access_token={$accessToken}&date={$data}");
    $msg  = new YDWXYaoTVProgramQueryResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 改变节目的播放模式
 * 
 * @param unknown $accessToken
 * @param unknown $program_id 节目的id
 * @param unknown $type 播放模式，0是录播，1是直播，2是随机
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_live_change_mode($accessToken, $program_id, $type){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/live/changeMode.json?access_token={$accessToken}&program_id={$program_id}&type={$type}");
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 添加直播节目的活动
 * @param unknown $accessToken
 * @param unknown $program_id 节目的id
 * @param unknown $resourceid 素材的id
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_live_add_activity($accessToken, $program_id, $resourceid){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/live/addActivity.json?access_token={$accessToken}&program_id={$program_id}&resourceid={$resourceid}");
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}
/**
 * 删除直播节目的某个活动
 * @param unknown $accessToken
 * @param unknown $program_id 节目的id
 * @param unknown $actid 活动的id
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_live_del_activity($accessToken, $program_id, $actid){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/live/delActivity.json?access_token={$accessToken}&program_id={$program_id}&actid={$actid}");
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 播放直播节目的活动A，活动A的状态置为播放中，如果原来已经播放了活动B，则活动B状态变成已播放
 * @param unknown $accessToken
 * @param unknown $program_id 节目的id
 * @param unknown $actid 活动的id
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_live_start_activity($accessToken, $program_id, $actid){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/live/startActivity.json?access_token={$accessToken}&program_id={$program_id}&actid={$actid}");
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}
/**
 * 置直播节目的活动为未播放（活动的状态必须是已播放或者已失效）
 * @param unknown $accessToken
 * @param unknown $program_id 节目的id
 * @param unknown $actid 活动的id
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_live_unplay_activity($accessToken, $program_id, $actid){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/live/unplayActivity.json?access_token={$accessToken}&program_id={$program_id}&actid={$actid}");
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 调用接口，上传ZIP包素材。返回素材ID和素材URL。
 * 注：
 * 1. ZIP包大小不能超过120K
 * 2. ZIP包里的文件和文件夹名称只能用英文或数字的组合
 * 3. ZIP包中必须包含一个index.html作为入口页
 * 
 * @param unknown $accessToken
 * @param unknown $media
 * @throws YDWXException
 * @return YDWXYaoTVZipAddResponse
 */
function ydwx_yaotv_resource_zip_add($accessToken, $media){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/resource/zip/add.json?access_token={$accessToken}",
    array("detail"=>"@".$media) ,true);
    $msg  = new YDWXYaoTVZipAddResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 调用接口，传入之前的素材ID，并上传新的素材来修改内容或者修改name。
 * 只能修改已经上传，但未提交的素材。
 * 
 * @param unknown $accessToken
 * @param unknown $media
 * @param YDWXYaoTVZip $zip
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_resource_zip_update($accessToken, YDWXYaoTVZip $zip, $media){
    $http = new YDHttp();
    $args = $zip->toArray();
    $args['detail'] = "@".$media;
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/resource/zip/update.json?access_token={$accessToken}",
    $args ,true);
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 由于ZIP等素材上传后，需要预览，因此并没有立刻提交入库，因此提供一个提交入库的API。
 * 调用后，素材进入入库中状态。
 * 
 * @param unknown $accessToken
 * @param YDWXYaoTVZip $zip
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_resource_submit($accessToken, YDWXYaoTVZip $zip){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/resource/submit.json?access_token={$accessToken}",
    ydwx_json_encode(array("id"=>$zip->id)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 调用接口，删除素材，如果素材已经入库成功，则删除失败。
 * @param unknown $accessToken
 * @param YDWXYaoTVZip $zip
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_resource_del($accessToken, YDWXYaoTVZip $zip){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/resource/del.json?access_token={$accessToken}",
    ydwx_json_encode(array("id"=>$zip->id)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 调用接口，查看素材的详细内容。
 * @param unknown $accessToken
 * @param unknown $id
 * @throws YDWXException
 * @return YDWXYaoTVResourceResponse
 */
function ydwx_yaotv_resource_get($accessToken, $id){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/resource/del.json?access_token={$accessToken}&id={$id}");
    $msg  = new YDWXYaoTVResourceResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 查看素材列表。分页查询。
 * 
 * @param unknown $accessToken
 * @param YDWXYaoTVResourceQueryRequest $request
 * @throws YDWXException
 * @return YDWXYaoTVResourceQueryResponse
 * @author leeboo@yidianhulian.com
 */
function ydwx_yaotv_resource_query($accessToken, YDWXYaoTVResourceQueryRequest $request){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/resource/query.json?access_token={$accessToken}&".$request->toString());
    $msg  = new YDWXYaoTVResourceQueryResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 调用接口，查询电视台下全部节目系列。不分页。
 * @param unknown $accessToken
 * @throws YDWXException
 * @return YDWXYaoTVSeriesQueryResponse
 * @author leeboo@yidianhulian.com
 */
function ydwx_yaotv_series_query($accessToken){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/series/query.json?access_token={$accessToken}");
    $msg  = new YDWXYaoTVSeriesQueryResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 新增投放组件的奖品。
 * 奖品的状态有：Editing编辑状态，Finished完成状态等两种状态。
 * 
 * @param unknown $accessToken
 * @param YDWXYaoTvPrizeAddRequest $request
 * @throws YDWXException
 * @return YDWXYaoTvPrizeAddResponse
 */
function ydwx_yaotv_prize_add($accessToken, YDWXYaoTvPrizeAddRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/prize/add?access_token={$accessToken}",
    $request->toJSONString());
    $msg  = new YDWXYaoTvPrizeAddResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 修改投放组件的奖品。
 * 奖品的状态有：Editing编辑状态，Finished完成状态等两种状态。
 * 奖品码数为0的无法修改状态为Finished完成状态，请先添加奖品码到奖品。状态为Finished完成状态的奖品无法再次修改。
 * 
 * @param unknown $accessToken
 * @param YDWXYaoTvPrize $request
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_prize_set($accessToken, YDWXYaoTvPrize $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/prize/set?access_token={$accessToken}",
    $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 读取投放组件的奖品。
 * 
 * @param unknown $accessToken
 * @param number $pageIndex
 * @param number $pageSize
 * @param string $id
 * @throws YDWXException
 * @return YDWXYaotvPrizeGetResponse
 */
function ydwx_yaotv_prize_get($accessToken, $pageIndex=1, $pageSize=1000,$id=null){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/prize/get?access_token={$accessToken}&pageIndex={$pageIndex}&pageSize={$pageSize}&id={$id}");
    $msg  = new YDWXYaotvPrizeGetResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 删除投放组件的奖品。
 * 只删除状态为Editing编辑状态的奖品，Finished完成状态的奖品不会被删除。
 * 
 * @param unknown $accessToken
 * @param long $id 奖品id，不填写将会删除状态为Editing编辑状态的全部数据
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_prize_del($accessToken, $id=null){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/prize/del?access_token={$accessToken}&id={$id}");
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 新增投放组件的奖品码，对应于属于某种奖品的单个具体奖品。
 * 例如：奖品为京东100元微信卡券，奖品码即为某张卡券的卡密或详细描述。
 * 每次新增同一奖品下的记录不超过1000条，不同奖品下的记录不超过500条，不同奖品数不能超过10种。更多记录请分批新增。
 * @param unknown $accessToken
 * @param YDWXYaoTvPrizeCodeAddRequest $request
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_prize_code_add($accessToken, YDWXYaoTvPrizeCodeAddRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/code/add?access_token={$accessToken}", $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 修改投放组件的奖品码。
 * 
 * @param unknown $accessToken
 * @param YDWXYaoTvPrizeCode $request
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_prize_code_set($accessToken, YDWXYaoTvPrizeCode $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/code/set?access_token={$accessToken}", $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 读取投放组件的奖品码。
 * 
 * @param unknown $accessToken
 * @param YDWXYaoTvPrizeCode $request
 * @param unknown $pageSize 
 * @param number $pageIndex 当前页，从1开始
 * @throws YDWXException
 * @return YDWXYaoTvPrizeCodeGetResponse
 */
function ydwx_yaotv_prize_code_get($accessToken, YDWXYaoTvPrizeCode $request, $pageSize, $pageIndex=1){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/code/get?access_token={$accessToken}&".$request->toString()."&pageIndex={$pageIndex}&pageSize={$pageSize}");
    $msg  = new YDWXYaoTvPrizeCodeGetResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 删除投放组件的奖品码。
 * 只删除状态为Editing编辑状态的奖品下的奖品码，状态为Finished完成状态的奖品下的奖品码不会被删除。
 * 
 * @param unknown $accessToken
 * @param YDWXYaoTvPrizeCode $request
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_prize_code_del($accessToken, YDWXYaoTvPrizeCode $request){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/code/del?access_token={$accessToken}&".$request->toString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 新增投放组件的奖品组。
 * 奖品组需要绑定至少一个奖品。每个奖品只能绑定到唯一奖品组，只有状态为Finished完成状态的奖品才能绑定到奖品组。
 * 奖品组状态为Finished完成状态时，无法对奖品组进行修改或删除，无法对绑定状态进行更改。
 * Finished完成状态的奖品组必须包含“奖品组占批次的权重”，投放规则组为可选。
 * 
 * @param unknown $accessToken
 * @param array $request YDWXYaoTvPrizeGroupAddRequest 数组
 * @throws YDWXException
 * @return YDWXYaoTvPrizeGroupAddResponse
 */
function ydwx_yaotv_prize_group_add($accessToken, array $request){
    $http = new YDHttp();
    $array= array(); 
    foreach ($request as $req){
        $array[] = $req->toArray();
    }
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/group/add?access_token={$accessToken}",
    ydwx_json_encode(array("list"=>$array)));
    $msg  = new YDWXYaoTvPrizeGroupAddResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 修改投放组件的奖品组。
 * 奖品组需要绑定至少一个奖品。每个奖品只能绑定到唯一奖品组，只有状态为Finished完成状态的奖品才能绑定到奖品组。
 * 奖品组状态为Finished完成状态时，无法对奖品组进行修改或删除，无法对绑定状态进行更改。
 * Finished完成状态的奖品组必须包含“奖品组占批次的权重”，投放规则组为可选。
 * 
 * @param unknown $accessToken
 * @param YDWXYaoTvPrizeGroupAddRequest $request
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_prize_group_set($accessToken, YDWXYaoTvPrizeGroupAddRequest $request){
    $http = new YDHttp();
    
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/group/set?access_token={$accessToken}",
    $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 读取投放组件的奖品组。
 * 
 * @param unknown $accessToken
 * @param unknown $id 奖品组id，不填写将会获取批量数据
 * @param unknown $pageIndex 当前页，从1开始
 * @param unknown $pageSize 分页大小，最大1000，如果记录超过1000，请使用分页，否则返回数据将缺失超过1000条的部分
 * @throws YDWXException
 * @return YDWXYaoTvPrizeGroupGetResponse
 */
function ydwx_yaotv_prize_group_get($accessToken, $pageIndex=1, $pageSize=1000, $id=null){
    $http = new YDHttp();

    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/group/get?access_token={$accessToken}&pageIndex={$pageIndex}&pageSize={$pageSize}&id={$id}");
    $msg  = new YDWXYaoTvPrizeGroupGetResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 删除投放组件的奖品组。
 * 只删除状态为Editing编辑状态的奖品组，状态为Finished完成状态的奖品组不会被删除。
 * 
 * @param unknown $accessToken
 * @param id 奖品组id，不填写将会删除状态为Editing编辑状态的全部数据
 * @throws YDWXException
 * @return YDWXYaoTvPrizeGroupGetResponse
 */
function ydwx_yaotv_prize_group_del($accessToken, $id){
    $http = new YDHttp();

    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/group/del?access_token={$accessToken}&id={$id}");
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 新增投放组件的投放批次
 * 
 * @param unknown $accessToken
 * @param array $request
 * @throws YDWXException
 * @return YDWXYaoTvPrizeBatchAddResponse
 */
function ydwx_yaotv_prize_batch_add($accessToken, array $request){
    $http = new YDHttp();
    $array= array();
    foreach ($request as $req){
        $array[] = $req->toArray();
    }
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/batch/add?access_token={$accessToken}",
    ydwx_json_encode(array("list"=>$array)));
    $msg  = new YDWXYaoTvPrizeBatchAddResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 修改投放组件的投放批次。
 * @param unknown $accessToken
 * @param YDWXYaoTvPrizeBatchAddRequest $request
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_prize_batch_set($accessToken, YDWXYaoTvPrizeBatchAddRequest $request){
    $http = new YDHttp();
    
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/batch/set?access_token={$accessToken}",
    $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 读取投放组件的投放批次。
 * @param unknown $accessToken
 * @param number $pageIndex 当前页，从1开始
 * @param number $pageSize 分页大小，最大1000，如果记录超过1000，请使用分页，否则返回数据将缺失超过1000条的部分
 * @param string $id 投放批次id，不填写将会获取批量数据
 * @throws YDWXException
 * @return YDWXYaoTvPrizeGroupGetResponse
 */
function ydwx_yaotv_prize_batch_get($accessToken, $pageIndex=1, $pageSize=1000, $id=null){
    $http = new YDHttp();

    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/batch/get?access_token={$accessToken}&pageIndex={$pageIndex}&pageSize={$pageSize}&id={$id}");
    $msg  = new YDWXYaoTvPrizeBatchGetResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 删除投放组件的投放批次。
 * 只删除状态为Editing编辑状态的投放批次，其他状态的投放批次不会被删除，投放批次绑定的奖品组、奖品、奖品码也不会被删除。
 * 
 * @param unknown $accessToken
 * @param unknown $id 投放批次id，不填写将会删除状态为Editing编辑状态的所有投放批次数据
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_prize_batch_del($accessToken, $id){
    $http = new YDHttp();

    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/batch/del?access_token={$accessToken}&id={$id}");
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 投放组件的投放批次预处理。调用本接口将配置好的投放批次以及批次下的奖品组、奖品、奖品码进行预处理，只有预处理完成的投放批次才能进行投放。
 * 
 * 特别注意：本接口返回成功指开始预处理，并不代表预处理完成。请使用读取投放批次接口查看状态。
 * 
 * 当投放批次的状态为Pause暂停状态或Playing开启状态时，表示预处理完成；为Editing编辑状态时，表示预处理失败，请再次预处理；为Syncing预处理状态时，表示正在预处理中。
 * @param unknown $accessToken
 * @param long $id 投放批次id
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_prize_batch_prepare($accessToken, $id){
    $http = new YDHttp();

    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/batch/set?access_token={$accessToken}",
    ydwx_json_encode(array("id"=>$id,"status"=>YDWXYaoTvPrizeBatchAddRequest::STATUS_SYNCING)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 手动控制投放组件的投放批次状态为暂停。
 * 只有模式为手动投放模式，投放批次预处理成功后即状态转为暂停状态的投放批次才能开始投放。投放完成后请将状态转为停止状态。
 * @param unknown $accessToken
 * @param unknown $id
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_prize_batch_pause($accessToken, $id){
    $http = new YDHttp();

    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/batch/set?access_token={$accessToken}",
    ydwx_json_encode(array("id"=>$id,"status"=>YDWXYaoTvPrizeBatchAddRequest::STATUS_PAUSE)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 手动控制投放组件的投放批次状态为开启状态。
 * 只有模式为手动投放模式，投放批次预处理成功后即状态转为暂停状态的投放批次才能开始投放。投放完成后请将状态转为停止状态。
 * @param unknown $accessToken
 * @param unknown $id
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_prize_batch_playing($accessToken, $id){
    $http = new YDHttp();

    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/batch/set?access_token={$accessToken}",
    ydwx_json_encode(array("id"=>$id,"status"=>YDWXYaoTvPrizeBatchAddRequest::STATUS_PLAYING)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 手动控制投放组件的投放批次状态为停止状态。
 * 只有模式为手动投放模式，投放批次预处理成功后即状态转为暂停状态的投放批次才能开始投放。投放完成后请将状态转为停止状态。
 * @param unknown $accessToken
 * @param unknown $id
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_prize_batch_close($accessToken, $id){
    $http = new YDHttp();

    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/batch/set?access_token={$accessToken}",
    ydwx_json_encode(array("id"=>$id,"status"=>YDWXYaoTvPrizeBatchAddRequest::STATUS_CLOSED)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 获取地理位置信息编码。当我国城市信息变动时，该数据将会随之变化，请以该接口结果为准。
 * 使用说明
 * 通过获取地理位置信息编码接口，可获得省市名称以及对应的省市Id。下面说明获得的数据如果组成投放组件中地理位置信息投放规则的参数。
 * 例如：A省的id为11，B省的id为12，C省的id为13，C省下有130300市、130400市、130500市、130600市等。如果奖品组投放包括A省全省、B省全省、C省的前面三个市，那么lbs参数如下所示：
 * lbs="11:0;12:0;13:130300;13:130400;13:130500;"
 * 
 * 如上所示：
 * 1、如果全选某省，lbs参数增加省id:0即可（lbs+="省id:0;"）；
 * 2、如果已经全选某省，不允许再增加该省id:市id；
 * 3、如果不选某省，则不在lbs参数标记；
 * 4、如果选全国所有省市，则lbs为全国id:0即可（lbs="0:0;"）；
 * 5、添加某省部分城市，则在lbs参数依次增加该省id:六位城市id即可（lbs+="省id:A城市id;省id:B城市id;"）；
 * 6、地理位置信息投放规则仅支持省市两级
 * 
 * @param unknown $accessToken
 * @throws YDWXException
 * @return array YDWXYaoTvLBSData组成的数组
 */
function ydwx_yaotv_lbsdata_get($accessToken){
    $http = new YDHttp();

    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/lbsdata/get?access_token={$accessToken}");
    $msg  = new YDWXResponse($info);
    $array = array();
    if($msg->isSuccess()){
        foreach ($msg->data['records'] as $record){
            $obj = new YDWXYaoTvLBSData();
            foreach ($record as $name=>$value){
                $obj->$name = $value;
            }
            $array[] = $obj;
        }
        return $array;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 在摇出来的页面里调用本接口，微信摇电视用户即可参与抽奖。
 * @param unknown $batch_key 某个投放批次密钥batchKey，注意此处调用参数命名法不同
 * @param long $latitude 用户所在地址的纬度，需要转化成整型，例如42.51457，则4251457
 * @param long $longitude 用户所在地址的经度，需要转化成整型，例如81.74521，则8174521
 * @param unknown $ratio_lbs 经纬度转化成整型的倍数，例如100000
 * @throws YDWXException
 * @return YDWXYaoTVLotteryResponse
 */
function ydwx_yaotv_lottery($batch_key, $latitude, $longitude, $ratio_lbs){
    $http = new YDHttp();

    $info = $http->get("http://yao.qq.com/cgi-bin/component/lotteryextra/draw?batch_key={batch_key}&latitude={latitude}&longitude={longitude}&ratio_lbs={ratio_lbs}");
    $msg  = new YDWXYaoTVLotteryResponse($info);
    $array = array();
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 构建互通卡券对应投放组件的奖品，并同时添加指定数量的奖品码到该奖品下。 
 * 调用本接口后，添加的奖品的状态已经是：Finished状态，无需再调用修改奖品接口改变奖品的状态到Finished，且无需再为该奖品添加奖品码。
 * 
 * @param unknown $accessToken
 * @param unknown $card_id card_id，MP平台的卡券id，如果制券方不是本电视台，则必须是互通到电视台名下的卡券
 * @param unknown $name name，卡券的title，对应投放系统是奖品的名称，例如“双十一满1000减100券”
 * @param unknown $quantity quantity，卡券的库存数量，对应投放系统是（奖品码）CODE的数量，必须大于0，且不要大于MP平台的库存数量，不然会出现少发或者多发卡券的情况
 * @throws YDWXException
 * @return YDWXYaoTVIntercardBuildResponse
 */
function ydwx_yaotv_intercard_build($accessToken, $card_id, $name, $quantity){
    $http = new YDHttp();

    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/intercard/build?card_id={$card_id}&name={$name}&quantity={$quantity}&access_token={$accessToken}");
    $msg  = new YDWXYaoTVIntercardBuildResponse($info);
    $array = array();
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 读取投放组件的话费券信息。
 * 
 * @param unknown $accessToken
 * @param number $pageIndex 当前页，从1开始
 * @param number $pageSize 分页大小，最大1000，如果记录超过1000，请使用分页，否则返回数据将缺失超过1000条的部分
 * @param string $id 投放批次id，不填写将会获取批量数据
 * @throws YDWXException
 * @return YDWXYaoTvPrizeTelecardGetResponse
 */
function ydwx_yaotv_prize_telecard_get($accessToken, $pageIndex=1, $pageSize=1000, $id=null){
    $http = new YDHttp();

    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/lotteryextra/prize/telecard/get?access_token={$accessToken}&pageIndex={$pageIndex}&pageSize={$pageSize}&id={$id}");
    $msg  = new YDWXYaoTvPrizeTelecardGetResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 发放中奖消息之前必须提前上传具体的奖品信息到电视台的奖品库，上传后经过审核人员审核后才可以下发奖品库中的奖品。
 * 1.上传奖品的奖品名称中，需提供以下信息，否则可能导则审核不通过：
 * （1）奖品类型：现金红包、卡券、实物奖品、体验机会四种类型
 * （2）奖品名称：
 * a. 现金红包类：
 * 要求明确现金金额
 * b. 实物奖品类：
 * 要求明确具体奖品名称
 * c. 体验机会类：
 * 要求明确体验地点（可以线下也可以线上）和体验目的（例如见明星、参观电视台等等）
 * d. 卡券类：
 * 代金券类的要求明确金额，明确发券商名
 * 其他类卡券都必须明确卡券用途，发券商名
 * 确保是有价值卡券，而不是例如环保小卫士卡等无价值虚拟荣誉
 * 具体规则以官方最新要求为准
 * 
 * @param unknown $accessToken
 * @param YDWXYaoTVAwardPrize $request
 * @throws YDWXException
 * @return long 奖品id
 */
function ydwx_yaotv_addawardprize($accessToken, YDWXYaoTVAwardPrize $request){
    $http = new YDHttp();

    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/template/addawardprize?access_token={$accessToken}", $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->data["prize_id"];
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 获取奖品及其审核状态
 * @param unknown $accessToken
 * @param unknown $prize_id
 * @throws YDWXYaoTVAwardPrize
 */
function ydwx_yaotv_getawardprize($accessToken, $prize_id){
    $http = new YDHttp();

    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/template/getawardprize?access_token={$accessToken}&prize_id={$prize_id}");
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        $obj = new YDWXYaoTVAwardPrize();
        foreach ($msg->data as $name => $value){
            $obj->$name = $value;
        }
        return $obj;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 通过添加中奖模板，可以在指定的时间（send_time，距离节目播出时间不能超过七天）将奖品发给中奖用户。
 * 中奖模板中的图标（template_icon，建议大小为100像素x100像素）、背景图片（template_background，
 * 建议大小为900像素x500像素）、奖品描述（template_text）是可以定制的，具体可以参考下面的图片。
 * 中奖用户不能重复。一次最多只能添加10000个，如果需要添加更多的中奖用户，请使用添加中奖用户接口。
 * 中将模板的节目播出时间和发送时间不能超过七天，否则将不发送。中奖用户必须是摇过本节目的，
 * 否则不发送给该用户。每个中奖用户可以获取多个奖品，但每个用户只能发送一次中奖通知。 
 * 页面下方“本页内容和活动由{自动填充电视台名}提供”，如需修改主体请发邮件到weixinyao@tencent.com联系摇电视团队申请。
 * @param unknown $accessToken
 * @param YDWXYaoTVAddAwardTemplate $request
 * @throws YDWXException
 */
function ydwx_yaotv_addawardtemplate($accessToken, YDWXYaoTVAddAwardTemplate $request){
    $http = new YDHttp();

    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/template/addawardtemplate?access_token={$accessToken}", $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->data["template_id"];
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 查看中奖模板及其发送状态
 * 
 * @param unknown $accessToken
 * @param unknown $template_id 中奖模板id
 * @throws YDWXException
 * @return YDWXYaoTVGetAwardTemplate
 */
function ydwx_yaotv_getawardtemplate($accessToken, $template_id){
    $http = new YDHttp();

    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/template/getawardtemplate?access_token={$accessToken}&template_id={$template_id}");
    $msg  = new YDWXYaoTVGetAwardTemplate($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 增加中奖用户
 * 
 * 中奖用户不能重复，一次最多只能添加10000个中奖用户，每个中奖用户可以获取多个奖品。
 * 中奖用户必须是摇过本节目，所有中奖用户的通知都会在中奖模板指定的发送时间（send_time）发送，如果中奖用户的添加时间超过了发送时间，将不发送中奖通知给该用户。
 * 
 * @param unknown $accessToken
 * @param unknown $template_id 中奖模板id，必填
 * @param array $winners YDWXYaoTVAwardWinner数组
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_addawardwinner($accessToken, $template_id, array $winners){
    $http = new YDHttp();

    $arr = array();
    foreach ($winners as $winner){
        $arr[] = $winner->toArray();
    }
    
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/template/addawardwinner?access_token={$accessToken}",
            ydwx_json_encode(array("template_id"=>$template_id, "winners"=>$arr))
    );
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 获取中奖用户及其发送状态
 * @param unknown $accessToken
 * @param unknown $template_id
 * @param unknown $openid
 * @throws YDWXException
 * @return YDWXYaoTVGetAwardwinnerResponse
 */
function ydwx_yaotv_getawardwinner($accessToken, $template_id, $openid){
    $http = new YDHttp();

    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/template/getawardwinner?access_token={$accessToken}&openid={$openid}&template_id={$template_id}");
    $msg  = new YDWXYaoTVGetAwardwinnerResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

function ydwx_yaotv_js_include(){
?>
<script type="text/javascript" src="https://wximg.gtimg.com/shake_tv/include/js/jsapi.js"></script>
<?php
}

/**
 * 上传节目单后，系统会针对每一个节目生成一个预约id（在“查看节目活动表”接口，可以看到reserveid字段），
 * 利用该预约id，调用此接口，后台查询发现有相应的预约信息，会拉起预约的浮层，用户确认后点击确认预约，即可成功预约。预约信息的录入请参考API文档。
 * 
 * 接口参数，预约单期节目
 * tvid	电视台id （不能用节目方的tvid）
 * reserveid	节目预约id，在api“查看节目活动表”里有返回该字段
 * date	节目开始的日期,格式"yyyyMMdd"，如"20150618"
 * 
 * 预约节目序列
 * tvid	电视台id （不能用节目方的tvid）
 * seriesid 节目系列id，在api“节目系列管理/查看节目系列”里有返回该字段
 * 
 * cb 参数errcode，errmsg
 * errcode 
 * 0	成功
 * -1002	用户取消
 * -1006	预约消息未通过审核
 * -1007	已预约
 * 
 * 注意：
 * 1.预约多期JSAPI限制在qq.com域下调用。除了摇出来的页面可以调用外，也支持分享页面调用，对于分享出去的页面，请在分享打开的目标url前加跳转cgi：http://yao.qq.com/tv/entry?redirect_uri=xxx (其中xxx是目标页面的url，url必须是在yaotv.qq.com域名的)。 
 * 2. 摇电视摇出来的页面会自动在cookie中带上yyytv_token，非摇场景会出现错误提示缺少token。如果测试中提示缺少token，请在目标url前加跳转cgi：http://yao.qq.com/tv/entry?redirect_uri=xxx (其中xxx是目标页面的url，url必须是在yaotv.qq.com域名的)，并在微信中打开即可在cookie中种上yyytv_token。
 */
function ydwx_yaotv_reserve_v2_jsapi(){
?>
function ydwx_yaotv_reserve_v2(tvid, rid, date, cb){
    shaketv.reserve_v2({
        tvid:tvid,
        reserveid:rid,
        date:date
    },function(d){
            cb(d.errorCode, d.errorMsg);
        }
    );
}
<?php 
}

/**
 * 查询预约的状态
 * 
 * 接口参数，预约单期节目
 * tvid	电视台id （不能用节目方的tvid）
 * reserveid	节目预约id，在api“查看节目活动表”里有返回该字段
 * date	节目开始的日期,格式"yyyyMMdd"，如"20150618"
 * 
 * 预约节目序列
 * tvid	电视台id （不能用节目方的tvid）
 * seriesid 节目系列id，在api“节目系列管理/查看节目系列”里有返回该字段
 * 
 * cb 参数errcode，errmsg
 * errcode 
 * 0	成功
 * -1002	用户取消
 * -1006	预约消息未通过审核
 * -1007	已预约
 * 
 * 注意：
 * 1.预约多期JSAPI限制在qq.com域下调用。除了摇出来的页面可以调用外，也支持分享页面调用，对于分享出去的页面，请在分享打开的目标url前加跳转cgi：http://yao.qq.com/tv/entry?redirect_uri=xxx (其中xxx是目标页面的url，url必须是在yaotv.qq.com域名的)。 
 * 2. 摇电视摇出来的页面会自动在cookie中带上yyytv_token，非摇场景会出现错误提示缺少token。如果测试中提示缺少token，请在目标url前加跳转cgi：http://yao.qq.com/tv/entry?redirect_uri=xxx (其中xxx是目标页面的url，url必须是在yaotv.qq.com域名的)，并在微信中打开即可在cookie中种上yyytv_token。
 */
function ydwx_yaotv_preReserve_v2_jsapi(){
    ?>
function ydwx_yaotv_preReserve_v2(tvid, rid, date, cb){
    shaketv.reserve_v2({
        tvid:tvid,
        reserveid:rid,
        date:date
    },function(d){
            cb(d.errorCode, d.errorMsg);
        }
    );
}
<?php 
}

/**
 * 调用此接口，可以自定义预约消息的图标和点击后打开的详情页面。自定义图标和详情页的预约需要经过审核才能正常下发。
 * @param unknown $accessToken
 * @param YDWXYaoTvProgramReserveAddRequest $request
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_program_reserve_add($accessToken, YDWXYaoTvProgramReserveAddRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/program/reserve/new/add.json?access_token={$accessToken}", $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 删除已有的预约信息
 * @param unknown $accessToken
 * @param unknown $reserveId 预约id，在api“查看节目活动表”里有返回该字段 reserveId 和 seriesId 两者必须选其中之一填写。
 * @param unknown $seriesId 节目系列id，在api“查询节目系列”里有返回该字段 reserveId 和 seriesId 两者必须选其中之一填写。
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_yaotv_program_reserve_del($accessToken, $reserveId, $seriesId){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/program/reserve/new/del.json?access_token={$accessToken}&reserveId={$reserveId}&seriesId={$seriesId}");
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 根据预约ID查询预约信息。
 * 通过此接口，可以查询通过“新增预约信息v2”接口进行了自定义设置的预约消息，不包含未自定义设置过的预约ID。
 * 
 * @param unknown $accessToken
 * @param unknown $reserveId 预约id，在api“查看节目活动表”里有返回该字段 reserveId 和 seriesId 两者必须选其中之一填写。
 * @param unknown $seriesId 节目系列id，在api“查询节目系列”里有返回该字段 reserveId 和 seriesId 两者必须选其中之一填写。
 * @throws YDWXException
 * @return YDWXYaoTvProgramReserveResponse
 */
function ydwx_yaotv_program_reserve_get($accessToken, $reserveId, $seriesId){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/program/reserve/new/get.json?access_token={$accessToken}&reserveId={$reserveId}&seriesId={$seriesId}");
    $msg  = new YDWXYaoTvProgramReserveResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 设置抽奖有效期，抽奖开关，等基本信息，返回抽奖id
 * @param unknown $accessToken
 * @param YDWXYaoTvLotteryInfoAddRequest $request
 * @throws YDWXException
 * @return string 生成的抽奖id
 */
function ydwx_yaotv_addlotteryinfo($accessToken, YDWXYaoTvLotteryInfoAddRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/user/addlotteryinfo?access_token={$accessToken}", $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->data['lottery_id'];
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 注意，此接口每次调用，都会新增一批奖品信息，如果奖品数少于100个，请通过一次调用添加所有奖品信息。如果奖品数大于100，可以多次调用接口添加。
 * 
 * @param unknown $accessToken
 * @param YDWXYaoTvSetPrizeBucketRequest $request
 * @throws YDWXException
 * @return string 当前奖品集id
 */
function ydwx_yaotv_setprizebucket($accessToken, YDWXYaoTvSetPrizeBucketRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/user/setprizebucket?access_token={$accessToken}", $request->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->data['lottery_id'];
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}
/**
 * 在调用"添加红包抽奖信息"接口之后，调用此接口录入红包信息。注意，此接口每次调用，都会新增一批红包信息，如果红包数少于100个，请通过一次调用添加所有奖品信息。如果红包数大于100，可以多次调用接口添加。
 * @param unknown $accessToken
 * @param YDWXYaoTVSetPrizeBucket4HBRequest $request
 * @throws YDWXException
 * @return YDWXYaoTVSetPrizeBucket4HBResponse
 */
function ydwx_yaotv_setprizebucket4hb($accessToken, YDWXYaoTVSetPrizeBucket4HBRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."yaotv/user/setprizebucket4hb?access_token={$accessToken}", $request->toJSONString());
    $msg  = new YDWXYaoTVSetPrizeBucket4HBResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}


/**
 * 开发者实时控制抽奖的开启和关闭。注意抽奖开关只在抽奖有效期之内才能生效，如果不能确定抽奖有效期，请尽量将奖品有效期的范围设置大。
 * 
 * @param unknown $accessToken
 * @param unknown $lottery_id
 * @param unknown $onoff
 * @throws YDWXException
 * @return boolean
 */
function ydwx_yaotv_setlotteryswitch($accessToken, $lottery_id, $onoff){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/user/setlotteryswitch?access_token={$accessToken}&lottery_id={$lottery_id}&onoff=".($onoff ? "1" : "0"));
    $rst = new YDWXResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg, $rst->errcode);
    
    return true;
}
/**
 * 根据抽奖id和奖品集id获取奖品列表，用于核对奖品信息是否设置成功
 * @param unknown $accessToken
 * @param unknown $lottery_id 抽奖id
 * @param unknown $prize_bucket_id 当前奖品集合id
 * @throws YDWXException
 * @return array
 */
function ydwx_yaotv_getprizebucket($accessToken, $lottery_id, $prize_bucket_id){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/user/getprizebucket?access_token={$accessToken}&lottery_id={$lottery_id}&prize_bucket_id={$prize_bucket_id}");
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        $arr = array();
        foreach ($msg->prize_info_list as $i){
            $obj = new YDWXYaoPrizeExtInfo();
            foreach ($i as $name=>$value){
                $obj->$name = $value;
            }
            $arr[] = $obj;
        }
        return $arr;
    }
    throw new YDWXException($msg->errmsg, $msg->errcode);
}

/**
 * 列表按照中奖顺序排序，采用分页返回，直到拉取到最后一页如果没有数据，表示已经拉取完毕。此接口若在抽奖进行中调用，中奖结果列表会实时更新。
 * @param unknown $accessToken
 * @param unknown $lottery_id 抽奖id
 * @param unknown $page_index 页码，从0开始
 * @param unknown $page_size 每页拉取的中奖人个数，最大20
 * @throws YDWXException
 * @return multitype:YDWXYaoPrizeExtInfo
 */
function ydwx_yaotv_getWinnerInfoByPage($accessToken, $lottery_id, $page_index, $page_size){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/user/getwinnerinfobypage?access_token={$accessToken}&lottery_id={$lottery_id}&page_index={$page_index}&page_size={$page_size}");
    $rst = new YDWXResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg, $rst->errcode);

    $arr = array();
    foreach ($msg->winnerlist as $i){
        $obj = new YDWXYaoPrizeWinnerInfo();
        foreach ($i as $name=>$value){
            $obj->$name = $value;
        }
        $arr[] = $obj;
    }
    return $arr;
}

/**
 * 在页面中，通过调用JSAPI来触发用户抢红包的操作，如果抢到红包，会呼出微信的原生红包页面(需6.1以上的微信版本)。该接口会在后台对用户请求进行校验和过滤，当请求过于频繁时，会要求传入验证码。用户只有在摇电视的场景下才能抽中红包，分享到朋友圈和好友的页面中发起该请求，将不会抽中红包。每个用户在一个抽奖id下最多只能中一个红包。
 * ydwx_yaotv_hongbao_js_api 参数：
 *  lottery_id
 *  key 添加红包抽奖信息”接口设置的key
 *  userid 用户唯一标识，可以是openid或是第三方自定义的用户id。选填
 *  captcha 验证码，4位，默认不需要填写。
 * 如果调用此接口后返回需要验证码（返回码10008），则该接口会同时返回验证码url。开发者需要将验证码在页面中展示，并将用户填写的验证码再次调用ydwx_yaotv_hongbao_js_api
 *  cb 参数cb(bool, captcha); bool true表示中奖了，false表示没中奖，captcha表示验证码
 * 
 * @return string
 */
function ydwx_yaotv_hongbao_js_api(){
    ob_start();
    ?>
function ydwx_yaotv_hongbao_js_api(lottery_id, key, userid,captcha, cb){
    $.post("<?php echo YDWX_SITE_URL."ydwx/yaotv_hb.php"?>",
            {lottery_id:  lottery_id,
                key:  key,
                userid:  userid,
                captcha:  captcha,
                    },
            function(rst){
            shaketv.hongbao({
                userid: userid,
                lottery_id:lottery_id,
                noncestr:rst.noncestr，
                sign:rst.sign，
                captcha:rst.captcha，
                }，function(d){
                    cb(! d.errorCode, d.captcha);
                })
    },"json");
}
<?php 
    return ob_get_clean();
}

/**
 * 在合适的时机，调用JSAPI，注册微信的分享事件，可以自定义分享的页面的title、图片、概述。仅限于qq.com域名的页面调用。
 * ydwx_yaotv_wxShare 参数
 * img	图片url
 * title	标题
 * desc	概述
 * url	分享打开的链接，如果不填，默认为当前页面
 * @return string
 */
function ydwx_yaotv_wxShare_js_api(){
    ob_start();
    ?>
function ydwx_yaotv_wxShare(img, title, desc, url){
    shaketv.wxShare(img,title,desc,url);
}
<?php 
    return ob_get_clean();
}

/**
 * 通过调用用户信息授权接口，可以获取用户信息的加密code，appID和secretKey获取access_token，并使用该code即可通过摇电视api接口获取用户信息。
 * 如果是需要获取用户的头像、昵称，调用该接口时会出现一个授权提示框，只有用户点击确定才可以获得code。
 * ydwx_yaotv_authorize(appid, type, callback)
 * appid	从摇电视平台申请到的appid
 * type	获取的code类型，可选值 1.base（不会出现授权提示框，返回的code只能换取openid）2.userinfo (会出现授权提示框，返回的code可以换取用户头像、昵称等信息)
 * callback_function	处理调用结果，参数:cb(result, userinfo)
 * 
 * result
 * 0	成功 userinfo返回用户的信息
 * -1001	用户取消
 * -1002	授权窗口打开超时
 * 101	请确认是否是通过摇的，参见注意2
 * 
 * 获取用户信息会触发hook YDWXHook::YAOTV_USER_AUTH
 * 
 * @return string
 */

function ydwx_yaotv_authorize_js_api(){
    ob_start();
    ?>
function ydwx_yaotv_authorize(appid, type, callback){
    shaketv.authorize(appid, type, function(rst){
        $.get("<?php echo YDWX_SITE_URL."ydwx/yaotv_auth.php"?>",
            {code:  rst.code,
             type:  type,
             appid:  appid
             },
            function(ydwxrst){
            callback(rst.errorCode, ydwxrst.data);
    },"json");
    });
}
<?php 
    return ob_get_clean();
}

/**
 * 使用token和code获取用户信息的api
 * @param unknown $appid
 * @param unknown $access_token
 * @param unknown $code ydwx_yaotv_authorize jsapi 得到的code
 * @throws YDWXException
 * @return YDWXOAuthUser
 */
function ydwx_yaotv_get_userinfo($appid, $access_token, $code){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."yaotv/user/userinfo?appid={$appid}&access_token={$access_token}&code={$code}");
    $rst = new YDWXOAuthUser($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg, $rst->errcode);
    $rst->appid = $appid;
    return $rst;
}

/**
 * 引用JS后，在需要时调用JSAPI，调用后，JSAPI会在服务器校验是否允许使用一键关注的功能，如果允许使用，将在指定位置显示相应的入口。
 * 
 * js api 参数
 * appid:,//需要关注的公众号的appid
 * container:,//需要显示关注bar的指定div的selector，如"#test",".test"。未指定selector，关注bar将默认用position:relative;的样式加到页面中
 * color:,//关注bar的颜色类型，1灰色，2白色。默认是灰色。
 * cb: //一键关注bar消失后会调用回调函数，在此处理bar消失后带来的样式问题
 * 
 * 必须是摇电视摇出的页面，才可以看到关注的入口。
- 关注的公众号需要提前提交申请，电视台账号或子账号可以在后台“高级设置”中自助申请。

一键关注的位置需要事先调好位置，并满足以下要求
1.位置：嵌入页面最底部
页面开发方在开发页面时在页面最底部预留一键关注组件的摆放位置
翻页式页面：最后一页页面最底部
2.关注后效果：用户成功关注后底部bar消失
建议：底部bar消失后不要留下影响页面视觉效果和用户体验的痕迹
3.颜色搭配：灰色和白色两套组件颜色选择
建议：选择搭配页面底色的组件，比如深色配灰色系组件，浅色配白色系组件
4.使用以下html代码调试样式,以便一键关注能出现在特定的位置（上线前记得将iframe节点删掉）
&lt;div  id="div_subscribe_area" style=""&gt;
&lt;iframe id="iframe_subscribe_footer" src="http://yaotv.qq.com/shake_tv/include/js/iframe_subscribe_footer_test.html" style="border: 0px;height: 50px;width: 100%;top: 0;left: 0; z-index:10000;"&gt;&lt;/iframe&gt;
&lt;/div&gt;
5. 摇电视摇出来的页面会自动在cookie中带上yyytv_token，非摇场景提示缺少token。如果测试中提示缺少token，请在目标url前加跳转cgi：http://yao.qq.com/tv/entry?redirect_uri=xxx (其中xxx是目标页面的url，url必须是在yaotv.qq.com域名的)，并在微信中打开即可在cookie中种上yyytv_token。
 * @return string
 */
function ydwx_yaotv_subscribe_js_api(){
    ob_start();
    ?>
function ydwx_yaotv_subscribe(appid, container, color, cb){
    shaketv.subscribe({appid:appid, selector:container, type:color,callback:function(rst){
    cb(rst);
    }});
}
<?php 
    return ob_get_clean();
}

/**
 * 直播模式下，用户摇出来之后，如果没有关闭页面，这时如果在管理后台切换了页面，用户侧是不会自动更新的，需要重新摇进来才能更新，为了能自动的更新，可以使用“直播自动刷新”的JSAPI
 * js api 参数
 * step	轮询间隔时间，单位是秒，默认15s轮询一次，最小1s，最大120s。
 * cb: 处理调用结果，会回传一个json {errorCode:0,errorMsg:"",url:""}的参数
 * 
 * 0	成功，直播页面有切换。
-1001	参数有误
-1002	一般是打开方式不对，不是摇出来的，轮询结束
-1003	获取直播信息失败，后台返回失败，会继续下次轮询，重试3次依旧失败，会自动结束轮询
-1004	当前直播的节目没有生效的活动，或者活动状态被重置，会继续下次轮询
-1005	请求暂不可用，会继续下次轮询,重试3次依然不可用，会自动结束轮询
 * @return string
 */
function ydwx_yaotv_live_js_api(){
    ob_start();
    ?>
function ydwx_yaotv_live(step, cb){
    shaketv.live({step:step,callback:function(rst){
    cb(rst);
    }});
}
<?php 
    return ob_get_clean();
}

/**
 * 调用接口，获取用户进入互动的场景，执行回调函数。
 * callback是shaketv.getScenes组件调用后的回调函数。callback的参数是用户进入到互动的场景值（整数）和一句提示语。
 * 
 * 1	从“摇电视”入口进入互动
2	从“互动预告页”入口进入互动
0	从其它入口进入互动，如好友分享
-1005	客户端发起网络请求失败
其它值	获取用户信息出错
 * @return string
 */
function ydwx_yaotv_getScenes_js_api(){
    ob_start();
    ?>
function ydwx_yaotv_getScenes(cb){
    shaketv.getScenes(function(scenesCode， msg){
    cb(scenesCode， msg);
    });
}
<?php 
    return ob_get_clean();
}