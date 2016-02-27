<?php
use app\common\Option_Model;
$oldcwd = getcwd ();
chdir ( dirname ( __FILE__ ) . '/../' );
require_once 'init.php';
chdir ( $oldcwd );

YDWXHook::add_hook ( YDWXHook::EVENT_COMPONENT_VERIFY_TICKET, function (YDWXEventComponent_verify_ticket $info) {
    Option_Model::option_save ( YDWXHook::EVENT_COMPONENT_VERIFY_TICKET, $info->ComponentVerifyTicket );
} );

YDWXHook::add_hook ( YDWXHook::GET_VERIFY_TICKET, function () {
    $option = Option_Model::option_get ( YDWXHook::EVENT_COMPONENT_VERIFY_TICKET );
    if ($option)
        return $option->get ( "option_value" );
    return "";
} );

YDWXHook::add_hook ( YDWXHook::REFRESH_AGENT_ACCESS_TOKEN, function (YDWXAccessTokenResponse $info) {
    Option_Model::option_save ( YDWXHook::REFRESH_AGENT_ACCESS_TOKEN, $info->access_token );
} );

YDWXHook::add_hook ( YDWXHook::GET_AGENT_ACCESS_TOKEN, function () {
    $option = Option_Model::option_get ( YDWXHook::REFRESH_AGENT_ACCESS_TOKEN );
    if ($option)
        return $option->get ( "option_value" );
    return "";
} );