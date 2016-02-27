<?php
/**
 * 摇一摇抽红包js后端
 */
chdir(dirname(__FILE__));//把工作目录切换到文件所在目录
include_once dirname(__FILE__).'/__config__.php';

$appid = $_GET['appid'];
$code = $_GET['code'];
$type = $_GET['type'];
if(YDWX_WEIXIN_COMPONENT_APP_ID){
    $access_token = YDWXHook::do_hook(YDWXHook::GET_ACCESS_TOKEN);
}else{
    $access_token = YDWXHook::do_hook(YDWXHook::GET_HOST_ACCESS_TOKEN, $appid);
}
$user = ydwx_yaotv_get_userinfo($appid, $access_token, $code);
YDWXHook::do_hook(YDWXHook::YAOTV_USER_AUTH, $user);
echo json_encode(ydwx_success(get_object_vars($user)));
?>