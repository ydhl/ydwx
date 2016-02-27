<?php
/**
 * 卡券相关接口
 */


/**
 * 上传卡券LOGO
 * 
 * 1.上传的图片限制文件大小限制1MB，像素为300*300，仅支持JPG、PNG格式。
 * 2.调用接口获取的logo_url仅支持在微信相关业务下使用。
 * 
 * @see http://mp.weixin.qq.com/wiki/8/b7e310e7943f7763450eced91fa793b0.html#.E6.AD.A5.E9.AA.A4.E4.B8.80.EF.BC.9A.E4.B8.8A.E4.BC.A0.E5.8D.A1.E5.88.B8LOGO
 * @param unknown $accessToken
 * @param unknown $buffer
 * @throws YDWXException
 * @return string logo url地址
 */
function ydwx_card_uploadlogo($accessToken, $buffer){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."media/uploadimg?access_token={$accessToken}",
    array("buffer"=>"@".$buffer) ,true);
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->url;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 创建卡券
 * 创建卡券接口是微信卡券的基础接口，用于创建一类新的卡券，获取card_id，创建成功并通过审核后，商家可以通过文档提供的其他接口将卡券下发给用户，每次成功领取，库存数量相应扣除。
 * 开发者须知
 * 1.需自定义Code码的商家必须在创建卡券时候，设定use_custom_code为true，且在调用投放卡券接口时填入指定的Code码。指定OpenID同理。特别注意：在公众平台创建的卡券均为非自定义Code类型。
 * 2.can_share字段指领取卡券原生页面是否可分享，建议指定Code码、指定OpenID等强限制条件的卡券填写false。
 * 
 * @param unknown $accessToken
 * @param YDWXCardBase $create 传入它的子类 如礼品券YDWXCardGift
 * @throws YDWXException
 * @param string 卡券id
 */
