<?php
/**
 * log 处理，比如想再某个地方加log进行调试则调用
 * YDWXHook::do_hook(YDWXHook::YDWX_LOG, $msg)
 * 由于是你自己处理log，所以msg你任意传入，你自己处理
 */
YDWXHook::add_hook(YDWXHook::YDWX_LOG, function($msg){
    //如log 写数据库
});