<?php

$oldcwd = getcwd ();
chdir ( dirname ( __FILE__ ) . '/../' );
require_once 'init.php';
chdir ( $oldcwd );

YDWXHook::add_hook ( YDWXHook::EVENT_CARD_NOT_PASS_CHECK, function (YDWXEventCard_not_pass_check $msg) {
} );

YDWXHook::add_hook ( YDWXHook::EVENT_CARD_PASS_CHECK, function (YDWXEventCard_pass_check $msg) {

} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_CONSUME_CARD, function (YDWXEventUser_consume_card $msg) {

} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_DEL_CARD, function (YDWXEventUser_del_card $msg) {

} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_ENTER_SESSION_FROM_CARD, function ( YDWXEventUser_enter_session_from_card $msg) {
} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_GET_CARD, function (YDWXEventUser_get_card $msg) {

} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_PAY_FROM_PAY_CELL, function (YDWXEventUser_pay_from_pay_cell $msg) {
        
} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_VIEW_CARD, function ($msg) {
} );

YDWXHook::add_hook ( YDWXHook::EVENT_CARD_MERCHANT_CHECK_RESULT, function (YDWXEventCard_merchant_check_result $msg) {

} );