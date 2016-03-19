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
 */
chdir(dirname(__FILE__));//把工作目录切换到文件所在目录
include_once dirname(__FILE__).'/__config__.php';

//刷新access_token
    try{
        $accessToken = ydwx_refresh_access_token(YDWX_WEIXIN_APP_ID, YDWX_WEIXIN_APP_SECRET);
        
        YDWXHook::do_hook(YDWXHook::REFRESH_ACCESS_TOKEN, $accessToken);
    
        //刷新jsapi ticket
        $ticket = ydwx_refresh_jsapi_ticket($accessToken->access_token);
        
        YDWXHook::do_hook(YDWXHook::REFRESH_JSAPI_TICKET, $ticket);
    
        //刷新微信card api ticket
        $ticket = ydwx_refresh_card_jsapi_ticket($accessToken->access_token);
        YDWXHook::do_hook(YDWXHook::REFRESH_CARD_JSAPI_TICKET, $ticket);
    }catch (\Exception $e){
        echo " accessToken: ".$e->getMessage()."<br/>";
    }

die("success");