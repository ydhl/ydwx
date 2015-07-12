<?php
/**
 * 微信access token刷新及jsapi_ticket。该脚本需要加入定时执行队列，比如corn
 * crontab -e
 * 0 *\/2 * * * php /var/www/html/ydwx/refresh.php
 * crontab -l
 */
chdir(dirname(__FILE__));
require_once './libs/wx.php';

$http = new YDHttp();

if(WEIXIN_ACCOUNT_TYPE == WEIXIN_ACCOUNT_CROP){//企业号
    $msg = $http->get(WEIXIN_QY_BASE_URL."gettoken?corpid=".WEIXIN_CROP_ID."&corpsecret=".WEIXIN_CROP_SECRET);
}else{//其它微信号
    $msg = $http->get(WEIXIN_BASE_URL."token?grant_type=client_credential&appid=".WEIXIN_APP_ID."&secret=".WEIXIN_APP_SECRET);
}
$info = json_decode($msg, true);

if( ! @$info['access_token']) die("access_token fail");


YDHook::do_hook(WXHooks::ACCESS_TOKEN_REFRESH, $info);

if(WEIXIN_ACCOUNT_TYPE == WEIXIN_ACCOUNT_CROP){//企业号
    $msg = $http->get(WEIXIN_QY_BASE_URL."get_jsapi_ticket?access_token=".$info['access_token']);
}else{//其它微信号
    $msg = $http->get(WEIXIN_BASE_URL."ticket/getticket?type=jsapi&access_token=".$info['access_token']);
    
}

$info = json_decode($msg, true);

if( ! @$info['ticket']) die("jsapi_token fail");
YDHook::do_hook(WXHooks::JSAPI_TICKET_REFRESH, $info);
die("success");