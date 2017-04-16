<?php

/**
 * 微信支付回调接口
 * 1. 模式1，扫码支付回调，这是通知的内容是支付成功与否,会传入transaction_id out_trade_no
 * 2. 模式2，这是通知的内容是用户是否成功扫码了，会传入product_id,openid
 */
chdir(dirname(__FILE__));//把工作目录切换到文件所在目录
include_once dirname(__FILE__).'/__config__.php';

$data = file_get_contents('php://input');
YDWXHook::do_hook(YDWXHook::YDWX_LOG, $data);
$msg = new YDWXPaiedNotifyResponse($data);
if($msg->isSuccess()){
    if($msg->product_id){
        $PayingMsg  = new YDWXPayingNotifyResponse($data);
        $result     = new YDWXPayNotifyRequest();
        YDWXHook::do_hook(YDWXHook::YDWX_LOG, "QRCODE_PAY_NOTIFY_SUCCESS");
        try{
            $arg = YDWXHook::do_hook(YDWXHook::QRCODE_PAY_NOTIFY_SUCCESS, $PayingMsg);
            $arg->openid        = $PayingMsg->openid;
            $order = ydwx_pay_unifiedorder($arg);
            $result_code = "SUCCESS";
            $err_code_des = "OK";
            $result->prepay_id = $order->prepay_id;
        }catch(YDWXException $e){
            $result_code = "FAIL";
            $err_code_des = $e->getMessage();
        }
        
        // TODO 这里要考虑YDWX_WEIXIN_MCH_KEY的来源（企业号，第三方平台等）
        
	    $result->appid  = $PayingMsg->appid;
	    $result->mch_id = $PayingMsg->mch_id;
	    $result->mch_key = YDWX_WEIXIN_MCH_KEY;
	    $result->return_code = $result_code;
        $result->result_code = $result_code;
        $result->err_code_des = $err_code_des;
        
        $result->sign();
        YDWXHook::do_hook(YDWXHook::YDWX_LOG, $result->toXMLString());
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