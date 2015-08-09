<?php

/**
 * 该方法是H5 jsAPI调起支付的第一步, 该函数调用成功后便通过jsApiPay这个js接口调起微信支付
 * 
 * @param YDWXPayUnifiedOrderArg arg
 * @return YDWXPayUnifiedOrderMsg
 */
function ydwx_pay_unifiedorder(YDWXPayUnifiedOrderArg $arg){
    $arg->trade_type = "JSAPI";
    $str = $arg->toString();
//     $str = "appid=".WEIXIN_APP_ID."&attach=".$attach
//             ."&body=".$pay_desc."&mch_id=".WEIXIN_MCH_ID
//             ."&nonce_str=".$nonceStr."&notify_url=".WEIXIN_NOTIFY_URL
//             ."&openid=".$openid."&out_trade_no=".$trade_no
//             ."&spbill_create_ip=".$_SERVER['REMOTE_ADDR']."&total_fee=".$price."&trade_type=JSAPI";
    $signStr = strtoupper(md5($str."&key=".WEIXIN_MCH_KEY));
    
    $arg->sign = $signStr;
//     $args = "<xml>
//     <appid>".WEIXIN_APP_ID."</appid>
//     <attach>{$attach}</attach>
//     <body>{$pay_desc}</body>
//     <mch_id>".WEIXIN_MCH_ID."</mch_id>
//     <nonce_str>".$nonceStr."</nonce_str>
//     <notify_url>".WEIXIN_NOTIFY_URL."</notify_url>
//     <openid>{$openid}</openid>
//     <out_trade_no>{$trade_no}</out_trade_no>
//     <spbill_create_ip>".$_SERVER['REMOTE_ADDR']."</spbill_create_ip>
//     <total_fee>{$price}</total_fee>
//     <trade_type>JSAPI</trade_type>
//     <sign>{$signStr}</sign>
//     </xml>";
    $args = $arg->toXMLString();
    
    $http = new YDHttp();
    $info = $http->post("https://api.mch.weixin.qq.com/pay/unifiedorder", $args);
    
    $msg  = new YDWXPayUnifiedOrderMsg($info);
    if($msg->isSuccess()){
        throw new YDWXException($msg->errmsg);
    }
    return $msg;
    
//     $msg =  YDWXMsg::build($info);
    
//     if($msg->isPrepaySuccess()){
//         if($msg->isPrepayResultSuccess()){
//             return ydwx_success($msg->get(YDWXMsg::PrePayPrepayId));
//         }
//         return ydwx_error($msg->get(YDWXMsg::PrePayErrCodeDes));
//     }
//     return ydwx_error($msg->get(YDWXMsg::PrePayReturnMsg));
}

/**
 * 扫码支付二维码内容（模式一）
 * 把返回的内容生成二维码后，用户扫码进入支付流程
 * 
 * 建议采用http://ydimage.yidianhulian.com/qrcode?str=二维码内容来生产二维码
 * 
 * @param unknown $product_id 你系统的产品id
 */
function ydwx_pay_qrcode($product_id){
    $nonceStr   = uniqid();
    $time_stamp = time();
    
    $str = "appid=".WEIXIN_APP_ID
    ."&mch_id=".WEIXIN_MCH_ID
    ."&nonce_str=".$nonceStr."&product_id=".$product_id
    ."&time_stamp=".$time_stamp;
    $signStr = strtoupper(md5($str."&key=".WEIXIN_MCH_KEY));
    
    return "weixin://wxpay/bizpayurl?sign={$sign}&appid="
            .WEIXIN_APP_ID."&mch_id=".WEIXIN_MCH_ID
    ."&product_id={$product_id}&time_stamp={$time_stamp}&nonce_str={$nonceStr}";
}

/**
 * 扫码支付二维码内容（模式二）
 * 先调起微信服务后台生成预支付交易单, 该函数把返回的内容生成二维码后，用户扫码便直接支付。
 * 注意该返回的内容有2小时失效
 * 
 * @param YDWXPayUnifiedOrderArg $arg
 * 
 * @return string 二维码内容
 */
function ydwx_scan_to_pay(YDWXPayUnifiedOrderArg $arg){
    $arg->trade_type = "NATIVE";
//     $nonceStr = uniqid();
//     $str = "appid=".WEIXIN_APP_ID."&attach=".$attach
//     ."&body=".$pay_desc."&mch_id=".WEIXIN_MCH_ID
//     ."&nonce_str=".$nonceStr."&notify_url=".WEIXIN_NOTIFY_URL
//     ."&out_trade_no=".$trade_no
//     ."&product_id={$product_id}&spbill_create_ip=".$_SERVER['SERVER_ADDR']."&total_fee=".$price."&trade_type=NATIVE";
    $str = $arg->toString();
    $arg->sign = strtoupper(md5($str."&key=".WEIXIN_MCH_KEY));
//     $args = "<xml>
//     <appid>".WEIXIN_APP_ID."</appid>
//         <attach>{$attach}</attach>
//         <body>{$pay_desc}</body>
//         <mch_id>".WEIXIN_MCH_ID."</mch_id>
//         <nonce_str>".$nonceStr."</nonce_str>
//         <notify_url>".WEIXIN_NOTIFY_URL."</notify_url>
//         <out_trade_no>{$trade_no}</out_trade_no>
//         <spbill_create_ip>".$_SERVER['SERVER_ADDR']."</spbill_create_ip>
//         <product_id>{$product_id}</product_id>
//         <total_fee>{$price}</total_fee>
//         <trade_type>NATIVE</trade_type>
//         <sign>{$signStr}</sign>
//         </xml>";

    $args = $arg->toXMLString();
    
    $http = new YDHttp();
    $info = $http->post("https://api.mch.weixin.qq.com/pay/unifiedorder", $args);
    $msg  =  new YDWXPayUnifiedOrderMsg($info);
    
    if( ! $msg->isSuccess()){
        throw new YDWXException($msg->errmsg);
    }
    return $msg->code_url;
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