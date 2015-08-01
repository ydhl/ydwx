<?php

/**
 * 微信支付回调接口
 * 1. 模式1，扫码支付回调，这是通知的内容是支付成功与否,会传入transaction_id out_trade_no
 * 2. 模式2，这是通知的内容是用户是否成功扫码了，会传入product_id,openid
 */
include_once dirname(__FILE__).'/libs/wx.php';

$data = @$GLOBALS["HTTP_RAW_POST_DATA"];

$msg =  WXMsg::build($data);

if($msg->get(WXMsg::ProductId)){//case 2
//     scanToPay($product_id, $trade_no, $price, $attach, $pay_desc)
}else{
    if($msg->isPrepaySuccess()){
        if($msg->isPrepayResultSuccess()){
            YDHook::do_hook(WXHooks::PAY_NOTIFY_SUCCESS, $msg);
            ob_clean();
            echo "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";die;
        }else{
            YDHook::do_hook(WXHooks::PAY_NOTIFY_ERROR, $msg->get(WXMsg::PrePayErrCodeDes));
        }
    }else{
        YDHook::do_hook(WXHooks::PAY_NOTIFY_ERROR, $msg->get(WXMsg::PrePayReturnMsg));
    }
}