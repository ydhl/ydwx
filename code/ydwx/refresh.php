<?php
/**
 * 该web api用于刷新微信开发者端的相关token，该脚本需要2小时定时执行，
 * 开发者可以通过cron来实现：
 *  crontab -e
 *  0 *\/2 * * * php /var/www/html/ydwx/refresh.php
 *  crontab -l
 *  
 * 也可以通过ydtimer这类网络定时器
 * ydtimer.yidianhulian.com
 * 
 * 
 * 注意：如果你的系统是作为第三方微信托管平台，你要需要定时刷新你所托管的公众号的令牌数据，
 * 这需要你自己写代码，调用ydwx_agent_refresh_auth_token实现
 * 这里只负责刷新平台方的微信公众号数据
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

if(YDWX_WEIXIN_COMPONENT_APP_ID){//作为第三方平台
    try{
        $token = ydwx_agent_access_token(YDWXHook::do_hook(YDWXHook::GET_VERIFY_TICKET));
        YDWXHook::do_hook(YDWXHook::AGENT_ACCESS_TOKEN_REFRESH, $token);
    }catch (\Exception $e){
        
    }
}

die("success");