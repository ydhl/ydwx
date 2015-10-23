<?php
/**
 * $oldcwd = getcwd();
 * #hook
 * chdir($your_work_dir);
 * include_once 'your-lib-file.php';
 * chdir ( $oldcwd );
 */

YDWXHook::add_hook ( YDWXHook::EVENT_CARD_NOT_PASS_CHECK, function (YDWXEventCard_not_pass_check $msg) {
              
} );

YDWXHook::add_hook ( YDWXHook::EVENT_CARD_PASS_CHECK, function (YDWXEventCard_pass_check $msg) {
       
} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_CONSUME_CARD, function (YDWXEventUserConsumeCard $msg) {
    
} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_DEL_CARD, function (YDWXEventUser_del_card $msg) {
   
} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_ENTER_SESSION_FROM_CARD, function ( YDWXEventUser_enter_session_from_card $msg) {
} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_GET_CARD, function (YDWXEventUser_get_card $msg) {
    
} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_PAID_BY_CARD, function (YDWXEventUserPaidByCard $msg) {
        
} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_VIEW_CARD, function ($msg) {
} );