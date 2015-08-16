<?php
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
    $info = $http->post("https://api.mch.weixin.qq.com/pay/unifiedorder", $args);
    
    $msg  = new YDWXPayUnifiedOrderResponse($info);
    if($msg->isSuccess()){
        throw new YDWXException($msg->errmsg);
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
 * @param unknown $product_id 你系统的产品id
 */
function ydwx_pay_product_qrcode($product_id){
    $nonceStr   = uniqid();
    $time_stamp = time();
    
    $str = "appid=".WEIXIN_APP_ID
    ."&mch_id=".WEIXIN_MCH_ID
    ."&nonce_str=".$nonceStr."&product_id=".$product_id
    ."&time_stamp=".$time_stamp;
    $signStr = strtoupper(md5($str."&key=".WEIXIN_MCH_KEY));
    
    return "weixin://wxpay/bizpayurl?sign={$signStr}&appid="
            .WEIXIN_APP_ID."&mch_id=".WEIXIN_MCH_ID
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
 * @return string
 */
function ydwx_jspay_script($jsapi_ticket, $curr_page_uri){
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
    appId: '<?php echo WEIXIN_APP_ID?>',
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
        price:totalPrice, trace_no:trace_no, action:"prepay", "attach":attach, "payDesc":pay_desc, "timestamp":"<?php echo $time?>", "noncestr":"<?php echo $nonceStr?>"
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