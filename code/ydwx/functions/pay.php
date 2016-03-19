<?php
/**
 * 该接口主要用于扫码原生支付模式一中的二维码链接转成短链接(weixin://wxpay/s/XX)，
 * 减小二维码数据量，提升扫描速度和精确度。
 * 
 * @param YDWXPayShorturlRequest $msg
 * @return YDWXPayShorturlResponse
 */
function ydwx_pay_short_qrcode(YDWXPayShorturlRequest $arg){
    $arg->sign();
    $args = $arg->toXMLString();
    
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_PAY_URL."pay/downloadbill", $args);
    
    $msg  = new YDWXPayShorturlResponse($info);
    if( ! $msg->isSuccess()){
        throw new YDWXException($msg->errmsg);
    }
    return $msg;
}

/**
 * 商户可以通过该接口下载历史交易清单。比如掉单、系统错误等导致商户侧和微信侧数据不一致，通过对账单核对后可校正支付状态。
 * 注意：
 * 1、微信侧未成功下单的交易不会出现在对账单中。支付成功后撤销的交易会出现在对账单中，跟原支付单订单号一致，bill_type为REVOKED；
 * 2、微信在次日9点启动生成前一天的对账单，建议商户10点后再获取；
 * 3、对账单中涉及金额的字段单位为“元”。
 *
 * @param YDWXPayDownloadbillRequest arg
 * @return YDWXPayDownloadbillResponse
 */
function ydwx_pay_downloadbill(YDWXPayDownloadbillRequest $arg){
    $arg->sign();
    $args = $arg->toXMLString();

    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_PAY_URL."pay/downloadbill", $args);

    $msg  = new YDWXPayDownloadbillResponse($info);
    if( ! $msg->isSuccess()){
        throw new YDWXException($msg->errmsg);
    }
    $msg->bill_type = $arg->bill_type;
    return $msg;
}
/**
 * 提交退款申请后，通过调用该接口查询退款状态。退款有一定延时，用零钱支付的退款20分钟内到账，
 * 银行卡支付的退款3个工作日后重新查询退款状态。
 *
 * @param YDWXPayRefundQueryRequest arg
 * @return YDWXPayRefundQueryResponse
 */
function ydwx_pay_refund_query(YDWXPayRefundQueryRequest $arg){
    $arg->sign();
    $args = $arg->toXMLString();

    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_PAY_URL."pay/refundquery", $args);

    $msg  = new YDWXPayRefundQueryResponse($info);
    if( ! $msg->isSuccess()){
        throw new YDWXException($msg->errmsg);
    }
    return $msg;
}
/**
 * 当交易发生之后一段时间内，由于买家或者卖家的原因需要退款时，
 * 卖家可以通过退款接口将支付款退还给买家，微信支付将在收到退款请求并且验证成功之后，按照退款规则将支付款按原路退到买家帐号上。
 * 注意：
 * 1、交易时间超过一年的订单无法提交退款；
 * 2、微信支付退款支持单笔交易分多次退款，多次退款需要提交原支付订单的商户订单号和设置不同的退款单号。
 * 一笔退款失败后重新提交，要采用原来的退款单号。总退款金额不能超过用户实际支付金额。
 *
 * @param YDWXPayRefundRequest arg
 * @return YDWXPayRefundResponse
 */
function ydwx_pay_refund(YDWXPayRefundRequest $arg){
    $arg->sign();
    $args = $arg->toXMLString();

    $http = new YDHttps($arg->appid);
    $info = $http->post(YDWX_WEIXIN_PAY_URL."secapi/pay/refund", $args);

    $msg  = new YDWXPayRefundResponse($info);
    if( ! $msg->isSuccess()){
        throw new YDWXException($msg->errmsg);
    }
    return $msg;
}
/**
 * 以下情况需要调用关单接口：商户订单支付失败需要生成新单号重新发起支付，要对原订单号调用关单，
 * 避免重复支付；系统下单后，用户支付超时，系统退出不再受理，避免用户继续，请调用关单接口。
 * 
 * 注意：订单生成后不能马上调用关单接口，最短调用时间间隔为5分钟。
 *
 * @param YDWXCloseOrderRequest arg
 * @return YDWXPayBaseResponse
 */
function ydwx_pay_closeorder(YDWXCloseOrderRequest $arg){
    $arg->sign();
    $args = $arg->toXMLString();

    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_PAY_URL."pay/closeorder", $args);

    $msg  = new YDWXPayBaseResponse($info);
    if( ! $msg->isSuccess()){
        throw new YDWXException($msg->errmsg);
    }
    return $msg;
}
/**
 * 该接口提供所有微信支付订单的查询，商户可以通过该接口主动查询订单状态，完成下一步的业务逻辑。
 * 需要调用查询接口的情况：
 * ◆ 当商户后台、网络、服务器等出现异常，商户系统最终未接收到支付通知；
 * ◆ 调用支付接口后，返回系统错误或未知交易状态情况；
 * ◆ 调用被扫支付API，返回USERPAYING的状态；
 * ◆ 调用关单或撤销接口API之前，需确认支付状态；
 *
 * @param YDWXOrderQueryRequest arg
 * @return YDWXOrderQueryResponse
 */
