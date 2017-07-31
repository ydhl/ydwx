<?php


/**
 * 微信支付实现方法
 * 场景1：微信内容H5页面JS调起微信支付
 * 场景2：网站扫码调起微信支付
 * 
 * 场景1： 微信内H5JS调起微信支付
 * 	1. 调用ydwx_jspay_script，它会输出一个js 函数jsPayApi(openid, traceno,totalPrice, attach, payDesc, success, fail, cancel)
 * 	2. 会进入微信支付流程，支付的成果失败会调用hook：YDWXHook::PAY_NOTIFY_ERROR 或者YDWXHook::PAY_NOTIFY_SUCCESS
 * 	3. 用户是否登录需要自己判断处理，需要进行oauth登录则包含对于的*auth.php文件，该文件会引导进入OAuth授权，授权成功后会得到openid，（hook YDWXHook::AUTH_INAPP_SUCCESS）
 * 
 * 场景2：
 * 	调起微信扫码有两种方式：
 * 		一种是先在微信端下订单，产生二维码后，在让用户扫码；这种方式由于是先把商品，购买者，价格等信息提交给微信
 * 			先生成订单了，所以需要用户在2小时内必须扫码进行支付，否则订单作废；
 * 			
 * 			预下单接口ydwx_pay_unifiedorder
 * 		进入微信支付流程，支付的成果失败会调用hook：YDWXHook::PAY_NOTIFY_ERROR 或者YDWXHook::PAY_NOTIFY_SUCCESS
 * 
 * 		一种是先把自己的商品按照微信的二维码格式生成二维码，用户扫码后再生成订单；这种方式是用户随时扫码，在产生订单
 * 			生成二维码接口：ydwx_pay_product_qrcode
 * 	
 * 		扫码成功后会触发hook QRCODE_PAY_NOTIFY_SUCCESS
 * 1. 
 */

YDWXHook::add_hook ( YDWXHook::GET_HOST_MCH_KEY, function ($appid) {
    //非第三方微信平台不用实现
} );

YDWXHook::add_hook ( YDWXHook::GET_HOST_MCH_ID, function ($appid) {
	//非第三方微信平台不用实现
} );

YDWXHook::add_hook ( YDWXHook::GET_HOST_APICLIENT_CERT_PATH, function ($appid) {
	//非第三方微信平台不用实现
} );

YDWXHook::add_hook ( YDWXHook::GET_HOST_APICLIENT_KEY_PATH, function ($appid) {
	//非第三方微信平台不用实现
} );

YDWXHook::add_hook ( YDWXHook::GET_HOST_ROOT_CA, function ($appid) {
	//非第三方微信平台不用实现
} );

YDWXHook::add_hook ( YDWXHook::PAY_NOTIFY_ERROR, function ($error) {
    // 支付失败，error为错误字符串
} );

YDWXHook::add_hook ( YDWXHook::PAY_NOTIFY_SUCCESS, function (YDWXPaiedNotifyResponse $msg) {
    // 支付成功，模式2
} );

YDWXHook::add_hook ( YDWXHook::QRCODE_PAY_NOTIFY_SUCCESS, function (YDWXPaiedNotifyResponse $msg) {
	// 用户扫码成功，模式1
} );
