<?php
/**
 * 微信支付统一下单接口,生成预支付id
 */
include_once dirname(__FILE__).'/libs/wx.php';

$msg = preparePay($_POST['openid'], $_POST['trace_no'], $_POST['price']*100, $_POST['attach'], $_POST['pay_desc']);
if($msg['success']){
    $str = "appId=".WEIXIN_APP_ID."&nonceStr=".$_POST['noncestr']."&package=prepay_id=".$msg->get(WXMsg::PrePayPrepayId)."&signType=MD5&timeStamp=".$_POST['timestamp'];
    $sign = strtoupper(md5($str."&key=".WEIXIN_MCH_KEY));

    echo json_encode(yze_success(array(
    "prepay_id" => $msg['data'],
    "paySign"   => $sign,
    "trade_no"  => $_POST['trace_no']
    )));
    die;
}else{
    echo json_encode(yze_error($msg['msg']));
    die;
}