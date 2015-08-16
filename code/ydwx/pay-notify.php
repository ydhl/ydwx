<?php

/**
 * 微信支付回调接口
 * 1. 模式1，扫码支付回调，这是通知的内容是支付成功与否,会传入transaction_id out_trade_no
 * 2. 模式2，这是通知的内容是用户是否成功扫码了，会传入product_id,openid
 */
chdir(dirname(__FILE__));//把工作目录切换到文件所在目录
include_once dirname(__FILE__).'/__config__.php';

$data = @$GLOBALS["HTTP_RAW_POST_DATA"];

$msg = new YDWXPayNotifyResponse($data);
if($msg->isSuccess()){
    if($msg->product_id){
        $result = new YDWXPayNotifyRequest();
        
        try{
            $arg = YDWXHook::do_hook(YDWXHook::QRCODE_PAY_NOTIFY_SUCCESS, $msg);
            $arg->openid        = $msg->openid;
            $msg = ydwx_pay_unifiedorder($arg);
            $result_code = "SUCCESS";
            $err_code_des = "OK";
            $result->prepay_id = $msg->prepay_id;
        }catch(YDWXException $e){
            $result_code = "FAIL";
            $err_code_des = $e->getMessage();
        }
        
        $result->result_code = $result_code;
        $result->err_code_des = $err_code_des;
        $result->sign();
        echo $result->toXMLString();
    }else{
        if(YDWXHook::do_hook(YDWXHook::PAY_NOTIFY_SUCCESS, $msg)){
            ob_clean();
            echo "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
        }
    }
    die;
}else{
    YDWXHook::do_hook(YDWXHook::PAY_NOTIFY_ERROR, $msg);
}