function ydwx_pay_orderquery(YDWXOrderQueryRequest $arg){
    $arg->sign();
    $args = $arg->toXMLString();
    
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_PAY_URL."pay/orderquery", $args);
    
    $msg  = new YDWXOrderQueryResponse($info);
    if( ! $msg->isSuccess()){
        throw new YDWXException($msg->errmsg);
    }
    return $msg;
}
/**
 * 微信统一下单接口,根据构造YDWXPayUnifiedOrderRequest的方式不同返回不同
 * 通过 new YDWXPayUnifiedOrderRequest(true)；返回的YDWXPayUnifiedOrderResponse中会有code_url（二维码内容）
 * 其他情况没用
 *
 * 建议采用http://ydimage.yidianhulian.com/qrcode?str=二维码内容来生产二维码
 * 
 * @param YDWXPayUnifiedOrderRequest arg
 * @return YDWXPayUnifiedOrderResponse
 */
function ydwx_pay_unifiedorder(YDWXPayUnifiedOrderRequest $arg){
    $arg->sign();
    $args = $arg->toXMLString();
    
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_PAY_URL."pay/unifiedorder", $args);
    
    $msg  = new YDWXPayUnifiedOrderResponse($info);
    if( ! $msg->isSuccess()){
        throw new YDWXException($msg->errmsg, $msg->errcode);
    }
    return $msg;
}


/**
 * 扫码支付二维码内容（模式一）
 * 把返回的内容生成二维码后，用户扫码后回回调pay-notify.php
 *  
 * 
 * 建议采用http://ydimage.yidianhulian.com/qrcode?str=二维码内容来生产二维码
 * 
 * 可以把返回结果再次调用ydwx_pay_short_qrcode()得到更精简的二维码内容，减少二维码复杂度
 * 
 * @param unknown $product_id 你系统的产品id
 * @param string type 见YDWX_WEIXIN_TYPE_XX常量
 * @param unknown $appid 当前公众号appid，如果不是第三方平台，则传入（普通账号）YDWX_WEIXIN_APP_ID或（企业号）YDWX_WEIXIN_CROP_ID
 */
function ydwx_pay_product_qrcode($product_id, $appid, $type=YDWX_WEIXIN_TYPE_NORMAL){
    $nonceStr   = uniqid();
    $time_stamp = time();
    
    if($type==YDWX_WEIXIN_TYPE_CROP){
    	$mchkey = YDWX_WEIXIN_QY_MCH_KEY;
    	$mchid  = YDWX_WEIXIN_QY_MCH_ID;
    }else if($type==YDWX_WEIXIN_TYPE_AGENT){
    	$mchkey = YDWXHook::do_hook(YDWXHook::GET_HOST_MCH_KEY, $appid);
    	$mchid  = YDWXHook::do_hook(YDWXHook::GET_HOST_MCH_ID, $appid);
    	
    }else{
    	$mchkey = YDWX_WEIXIN_MCH_KEY;
    	$mchid  = YDWX_WEIXIN_MCH_ID;
    }
    
    $str = "appid=".$appid
    ."&mch_id=".$mchid
    ."&nonce_str=".$nonceStr."&product_id=".$product_id
    ."&time_stamp=".$time_stamp;
    $signStr = strtoupper(md5($str."&key=".$mchkey));
    
    return "weixin://wxpay/bizpayurl?sign={$signStr}&appid="
            .$appid."&mch_id=".$mchid
    ."&product_id={$product_id}&time_stamp={$time_stamp}&nonce_str={$nonceStr}";
}

/**
 * 生成jsAPI 预处理支付脚本，其中有个一个jsPayApi(openid, traceno,totalPrice, attach, payDesc, success, fail, cancel)方法是实际调起微信支付的入口
 * openid 支付用户openid；traceno订单号，totalPrice是支付费用；attach是附加数据，微信原因返回；payDesc商品描述；
 * success 成功回调
 * fail 失败回调 参数为错误消息
 * cancel 用户取消支付回调
 * 
 *  在准备支付是调用jsPayApi即可
 * 
 * 需要先引入<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
 * 
 * @param unknown $jsapi_ticket
 * @param unknown $curr_page_uri
 * @param unknown $appid
 * @return string
 */
function ydwx_jspay_script($jsapi_ticket, $curr_page_uri, $appid){
    ob_start();
?>
<script type="text/javascript">
<?php 
    $time       = time();
    $nonceStr   = uniqid();
    $signStr    = sha1("jsapi_ticket={$jsapi_ticket}&noncestr={$nonceStr}&timestamp={$time}&url=".$curr_page_uri);
?>

wx.config({
    debug: false,
    appId: '<?php echo $appid?>',
    timestamp:'<?php echo $time?>' ,
    nonceStr: '<?php echo $nonceStr?>',
    signature: '<?php echo $signStr?>',
    jsApiList: ['chooseWXPay']
});

wx.error(function(res){
    //alert(JSON.stringify(res));
});

function jsPayApi(openid, trace_no, totalPrice, attach, pay_desc, success, fail, cancel){
    $.post("<?php echo YDWX_SITE_URL."pay.php"?>", {
        appid:<?php echo $appid?>,price:totalPrice, trace_no:trace_no, action:"prepay", "attach":attach, "payDesc":pay_desc, "timestamp":"<?php echo $time?>", "noncestr":"<?php echo $nonceStr?>"
        }, function(data){
            if( ! data.success){
                fail(data.msg);
                return;
            }
            wx.chooseWXPay({
                timestamp:  <?php echo $time?>, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
                nonceStr:  '<?php echo $nonceStr?>', // 支付签名随机串，不长于 32 位
                'package': 'prepay_id='+data.prepay_id, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
                signType:  'MD5', // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
                paySign:   data.paySign, // 支付签名
                success: function(res){
                    success();
                },
                fail:   function(res){
                    fail("");
                },
                cancel: function(res){
                    cancel(res);
                }
            });
    },"json");
}
</script>
<?php 
    return ob_get_clean();
}?>