<?php
/**
 * 微信access token刷新及jsapi_ticket。该脚本需要加入定时执行队列，比如corn
 * crontab -e
 * 0 *\/2 * * * php /var/www/html/ydwx/refresh.php
 * crontab -l
 */
chdir(dirname(__FILE__));//把工作目录切换到文件所在目录
include_once dirname(__FILE__).'/__config__.php';

$http = new YDHttp();

if(YDWX_WEIXIN_ACCOUNT_TYPE == YDWX_WEIXIN_ACCOUNT_TYPE_CROP){//企业号
    $msg = $http->get(YDWX_WEIXIN_QY_BASE_URL."gettoken?corpid=".YDWX_WEIXIN_CROP_ID."&corpsecret=".YDWX_WEIXIN_CROP_SECRET);
}else{//其它微信号
    $msg = $http->get(YDWX_WEIXIN_BASE_URL."token?grant_type=client_credential&appid=".YDWX_WEIXIN_APP_ID."&secret=".YDWX_WEIXIN_APP_SECRET);
}
$accessToken = new YDWXAccessTokenResponse($msg);
if($accessToken->isSuccess()) YDWXHook::do_hook(YDWXHook::ACCESS_TOKEN_REFRESH, $accessToken);

if(YDWX_WEIXIN_ACCOUNT_TYPE == YDWX_WEIXIN_ACCOUNT_TYPE_CROP){//企业号
    $msg = $http->get(YDWX_WEIXIN_QY_BASE_URL."get_jsapi_ticket?access_token=".$accessToken->access_token);
}else{//其它微信号
    $msg = $http->get(YDWX_WEIXIN_BASE_URL."ticket/getticket?type=jsapi&access_token=".$accessToken->access_token);
    
}
$ticket = new YDWXJsapiTicketResponse($msg);
if($ticket->isSuccess()) YDWXHook::do_hook(YDWXHook::JSAPI_TICKET_REFRESH, $info);
die("success");