function ydwx_card_create($accessToken, YDWXCardBase $create){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/create?access_token={$accessToken}",
    $create->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->card_id;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * Code解码接口
 * 
 * code解码接口支持两种场景： 
 * 1.商家获取choos_card_info后，将card_id和encrypt_code字段通过解码接口，获取真实code。 
 * 2.卡券内跳转外链的签名中会对code进行加密处理，通过调用解码接口获取真实code。
 * @param unknown $accessToken
 * @param unknown $code
 * @throws YDWXException
 */
function ydwx_card_code_decrypt($accessToken, $code){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/code/decrypt?access_token={$accessToken}",
    ydwx_json_encode(array('encrypt_code'=>$code)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->code;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 对于卡券外链打开的url，验证是否是微信过来的.
 * 在外链访问的第一个页面使用，其他页面使用无效
 */
function ydwx_card_verify_referer_is_weixin($accessToken){
    if( ! @$_GET['signature'])return false;
    $array[] = @$_GET['appscret'];
    $array[] = ydwx_card_code_decrypt($accessToken, @$_GET['code']);
    $array[] = @$_GET['card_id'];
    sort($array);
    
    $signature = sha1(join($array));
    
    return @$_GET['signature']===$signature;
}

/**
 * 对于某张卡，开通开通买单功能
 * 在调用买单接口之前，请务必确认是否已经开通了微信支付以及对相应的cardid设置了门店，否则会报错。
 * 
 * 开通指引
 * 
 * 步骤一：申请开通内测白名单权限后，开发者可以登录微信公众平台mp.weixin.qq.com，进入【卡券功能】-【卡券概况】，点击查看资料和权限
 * 步骤二：在高级权限区，有标注微信买单的权限状态，商户先需要开通微信支付，并为收款门店配置核销员，才能激活申请权限。未获得权限时，点击“申请“，开通买单权限
 * 步骤三：为收款门店配置收款员“或直接点击”卡券核销“，可前往添加门店核销员，便于后续接收结算通知。
 * 
 * 核销后微信会通知，请住处YDWXHook::EVENT_USER_CONSUME_CARD
 * 
 * @param unknown $accessToken
 * @param unknown $cardid
 * @throws YDWXException
 * @return boolean
 */
function ydwx_card_paycell_open($accessToken, $cardid){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/paycell/set?access_token={$accessToken}",
    ydwx_json_encode(array('card_id'=>$cardid,"is_open"=>true)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 创建卡券二维码，扫描二维码领取一张卡券
 * 
 * @param unknown $accessToken
 * @param YDWXCardQrcodeRequest $request
 * @throws YDWXException
 * @return YDWXCardQrcodeResponse
 */
function ydwx_card_qrcode_create($accessToken, YDWXCardQrcodeRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/qrcode/create?access_token={$accessToken}",
    $request->toJSONString());
    $msg  = new YDWXCardQrcodeResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 创建卡券二维码，扫描二维码领取多张卡券
 * 
 * @param unknown $accessToken
 * @param YDWXMultiCardQrcodeRequest $request
 * @throws YDWXException
 * @return YDWXCardQrcodeResponse
 */
function ydwx_multicard_qrcode_create($accessToken, YDWXMultiCardQrcodeRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/qrcode/create?access_token={$accessToken}",
    $request->toJSONString());
    $msg  = new YDWXCardQrcodeResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}


/**
 * 输出微信内网页调起卡券选择js接口ydwx_card_chooseCard,需要先调用ydwx_jsapi_config
 * 
 * shopId 门店ID。shopID用于筛选出拉起带有指定location_list(shopID)的卡券列表，非必填
 * cardType 卡券类型，用于拉起指定卡券类型的卡券列表。当cardType为空时，默认拉起所有卡券的列表，非必填。
 * cardId 卡券ID，用于拉起指定cardId的卡券列表，当cardId为空时，默认拉起所有卡券的列表，非必填
 * callback 选择卡券后回调的js函数
 * @param $appid 作为第三方平台，配置某个授权公众号
 * @return string
 */
function ydwx_card_chooseCard($appid=null){
    ob_start();
    ?>
    <script type="text/javascript">
    function ydwx_card_chooseCard(shopId, cardType, cardId, callback){
        $.post("<?php echo YDWX_SITE_URL."ydwx/card.php"?>",{
            action:     "chooseCard",
            shopId:     shopId, // 门店Id
            appid:      "<?php echo $appid?>",
            cardType:   cardType, // 卡券类型
            cardId:     cardId, // 卡券Id
            },function(data){
                if(!data || !data.success) {
                    return;
                }
                wx.chooseCard({
                    shopId:     shopId, // 门店Id
                    cardType:   cardType, // 卡券类型
                    cardId:     cardId, // 卡券Id
                    timestamp:  data.timestamp, // 卡券签名时间戳
                    nonceStr:   data.nonceStr, // 卡券签名随机串
                    signType:   'SHA1', // 签名方式，默认'SHA1'
                    cardSign:   data.cardSign, // 卡券签名
                    success: function (res) {
                        var cardList= res.cardList; // 用户选中的卡券列表信息
                        callback(cardList);
                    }
                });
        },"json");
    }
    </script>
<?php 
    return ob_get_clean();
}

/**
 * 输出批量领取卡券js接口代码ydwx_card_addCard,需要先调用ydwx_jsapi_config
 * exts json对象格式：[{cardId:"",code:"",openid:""},{cardId:"",code:"",openid:""}]
 * callback 添加成功后的js回调, 参数为添加的卡券, 如果参数为空表示领取失败
 * @param $appid 作为第三方平台，配置某个授权公众号
 * 
 * @return string
 */
function ydwx_card_jsAPI_addCard($appid=null){
    ob_start();
    ?>
    <script type="text/javascript">
    function ydwx_card_addCard(exts, callback){
        $.post("<?php echo YDWX_SITE_URL."ydwx/card.php"?>",{
            action:   "addCard",
            appid:    "<?php echo $appid?>",
            exts:     exts,
            },function(data){
                if(!data || !data.success) {
                    return;
                }
                //alert(JSON.stringify(data.data));
                wx.addCard({
                    cardList: data.data, // 需要添加的卡券列表
                    success: function (res) {
                        var cardList = res.cardList; // 添加的卡券列表信息
                        callback(cardList);
                    },
                    complete:function(res){
                    },
                    fail: function(res){
                        callback();
                    },
                    cancel:function(res){
                        callback();
                    }
                });
        },"json");
    }
    </script>
<?php 
    return ob_get_clean();
}

/**
 * 输出查看微信卡包中的卡券js接口ydwx_card_openCard
 * list  [{ cardId: '',code: ''},{ cardId: '',code: ''}]
 * 
 * @return string
 */
function ydwx_card_jsAPI_openCard(){
    ob_start();
    ?>
    <script type="text/javascript">
    function ydwx_card_openCard(list){
        wx.openCard({
            cardList: list
        });
    }
    </script>
<?php 
    return ob_get_clean();
}


/**
 * 导入code接口
 * 
 * 在自定义code卡券成功创建并且通过审核后，必须将自定义code按照与发券方的约定数量调用导入code接口导入微信后台，导入后才能发起互通任务。
 * 接口说明
 * 开发者可调用该接口将自定义code导入微信卡券后台，由微信侧代理存储并下发code，本接口仅用于支持自定义code的卡券参与互通。
 * 注： 
 * 1）单次调用接口传入code的数量上限为100个。
 * 2）每一个 code 均不能为空串。
 * 3）导入结束后系统会自动判断提供方设置库存与实际导入code的量是否一致。
 * 4）导入失败支持重复导入，提示成功为止。
 * 
 * @param unknown $accessToken
 * @param unknown $cardid 需要进行导入code的卡券ID。
 * @param array $codes 需导入微信卡券后台的自定义code，上限为100个。
 * @throws YDWXException
 * @return YDWXCardCodeImportResponse
 */
function ydwx_card_code_deposit($accessToken, $cardid, array $codes){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/code/deposit?access_token={$accessToken}",
    ydwx_json_encode(array("card_id"=>$cardid,"code"=>$codes)));
    $msg  = new YDWXCardCodeImportResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 支持开发者调用该接口查询code导入微信后台成功的数目。
 * @param unknown $accessToken
 * @param unknown $cardid 进行导入code的卡券ID。
 * @throws YDWXException
 * @return number 已经成功存入的code数目。
 */
function ydwx_card_code_getdepositcount($accessToken, $cardid){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/code/getdepositcount?access_token={$accessToken}",
    ydwx_json_encode(array("card_id"=>$cardid)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->count;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 核查code接口
 * 为了避免出现导入差错，强烈建议开发者在查询完code数目的时候核查code接口校验code导入微信后台的情况。
 * 该接口查询code导入微信后台的情况。
 * 
 * @param unknown $accessToken
 * @param unknown $cardid
 * @param array $codes 已经导入微信卡券后台的自定义code，上限为100个。
 * @throws YDWXException
 * @return YDWXCardCodeCheckResponse
 */
function ydwx_card_code_checkcode($accessToken, $cardid, array $codes){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/code/checkcode?access_token={$accessToken}",
    ydwx_json_encode(array("card_id"=>$cardid,"code"=>$codes)));
    $msg  = new YDWXCardCodeCheckResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 图文消息群发卡券
 * 支持开发者调用该接口获取卡券嵌入图文消息的标准格式代码，将返回代码填入上传图文素材接口中content字段，即可获取嵌入卡券的图文消息素材。
 * 特别注意：目前该接口仅支持填入非自定义code的卡券,自定义code的卡券需先进行code导入后调用。
 * @param unknown $accessToken
 * @param unknown $cardid
 * @throws YDWXException
 * @return string 返回一段html代码，可以直接嵌入到图文消息的正文里。即可以把这段代码嵌入到上传图文消息素材接口中的content字段里。
 */
function ydwx_card_mpnews_gethtml($accessToken, $cardid){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/mpnews/gethtml?access_token={$accessToken}",
    ydwx_json_encode(array("card_id"=>$cardid)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->content;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}


/**
 * 设置测试白名单
 * 接口说明
 * 由于卡券有审核要求，为方便公众号调试，可以设置一些测试帐号，这些帐号可领取未通过审核的卡券，体验整个流程。
 * 开发者注意事项
 * 1.同时支持“openid”、“username”两种字段设置白名单，总数上限为10个。
 * 2.设置测试白名单接口为全量设置，即测试名单发生变化时需调用该接口重新传入所有测试人员的ID.
 * 3.白名单用户领取该卡券时将无视卡券失效状态，请开发者注意。
 * 
 * @param unknown $accessToken
 * @param unknown $openids
 * @param unknown $usernames
 * @throws YDWXException
 * @return boolean
 */
function ydwx_card_testwhitelist_set($accessToken, $openids, $usernames){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/testwhitelist/set?access_token={$accessToken}",
    ydwx_json_encode(array("openid"=>(array)$openids,"username"=>(array)$usernames)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 创建货架接口
 * 卡券货架支持开发者通过调用接口生成一个卡券领取H5页面，并获取页面链接，进行卡券投放动作。 目前卡券货架仅支持非自定义code的卡券，自定义code的卡券需先调用导入code接口将code导入才能正常使用。
 * 开发者需调用该接口创建货架链接，用于卡券投放。创建货架时需填写投放路径的场景字段。
 * 
 * @param unknown $accessToken
 * @param YDWXCardLandingPageRequest $request
 * @throws YDWXException
 * @return YDWXCardLandingPageResponse
 */
function ydwx_card_landingpage_create($accessToken, YDWXCardLandingPageRequest $request){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/landingpage/create?access_token={$accessToken}",
        $request->toJSONString());
    $msg  = new YDWXCardLandingPageResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 查询Code接口
 * 我们强烈建议开发者在调用核销code接口之前调用查询code接口，并在核销之前对非法状态的code(如转赠中、已删除、已核销等)做出处理。
 * @param unknown $accessToken
 * @param YDWXCard $request
 * @param boolean $check_consume 是否校验code核销状态，填入true和false时的code异常状态返回数据不同。
 * @throws YDWXException
 * @return YDWXCardCheckCodeResponse
 */
function ydwx_card_code_get($accessToken, YDWXCard $request, $check_consume){
    $http  = new YDHttp();
    $array = $request->toArray();
    $array['check_consume'] = $check_consume;
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/code/get?access_token={$accessToken}",
    ydwx_json_encode($array));
    $msg  = new YDWXCardCheckCodeResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}
/**
 * 
 * 核销Code接口
 * 消耗code接口是核销卡券的唯一接口，仅支持核销有效期内的卡券，否则会返回错误码invalid time。
 * 自定义Code码（use_custom_code为true）的优惠券，在code被核销时，必须调用此接口。用于将用户客户端的code状态变更。
 * 
 * 
 * @param unknown $accessToken
 * @param YDWXCard $request 自定义code的卡券调用接口时，需包含card_id，非自定义code不需。
 * @throws YDWXException
 * @return YDWXCardConsumeCodeResponse
 */
function ydwx_card_code_consume($accessToken, YDWXCard $request){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/code/consume?access_token={$accessToken}",
    $request->toJSONString());
    $msg  = new YDWXCardConsumeCodeResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 获取用户已领取卡券接口
 * 用于获取用户卡包里的，属于该appid下的卡券。
 * 
 * @param unknown $accessToken
 * @param unknown $openid
 * @param unknown $cardid
 * @throws YDWXException
 * @return array YDWXCard组成的数组
 */
function ydwx_card_user_getcardlist($accessToken, $openid, $cardid){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/user/getcardlist?access_token={$accessToken}",
    ydwx_json_encode(array("openid"=>$openid,"card_id"=>$cardid)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        $array = array();
        foreach ($msg->card_list as $card){
            $c = new YDWXCard();
            $c->card_id = $card['card_id'];
            $c->code = $card['code'];
            $array[] = $c;
        }
        return $array;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 查看卡券详情
 * 调用该接口可查询卡券字段详情及卡券所处状态。建议开发者调用卡券更新信息接口后调用该接口验证是否更新成功。
 * 开发者注意事项
 * 1.对于部分有特殊权限的商家，查询卡券详情得到的返回可能含特殊接口的字段。
 * 2.由于卡券字段会持续更新，实际返回字段包含但不限于文档中的字段，建议开发者开发时对于不理解的字段不做处理，以免出错。
 * 
 * @param unknown $accessToken
 * @param unknown $cardid
 * @throws YDWXException
 * @return YDWXCardResponse
 */
function ydwx_card_get($accessToken, $cardid){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/get?access_token={$accessToken}",
    ydwx_json_encode(array("card_id"=>$cardid)));
    $msg  = new YDWXCardResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 批量查询卡列表
 * @param unknown $accessToken
 * @param unknown $offset
 * @param unknown $count
 * @param string|array $status 见YDWX_CARD_STATUS_XX常量
 * @throws YDWXException
 * @return YDWXCardBatchgetResponse
 */
function ydwx_card_batchget($accessToken, $offset, $count, $status){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/batchget?access_token={$accessToken}",
    ydwx_json_encode(array("offset"=>$offset,"count"=>$count,"status_list"=>$status)));
    $msg  = new YDWXCardBatchgetResponse($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 更改卡券信息接口
 * 接口说明
 * 支持更新所有卡券类型的部分通用字段及特殊卡券（会员卡、飞机票、电影票、会议门票）中特定字段的信息。
 * 开发者注意事项注
 * 1. 更改卡券的部分字段后会重新提交审核，详情见字段说明，更新成功后可通过调用查看卡券详情接口核查更新结果；
 * 2. 仅填入需要更新的字段，许多开发者在调用该接口时会填入brandname等不支持修改的字段，导致更新不成功。
 * 3. 调用该接口后更改卡券信息后，请务必调用查看卡券详情接口验证是否已成功更改。
 * 
 * @param unknown $accessToken
 * @param YDWXCardBase $card
 * @throws YDWXException
 * @return boolean 是否提交审核，false为修改后不会重新提审，true为修改字段后重新提审，该卡券的状态变为审核中。
 */
function ydwx_card_update($accessToken, $cardid, YDWXCardBase $card){
    $http  = new YDHttp();
    $args  = $card->toArray();
    $args  = $args['card'];
    $args["card_id"] = $cardid;
    unset($args["card_type"]);
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/update?access_token={$accessToken}",
    ydwx_json_encode($args));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return $msg->send_check;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 修改库存接口
 * 调用修改库存接口增减某张卡券的库存。
 * 
 * @param unknown $accessToken
 * @param unknown $cardid
 * @param unknown $increase_stock_value
 * @param unknown $reduce_stock_value
 * @throws YDWXException
 * @return boolean
 */
function ydwx_card_modifystock($accessToken, $cardid, $increase_stock_value, $reduce_stock_value){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/modifystock?access_token={$accessToken}",
    ydwx_json_encode(array("card_id"=>$cardid, "increase_stock_value"=>$increase_stock_value, 
    "reduce_stock_value"=>$reduce_stock_value)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 更改Code接口
 * 为确保转赠后的安全性，微信允许自定义Code的商户对已下发的code进行更改。 
 * 注：为避免用户疑惑，建议仅在发生转赠行为后（
 * 发生转赠后，微信会通过事件推送的方式告知商户被转赠的卡券Code, YDWXHOOK::EVENT_USER_GET_CARD）对用户的Code进行更改。
 * @param unknown $accessToken
 * @param unknown $cardid
 * @param unknown $code
 * @param unknown $newcode
 * @throws YDWXException
 * @return boolean
 */
function ydwx_card_code_update($accessToken, $cardid, $code, $newcode){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/code/update?access_token={$accessToken}",
    ydwx_json_encode(array("card_id"=>$cardid, "code"=>$code,
            "newcode"=>$newcode)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 删除卡券接口
 * 删除卡券接口允许商户删除任意一类卡券。删除卡券后，该卡券对应已生成的领取用二维码、添加到卡包JS API均会失效。 
 * 注意：如用户在商家删除卡券前已领取一张或多张该卡券依旧有效。即删除卡券不能删除已被用户领取，保存在微信客户端中的卡券
 * 
 * @param unknown $accessToken
 * @param unknown $cardid
 * @throws YDWXException
 * @return boolean
 */
function ydwx_card_delete($accessToken, $cardid){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/delete?access_token={$accessToken}",
    ydwx_json_encode(array("card_id"=>$cardid)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 设置卡券失效接口
 * 为满足改票、退款等异常情况，可调用卡券失效接口将用户的卡券设置为失效状态。 
 * 注：设置卡券失效的操作不可逆，即无法将设置为失效的卡券调回有效状态，商家须慎重调用该接口。
 * 
 * @param unknown $accessToken
 * @param YDWXCard $card 非自定义卡券的请求只需设置code
 * @throws YDWXException
 * @return boolean
 */
function ydwx_card_code_unavailable($accessToken, YDWXCard $card){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/code/unavailable?access_token={$accessToken}",
    $card->toJSONString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 拉取卡券概况数据接口
 * 
 * 支持调用该接口拉取本商户的总体数据情况，包括时间区间内的各指标总量。
 * 特别注意： 
 * 1. 查询时间区间需<=62天，否则报错{errcode: 61501，errmsg: "date range error"}；
 * 2. 传入时间格式需严格参照示例填写”2015-06-15”，否则报错{errcode":61500,"errmsg":"date format error"}
 * @param unknown $accessToken
 * @param unknown $begindate
 * @param unknown $enddate
 * @param unknown $cond_source 卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据
 * @throws YDWXException
 * @return array YDWXCardStatistic 组成的数组
 */
function ydwx_card_datacube_getcardbizuininfo($accessToken, $begindate, $enddate, $cond_source){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."datacube/getcardbizuininfo?access_token={$accessToken}",
    ydwx_json_encode(array("begin_date"=>$begindate,"end_date"=>$enddate,"cond_source"=>$cond_source)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        $array = array();
        foreach ($msg->list as $info){
            $obj = new YDWXCardStatistic();
            foreach ($info as $name=>$value){
                $obj->$name = $value;
            }
            $array[] = $obj;
        }
        return $array;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 获取免费券数据接口
 * 支持开发者调用该接口拉取免费券（优惠券、团购券、折扣券、礼品券）在固定时间区间内的相关数据。
 * 特别注意：
 * 1. 该接口目前仅支持拉取免费券（优惠券、团购券、折扣券、礼品券）的卡券相关数据，暂不支持特殊票券（电影票、会议门票、景区门票、飞机票）数据。
 * 2. 查询时间区间需<=62天，否则报错{"errcode:" 61501，errmsg: "date range error"}；
 * 3. 传入时间格式需严格参照示例填写如”2015-06-15”，否则报错｛"errcode":"date format error"｝
 * @param unknown $accessToken
 * @param unknown $card_id
 * @param unknown $begindate
 * @param unknown $enddate
 * @param unknown $cond_source
 * @throws YDWXException
 * @return array YDWXCardCardStatistic
 */
function ydwx_card_datacube_getcardinfo($accessToken, $begindate, $enddate, $cond_source,$card_id=null){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."datacube/getcardcardinfo?access_token={$accessToken}",
    ydwx_json_encode(array("card_id"=>$card_id,"begin_date"=>$begindate,"end_date"=>$enddate,"cond_source"=>$cond_source)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        $array = array();
        foreach ($msg->list as $info){
            $obj = new YDWXCardCardStatistic();
            foreach ($info as $name=>$value){
                $obj->$name = $value;
            }
            $array[] = $obj;
        }
        return $array;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 拉取会员卡数据接口
 * 
 * 为支持开发者调用API查看卡券相关数据，微信卡券团队封装数据接口并面向具备卡券功能权限的开发者开放使用。开发者调用该接口可获取本商户下的所有卡券相关的总数据以及指定卡券的相关数据。开发过程请务必注意以下事项：
 * 1.查询时间区间需<=62天，否则报错{errcode: 61501，errmsg: "date range error"}；
 * 2.传入时间格式需严格参照示例填写”2015-06-15”，否则报错{errcode":61500,"errmsg":"date format error"}；
 * 3.需在获取卡券相关数据前区分卡券创建渠道：公众平台创建、调用卡券接口创建。
 * 
 * 支持开发者调用该接口拉取公众平台创建的会员卡相关数据。
 * @param unknown $accessToken
 * @param unknown $begindate
 * @param unknown $enddate
 * @param unknown $cond_source
 * @throws YDWXException
 * @return multitype:YDWXCardCardStatistic
 */
function ydwx_card_datacube_getmembercardinfo($accessToken, $begindate, $enddate, $cond_source){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."datacube/getcardmembercardinfo?access_token={$accessToken}",
    ydwx_json_encode(array("begin_date"=>$begindate,"end_date"=>$enddate,"cond_source"=>$cond_source)));
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        $array = array();
        foreach ($msg->list as $info){
            $obj = new YDWXMemberCardStatistic();
            foreach ($info as $name=>$value){
                $obj->$name = $value;
            }
            $array[] = $obj;
        }
        return $array;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 更新会议门票
 * 支持调用“更新会议门票”接口update 入场时间、区域、座位等信息。
 * 
 * @param unknown $accessToken
 * @param YDWXCardMeetingTicketUpdate $request
 * @throws YDWXException
 * @return boolean
 */
function ydwx_card_meetingticket_updateuser($accessToken, YDWXCardMeetingTicketUpdate $request){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/meetingticket/updateuser?access_token={$accessToken}",
    $request->toJsonString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 更新电影票
 * 领取电影票后通过调用“更新电影票”接口update电影信息及用户选座信息。
 * 
 * @param unknown $accessToken
 * @param YDWXCardMovieTicketUpdate $request
 * @throws YDWXException
 * @return boolean
 */
function ydwx_card_moviceticket_updateuser($accessToken, YDWXCardMovieTicketUpdate $request){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/moviceticket/updateuser?access_token={$accessToken}",
    $request->toJsonString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 更新飞机票信息接口
 * 
 * @param unknown $accessToken
 * @param YDWXCardBoardingPassUpdate $request
 * @throws YDWXException
 * @return boolean
 */
function ydwx_card_boardingpass_checkin($accessToken, YDWXCardBoardingPassUpdate $request){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/boardingpass/checkin?access_token={$accessToken}",
    $request->toJsonString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 会员卡激活接口
 * @param unknown $accessToken
 * @param YDWXCardMemberActivate $request
 * @throws YDWXException
 * @return boolean
 */
function ydwx_card_membercard_activate($accessToken, YDWXCardMemberActivate $request){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/membercard/activate?access_token={$accessToken}",
    $request->toJsonString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 设置开卡字段接口
 * 支持开发者自定义开卡字段
 * 
 * @param unknown $accessToken
 * @param YDWXCardMemberActivateForm $request
 * @throws YDWXException
 * @return boolean
 */
function ydwx_card_membercard_activate_from_set($accessToken, YDWXCardMemberActivateForm $request){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/membercard/activateuserform/set?access_token={$accessToken}",
    $request->toJsonString());
    $msg  = new YDWXResponse($info);
    if($msg->isSuccess()){
        return true;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}

/**
 * 支持开发者根据CardID和Code查询会员信息。
 * 
 * @param unknown $accessToken
 * @param unknown $card_id
 * @param unknown $code
 * @throws YDWXException
 * @return YDWXCardMemberUserInfo
 */
function ydwx_card_membercard_userinfo($accessToken, $card_id, $code){
    $http  = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL2."card/membercard/userinfo/get?access_token={$accessToken}",
    ydwx_json_encode(array("card_id"=>$card_id,"code"=>$code)));
    $msg  = new YDWXCardMemberUserInfo($info);
    if($msg->isSuccess()){
        return $msg;
    }
    throw new YDWXException($msg->errmsg,$msg->errcode);
}