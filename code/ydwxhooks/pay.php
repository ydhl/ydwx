<?php
use app\admin\Pay_History_Model;
use yangzie\YZE_SQL;
use app\admin\Pay_Way_Model;
use app\sp\Service_Provider_Model;
use yangzie\YZE_DBAImpl;


YDWXHook::add_hook ( YDWXHook::GET_HOST_MCH_KEY, function ($appid) {
    $find_sp = reset(Service_Provider_Model::find_by_attrs(array("appid" => $appid)));
    if( ! $find_sp ){
        return "";
    }
	if( $find_sp->hasMchSetting() ){
	    $setting = $find_sp->getMchSettings();
		return $setting['mch_key'];
	}
    return YDWX_WEIXIN_MCH_KEY;
} );

YDWXHook::add_hook ( YDWXHook::GET_HOST_MCH_ID, function ($appid) {
    $find_sp = reset(Service_Provider_Model::find_by_attrs(array("appid" => $appid)));
    if( ! $find_sp ){
        return "";
    }
	if( $find_sp->hasMchSetting() ){
	    $setting = $find_sp->getMchSettings();
		return $setting['mch_id'];
	}
    return YDWX_WEIXIN_MCH_ID;
} );

YDWXHook::add_hook ( YDWXHook::GET_HOST_APICLIENT_CERT_PATH, function ($appid) {
    $find_sp = reset(Service_Provider_Model::find_by_attrs(array("appid" => $appid)));
    if( ! $find_sp ){
        return "";
    }
	if( $find_sp->hasMchSetting() ){
	    $setting = $find_sp->getMchSettings();
		return $setting['apiclient_cert'];
	}
	
	return YDWX_WEIXIN_APICLIENT_CERT;
} );

YDWXHook::add_hook ( YDWXHook::GET_HOST_APICLIENT_KEY_PATH, function ($appid) {
    $find_sp = reset(Service_Provider_Model::find_by_attrs(array("appid" => $appid)));
    if( ! $find_sp ){
        return "";
    }
	if( $find_sp->hasMchSetting() ){
	    $setting = $find_sp->getMchSettings();
		return $setting['apiclient_key'];
	}
	
	return YDWX_WEIXIN_APICLIENT_KEY;
} );

YDWXHook::add_hook ( YDWXHook::GET_HOST_ROOT_CA, function ($appid) {
    $find_sp = reset(Service_Provider_Model::find_by_attrs(array("appid" => $appid)));
    if( ! $find_sp ){
        return "";
    }
	if( $find_sp->hasMchSetting() ){
	    $setting = $find_sp->getMchSettings();
		return $setting['rootca'];
	}
	
	return YDWX_WEIXIN_ROOTCA;
} );

YDWXHook::add_hook ( YDWXHook::PAY_NOTIFY_ERROR, function ($error) {
    // 支付失败，error为错误字符串
} );

function sp_charge(YDWXPaiedNotifyResponse $msg, $attachArray){
    $pay = reset ( Pay_History_Model::find_by_attrs ( array (
            "order_no" => $msg->out_trade_no
    ) ) );
    if($pay)return true;
    
    $sp = Service_Provider_Model::find_by_id($attachArray['sp_id']);
    if( ! $sp)return true;
    if( ! $sp->hasMchSetting()){
        $sql = "update service_providers set money = money+".$attachArray['pay_price']." where id=".$attachArray['sp_id'];
        YZE_DBAImpl::getDBA()->exec($sql);
    }
    $pay = new Pay_History_Model ();
    $pay->set("pay_time",   date ( "Y-m-d H:i:s" ))
        ->set("pay_price",  $attachArray['pay_price'])
        ->set("pay_way_id", $attachArray['pay_way_id'])
        ->set("order_no",   $msg->out_trade_no)
        ->set("sp_id",      $attachArray['sp_id'])
        ->save();
    return true;
}

YDWXHook::add_hook ( YDWXHook::PAY_NOTIFY_SUCCESS, function (YDWXPaiedNotifyResponse $msg) {
    // 支付成功的处理
    $attach = urldecode ( $msg->attach );
    $attachArray = array ();
    parse_str ( $attach, $attachArray );
    if(@$attachArray['action']=="charge"){//SP 充值
        return sp_charge($msg, $attachArray);
    }
    
    $outTradeNo = $msg->out_trade_no;
    $pay = reset ( Pay_History_Model::find_by_attrs ( array (
            "order_no" => $outTradeNo 
    ) ) );
    if (! $pay) {
        $pay = new Pay_History_Model ();
        foreach ( $attachArray as $name => $value ) {
            $pay->set ( $name, $value );
        }
        
        $pay->set ( "pay_time", date ( "Y-m-d H:i:s" ) )->set ( "order_no", $outTradeNo )->set ( "created_on", date ( "Y-m-d H:i:s" ) )->save ();
        
        $pay_way_id = $pay->get ( "pay_way_id" );
        $pay_way = Pay_Way_Model::find_by_id ( $pay_way_id );
//         $type = $pay_way->get ( "type" );		//20151112@liulongxing 用户购买升级时不更新sp的type,通过查询购买历史判断type
        $sql = new YZE_SQL (); // 新的过期时间计算,保存该条付费历史的过期时间
        $sql->from ( 'app\admin\Pay_History_Model', 'ph' )->left_join ( 'app\admin\Pay_Way_Model', 'pw', 'ph.pay_way_id=pw.id' )->where ( 'ph', 'sp_id', YZE_SQL::EQ, get_sp_id () )->where ( 'pw', 'type', YZE_SQL::EQ, $type )->where ( 'ph', 'expired_time', YZE_SQL::GT, yd_date () )->order_by ( 'ph', 'expired_time', 'desc' )->limit ( 1 );
        $find_ph = YZE_DBAImpl::getDBA ()->select ( $sql ); // 查找没过期的升级该类型账户的历史
        if ($find_ph) { // 如果有没过期的记录，则新插入的付费历史的过期时间为原过期时间+1年
            $new_expired_time = date ( 'Y-m-d H:i:s', strtotime ( "+ 1 yeas", strtotime ( $find_ph [0]->expired_time ) ) );
        } else { // 如果没有没过期的记录，则新插入的付费历史的过期时间现在+1年
            $new_expired_time = date ( 'Y-m-d H:i:s', strtotime ( "+ 1 yeas", time () ) );
        }
        $pay->set ( "expired_time", $new_expired_time )->save ();
        
//         $sp = Service_Provider_Model::find_by_id ( $pay->get ( sp_id ) ); // 更新sp的type  //20151112@liulongxing 用户购买升级时不更新sp的type,通过查询购买历史判断type
//         $sp->set ( "type", $type )->save ();
    }
    return true;
} );
