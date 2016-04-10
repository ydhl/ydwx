<?php
use app\admin\Pay_History_Model;
use yangzie\YZE_SQL;
use app\admin\Pay_Way_Model;
use app\sp\Service_Provider_Model;
use yangzie\YZE_DBAImpl;


YDWXHook::add_hook ( YDWXHook::GET_HOST_MCH_KEY, function ($appid) {

} );

YDWXHook::add_hook ( YDWXHook::GET_HOST_MCH_ID, function ($appid) {

} );

YDWXHook::add_hook ( YDWXHook::GET_HOST_APICLIENT_CERT_PATH, function ($appid) {

} );

YDWXHook::add_hook ( YDWXHook::GET_HOST_APICLIENT_KEY_PATH, function ($appid) {

} );

YDWXHook::add_hook ( YDWXHook::GET_HOST_ROOT_CA, function ($appid) {

} );

YDWXHook::add_hook ( YDWXHook::PAY_NOTIFY_ERROR, function ($error) {
    // 支付失败，error为错误字符串
} );

YDWXHook::add_hook ( YDWXHook::PAY_NOTIFY_SUCCESS, function (YDWXPaiedNotifyResponse $msg) {

} );
