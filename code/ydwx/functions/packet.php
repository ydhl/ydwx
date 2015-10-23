<?php
/**
 * 红包接口
 */

/**
 * 红包预下单接口
 * 
 * 设置单个红包的金额，类型等，生成红包信息。预下单完成后，需要在72小时内调用jsapi完成抽红包的操作。（红包过期失效后，资金会退回到商户财付通帐号。）
 * 
 * @param YDWXPacketPreorderRequest $request
 * @throws YDWXException
 * @return YDWXPacketPreorderResponse
 */
function ydwx_packet_preorder(YDWXPacketPreorderRequest $request){
    $http = new YDHttps($request->wxappid);
    $request->sign();
    $info = $http->post(YDWX_WEIXIN_PAY_URL."mmpaymkttransfers/hbpreorder",
    $request->toXMLString());
    $rst = new YDWXPacketPreorderResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg.$rst->errcode, $rst->errcode);
    
    return $rst;
}

/**
 * 创建红包活动
 * 创建红包活动，设置红包活动有效期，红包活动开关等基本信息，返回活动id
 * @param unknown $accessToken
 * @param YDWXPacketAddLotteryInfoRequest $request
 * @param unknown $logourl 使用模板页面的logo_url，不使用模板时可不加。展示在摇一摇界面的消息图标。图片尺寸为120x120。
 * @param boolean $useTemplate 是否使用模板, 模版即交互流程图中的红包加载页，使用模板用户不需要点击可自动打开红包；不使用模版需自行开发HTML5页面，并在页面调用红包jsapi）
 * @throws YDWXException
 * @return YDWXPacketAddLotteryInfoResponse
 */
function ydwx_packet_addlotteryinfo($accessToken, YDWXPacketAddLotteryInfoRequest $request, $logourl, $useTemplate=true){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/lottery/addlotteryinfo?access_token={$accessToken}&use_template=".($useTemplate ? 1 : 2)."&logo_url={$logourl}", $request->toJSONString());
    $rst = new YDWXPacketAddLotteryInfoResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg, $rst->errcode);
    
    return $rst;
}

/**
 * 录入红包信息
 * 在调用"创建红包活动"接口之后，调用此接口录入红包信息。注意，此接口每次调用，
 * 都会向某个活动新增一批红包信息，如果红包数少于100个，请通过一次调用添加所有红包信息。
 * 如果红包数大于100，可以多次调用接口添加。请注意确保多次录入的红包ticket总的数目不大于
 * 创建该红包活动时设置的total值。
 * 
 * @param unknown $accessToken
 * @param YDWXPacketSetPrizeBucketRequest $request
 * @throws YDWXException
 * @return YDWXPacketSetPrizeBucketResponse
 */
function ydwx_packet_setprizebucket($accessToken, YDWXPacketSetPrizeBucketRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."shakearound/lottery/setprizebucket?access_token={$accessToken}", $request->toJSONString());
    $rst = new YDWXPacketSetPrizeBucketResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg.$request->toJSONString(), $rst->errcode);
    
    return $rst;
}

/**
 * 设置红包活动抽奖开关
 * 
 * 开发者实时控制红包活动抽奖的开启和关闭。
 * 注意活动抽奖开关只在红包活动有效期之内才能生效，
 * 如果不能确定红包活动有效期，请尽量将红包活动有效期的范围设置大。
 * 
 * @param unknown $accessToken
 * @param unknown $lottery_id 红包抽奖id，来自addlotteryinfo返回的lottery_id
 * @param boolean $onoff 活动抽奖开关，false：关闭，true：开启
 * @throws YDWXException
 * @return boolean
 */
function ydwx_packet_setlotteryswitch($accessToken, $lottery_id, $onoff){
    $http = new YDHttp();
    $info = $http->get(YDWX_WEIXIN_BASE_URL2."shakearound/lottery/setlotteryswitch?access_token={$accessToken}&lottery_id={$lottery_id}&onoff=".($onoff ? "1" : "0"));
    $rst = new YDWXResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg, $rst->errcode);
    
    return true;
}

