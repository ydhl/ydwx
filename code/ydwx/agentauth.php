<?php
/**
 * 公众号授权第三方平台托管流程
 */
chdir(dirname(__FILE__));

include_once dirname(__FILE__).'/__config__.php';

$auth_code = @$_GET["auth_code"];

if( ! $auth_code){
    YDWXHook::do_hook(YDWXHook::AUTH_CANCEL);
    die;
}

try{
    $auth_info = ydwx_agent_query_auth($auth_code);
}catch (\Exception $e){
    YDWXHook::do_hook(YDWXHook::AUTH_FAIL, YDWXAuthFailResponse::errMsg($e->getMessage()));
    die();
}
try{
    $auth_info->query = $_GET;
    YDWXHook::do_hook(YDWXHook::AUTH_AGENT_SUCCESS, 
    array($auth_info, ydwx_agent_get_auth_account($auth_info->authorizer_appid)));
}catch (\Exception $e){
    YDWXHook::do_hook(YDWXHook::AUTH_FAIL, YDWXAuthFailResponse::errMsg($e->getMessage()));
    die();
}