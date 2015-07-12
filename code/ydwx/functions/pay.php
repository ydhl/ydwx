<?php

/**
 * 
 * @param unknown $openid
 * @param unknown $trade_no
 * @param unknown $price
 * @param unknown $attach
 * @param unknown $payDesc
 * @return WXMsg
 */
function preparePay($openid, $trade_no, $price, $attach, $payDesc){
    $nonceStr = uniqid();

    $str = "appid=".WEIXIN_APP_ID."&attach=".$attach
            ."&body=".$payDesc."&mch_id=".WEIXIN_MCH_ID
            ."&nonce_str=".$nonceStr."&notify_url=".WEIXIN_NOTIFY_URL
            ."&openid=".$openid."&out_trade_no=".$trade_no
            ."&spbill_create_ip=".$_SERVER['REMOTE_ADDR']."&total_fee=".$price."&trade_type=JSAPI";
    $signStr = strtoupper(md5($str."&key=".WEIXIN_MCH_KEY));
    
    $args = "<xml>
    <appid>".WEIXIN_APP_ID."</appid>
    <attach>{$attach}</attach>
    <body>{$payDesc}</body>
    <mch_id>".WEIXIN_MCH_ID."</mch_id>
    <nonce_str>".$nonceStr."</nonce_str>
    <notify_url>".WEIXIN_NOTIFY_URL."</notify_url>
    <openid>{$openid}</openid>
    <out_trade_no>{$trade_no}</out_trade_no>
    <spbill_create_ip>".$_SERVER['REMOTE_ADDR']."</spbill_create_ip>
    <total_fee>{$price}</total_fee>
    <trade_type>JSAPI</trade_type>
    <sign>{$signStr}</sign>
    </xml>";
    
    $http = new YDHttp();
    $info = $http->post("https://api.mch.weixin.qq.com/pay/unifiedorder", $args);
    $msg =  WXMsg::build($info);
//     $msg->rawData .= $str."&key=".WEIXIN_MCH_KEY;
    return $msg;
}