/**
 * 红包JSAPI
 * 在第三方页面中，通过调用JSAPI来触发用户抽红包的操作，
 * 如果抽到红包，会呼出微信的原生红包页面。
 * 用户只有通过摇周边的入口才能抽中红包。每个用户在一个活动抽奖id下最多
 * 只能中一个红包。创建红包活动时，选择使用模板页面的开发者不需要调用该接口
 * 
 * openid 当前访问页面的用户openid 可以在访问页面之前是否微信用户“登录”了（之前已经访问过后，把openid记在会话中）;
 * 如果没有登录，则把请求转向auth.php或则baseauth.php
 * 对于h5页面，微信也是一个浏览器，具有会话
 * 
 * @param $lottery_id 红包抽奖id，必填，来自addlotteryinfo返回的lottery_id
 * @param $key 通过“创建红包活动” ydwx_packet_addlotteryinfo 接口设置的key
 * @param $openid 当前访问页面的用户openid 可以在访问页面之前是否微信用户“登录”了（之前已经访问过后，把openid记在会话中）;如果没有登录，则把请求转向auth.php或则baseauth.php
 * 
 * @return string
 */
function ydwx_packet_shake_js_api($lottery_id, $key, $openid){
    ob_start();
?>
    <script type="text/javascript" src="http://zb.weixin.qq.com/app/shakehb/BeaconShakehbJsBridge.js"></script>
    <script type="text/javascript">
    $.post("<?php echo YDWX_SITE_URL."ydwx/packet.php"?>",
            {lottery_id:  '<?php echo $lottery_id?>',
                key:  '<?php echo $key?>',
                openid:  '<?php echo $openid?>'},
            function(rst){
        BeaconShakehbJsBridge.ready(function(){
            BeaconShakehbJsBridge.invoke('jumpHongbao',{
                lottery_id:  '<?php echo $lottery_id?> ',
                noncestr:    rst.data.noncestr,
                openid:      <?php echo $openid?>,
                sign:        rst.data.sign
           });
       });
    });
    </script>
<?php 
    return ob_get_clean();
}

/**
 * 用于商户对已发放的红包进行查询红包的具体信息，可支持普通红包和裂变包。
 * @param YDWXPacketGetHBInfoRequest $request
 * @throws YDWXException
 * @return YDWXPacketGetHBInfoResponse
 */
function ydwx_packet_gethbinfo(YDWXPacketGetHBInfoRequest $request){
    $http = new YDHttps($request->appid);
    $request->sign();
    $info = $http->post(YDWX_WEIXIN_PAY_URL."mmpaymkttransfers/gethbinfo",
            $request->toXMLString());
    $rst = new YDWXPacketGetHBInfoResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg.$rst->errcode.$http->error, $rst->errcode);
    
    return $rst;
}

/**
 * 用于企业向微信用户个人发现金红包,目前支持向指定微信用户的openid发放指定金额红包
 * 
 * @param YDWXPacketSendRequest $request
 * @throws YDWXException
 * @return YDWXPacketSendResponse
 */
function ydwx_packet_send(YDWXPacketSendRequest $request){
    $http = new YDHttps($request->wxappid);
    $request->sign();
    $info = $http->post(YDWX_WEIXIN_PAY_URL."mmpaymkttransfers/sendredpack",
            $request->toXMLString());
    $rst = new YDWXPacketSendResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg.$rst->errcode.$http->error, $rst->errcode);

    return $rst;
}

/**
 * 用于企业向微信用户个人发裂变红包
 * 目前支持向指定微信用户的openid发放指定金额裂变红包
 * 对应红包的领取情况，可通过ydwx_packet_gethbinfo获取
 * @param YDWXPacketSendGroupRequest $request
 * @throws YDWXException
 * @return YDWXPacketSendGroupResponse
 */
function ydwx_packet_send_group(YDWXPacketSendGroupRequest $request){
    $http = new YDHttps($request->wxappid);
    $request->sign();
    $info = $http->post(YDWX_WEIXIN_PAY_URL."mmpaymkttransfers/sendgroupredpack",
            $request->toXMLString());
    $rst = new YDWXPacketSendGroupResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg.$rst->errcode.$http->error, $rst->errcode);

    return $rst;
}