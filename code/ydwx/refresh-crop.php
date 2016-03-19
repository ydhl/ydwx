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

//作为第三方平台刷新平台的access token,这时所托管的公众号token的刷新要开发者自己负责
//
//刷新access_token
    try{
        $accessToken = ydwx_qy_refresh_access_token(YDWX_WEIXIN_CROP_ID, YDWX_WEIXIN_CROP_SECRET);
        
        YDWXHook::do_hook(YDWXHook::REFRESH_QY_ACCESS_TOKEN, $accessToken);
    
        //刷新jsapi ticket
        $ticket = ydwx_qy_refresh_jsapi_ticket($accessToken->access_token);
        
        YDWXHook::do_hook(YDWXHook::REFRESH_QY_JSAPI_TICKET, $ticket);
    
        //刷新微信card api ticket
        $ticket = ydwx_refresh_card_jsapi_ticket($accessToken->access_token);
        YDWXHook::do_hook(YDWXHook::REFRESH_QY_CARD_JSAPI_TICKET, $ticket);
    }catch (\Exception $e){
        echo " accessToken: ".$e->getMessage()."<br/>";
    }

die("success");