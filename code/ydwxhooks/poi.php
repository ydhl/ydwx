<?php

$oldcwd = getcwd ();
chdir ( dirname ( __FILE__ ) . '/../' );
require_once 'init.php';
chdir ( $oldcwd );

//门店审核通过的通知
YDWXHook::add_hook ( YDWXHook::EVENT_POI_CHECK_NOTIFY, function (YDWXEventPoi_check_notify $info) {

} );