<?php

$oldcwd = getcwd ();
chdir ( dirname ( __FILE__ ) . '/../' );
require_once 'init.php';
chdir ( $oldcwd );

YDWXHook::add_hook ( YDWXHook::EVENT_SHAKEAROUNDUSERSHAKE, function (YDWXEventShakearoundusershake $msg) {

} );