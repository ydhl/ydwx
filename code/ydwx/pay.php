<?php
/**
 * 微信支付统一下单接口,生成预支付id
 */
chdir(dirname(__FILE__));//把工作目录切换到文件所在目录
include_once dirname(__FILE__).'/__config__.php';
$arg = new YDWXPayUnifiedOrderArg();
$arg->openid        = $_POST['openid'];
$arg->out_trade_no  = $_POST['trace_no'];
$arg->total_fee     = intval($_POST['price']*100);
$arg->attach        = $_POST['attach'];
$arg->body          = $_POST['pay_desc'];
try{
    $msg = ydwx_pay_unifiedorder($arg);
    $str = "appId=".WEIXIN_APP_ID."&nonceStr=".$_POST['noncestr']
        ."&package=prepay_id=".$msg->prepay_id."&signType=MD5&timeStamp=".$_POST['timestamp'];
    $sign = strtoupper(md5($str."&key=".WEIXIN_MCH_KEY));
    
    echo json_encode(ydwx_success(array(
            "prepay_id" => $msg['data'],
            "paySign"   => $sign,
            "trade_no"  => $_POST['trace_no']
    )));
    die;
}catch (YDWXException $e){
    echo json_encode(ydwx_error($e->getMessage()));
    die;
}