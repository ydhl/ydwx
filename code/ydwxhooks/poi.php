<?php
use app\common\Option_Model;
use app\wxpoi\Shop_Model;
$oldcwd = getcwd ();
chdir ( dirname ( __FILE__ ) . '/../' );
require_once 'init.php';
chdir ( $oldcwd );

//门店审核通过的通知
YDWXHook::add_hook ( YDWXHook::EVENT_POI_CHECK_NOTIFY, function (YDWXEventPoi_check_notify $info) {
    YDWXHook::do_hook(YDWXHook::YDWX_LOG, "EVENT_POI_CHECK_NOTIFY".$info->UniqId);
    //保存门店id
    $shop = Shop_Model::find_by_id($info->UniqId);
    if( ! $shop)return;
    if($info->result=="succ"){
        $shop->set("poi_id", $info->PoiId)->set("available_state", 3)->save();
    }else{
        $shop->set("check_result", $info->msg)->set("available_state", 4)->save();
    }
} );