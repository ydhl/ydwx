<?php
use app\card\Card_Sn_Model;
use app\sp\Consumer_Model;
use app\common\User_Model;
use app\sp\Service_Provider_Model;
use app\card\Merchants_Model;
use app\data\User_Action_Model;
$oldcwd = getcwd ();
chdir ( dirname ( __FILE__ ) . '/../' );
require_once 'init.php';
chdir ( $oldcwd );

YDWXHook::add_hook ( YDWXHook::EVENT_CARD_NOT_PASS_CHECK, function (YDWXEventCard_not_pass_check $msg) {
    $Card_Model = reset(\app\card\Card_Model::find_by_attrs(array("wx_card_id"=>$msg->CardId)));
    if ($Card_Model) $Card_Model->set("status", "not_pass")->save();                       
} );

YDWXHook::add_hook ( YDWXHook::EVENT_CARD_PASS_CHECK, function (YDWXEventCard_pass_check $msg) {
    $Card_Model = reset(\app\card\Card_Model::find_by_attrs(array("wx_card_id"=>$msg->CardId)));
    if ($Card_Model) $Card_Model->set("status", "passed")->save();            
} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_CONSUME_CARD, function (YDWXEventUser_consume_card $msg) {
//    if ($msg->StaffOpenId) {
//        $staff_consumer = register_consumer($msg->StaffOpenId, $msg->APPID);  
//    }
	$now_time = yd_date();
	
    $Card_Model = reset(\app\card\Card_Model::find_by_attrs(array("wx_card_id"=>$msg->CardId)));    
    $Card_Sn_Model = reset(\app\card\Card_Sn_Model::find_by_attrs(array("sn"=>$msg->UserCardCode, "card_id"=>$Card_Model->id)));
    if ($Card_Sn_Model) {
        $Card_Sn_Model->set("status", "used")
            ->set("used_time", $now_time)
            ->save(); 
            
        \app\card\Card_Model::increaseUsedQty($Card_Model->id);
        
        //保存使用卡卷的user_action记录 @liulongxing20160106
        $consumer = Consumer_Model::find_by_id($Card_Sn_Model->consumer_id);
        if( $consumer ){
        	$action_data = array(
        			User_Action_Model::ACTION 			=> User_Action_Model::ACTION_USE,
        			User_Action_Model::USER_ID 			=> $consumer->user_id,
        			User_Action_Model::TYPE 				=> User_Action_Model::TYPE_CARD,
        			User_Action_Model::OBJECT_TYPE 	=> Card_Sn_Model::TABLE,
        			User_Action_Model::OBJECT_ID 		=> $Card_Sn_Model->id,
        			User_Action_Model::DATE 				=> $now_time,
        			User_Action_Model::SP_ID				=> $Card_Model->sp_id,
        			User_Action_Model::DEVICE_ID 		=> null,
        			User_Action_Model::CREATED_ON 	=> $now_time,
        	);
        	$action = User_Action_Model::saveAction($action_data);
        }
        
    }      
} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_DEL_CARD, function (YDWXEventUser_del_card $msg) {
    $Card_Model = reset(\app\card\Card_Model::find_by_attrs(array("wx_card_id"=>$msg->CardId)));    
    $Card_Sn_Model = reset(\app\card\Card_Sn_Model::find_by_attrs(array("sn"=>$msg->UserCardCode, "card_id"=>$Card_Model->id)));
    if ($Card_Sn_Model) {
        $Card_Sn_Model->set("status", "canceled")->save(); 
        
        //保存删除卡卷的user_action记录 @liulongxing20160106
        $consumer = Consumer_Model::find_by_id($Card_Sn_Model->consumer_id);
        $now_time = yd_date();
        if( $consumer ){
        	$action_data = array(
        			User_Action_Model::ACTION 			=> User_Action_Model::ACTION_DEL,
        			User_Action_Model::USER_ID 			=> $consumer->user_id,
        			User_Action_Model::TYPE 				=> User_Action_Model::TYPE_CARD,
        			User_Action_Model::OBJECT_TYPE 	=> Card_Sn_Model::TABLE,
        			User_Action_Model::OBJECT_ID 		=> $Card_Sn_Model->id,
        			User_Action_Model::DATE 				=> $now_time,
        			User_Action_Model::SP_ID				=> $Card_Model->sp_id,
        			User_Action_Model::DEVICE_ID 		=> null,
        			User_Action_Model::CREATED_ON 	=> $now_time,
        	);
        	$action = User_Action_Model::saveAction($action_data);
        }
        
    }
} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_ENTER_SESSION_FROM_CARD, function ( YDWXEventUser_enter_session_from_card $msg) {
} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_GET_CARD, function (YDWXEventUser_get_card $msg) {
    $Card_Model     = reset(\app\card\Card_Model::find_by_attrs(array("wx_card_id"=>$msg->CardId)));
    $Card_Sn_Model  = reset(\app\card\Card_Sn_Model::find_by_attrs(array("sn"=>$msg->UserCardCode, "card_id"=>$Card_Model->id)));
    
    if ($Card_Sn_Model) return;
    
    $consumer   = register_consumer($msg->FromUserName, $msg->APPID);   
    
    $now        = yd_date();
    
    $Card_Sn_Model = new \app\card\Card_Sn_Model();
    $Card_Sn_Model->set("consumer_id", $consumer->id)
        ->set("sn",         $msg->UserCardCode)
        ->set("card_id",    $Card_Model->id)
        ->set("status",     Card_Sn_Model::GOT)
        ->set("got_time",   $msg->CreateTime ? date("Y-m-d H:i:s", $msg->CreateTime) : $now)
        ->set("outer_id",   $msg->OuterId)
        ->set("created_on", $now);

    if ($msg->IsGiveByFriend) {
        $oldCardSn = reset(Card_Sn_Model::find_by_attrs(array("sn"=>$msg->OldUserCardCode, "card_id"=>$Card_Model->id)));
        if($oldCardSn){
            $oldCardSn->set("status",     Card_Sn_Model::SHARED)
            ->set("share_to_consumer_id", $consumer->id)
            ->set("new_sn",               $msg->UserCardCode)->save();
        }
        
        $friend_consumer = register_consumer($msg->FriendUserName, $msg->APPID);
        
        $Card_Sn_Model->set("old_sn", $msg->OldUserCardCode);
        $Card_Sn_Model->set("share_consumer_id", $friend_consumer->id);
    } else {
        \app\card\Card_Model::reduceLeftQty($Card_Model->id);
    }
    $Card_Sn_Model->save();
    
    //保存user_action记录
    $action_data = array(
    		User_Action_Model::ACTION 			=> User_Action_Model::ACTION_GOT,
    		User_Action_Model::USER_ID 			=> $consumer->user_id,
    		User_Action_Model::TYPE 				=> User_Action_Model::TYPE_CARD ,
    		User_Action_Model::OBJECT_TYPE 	=> Card_Sn_Model::TABLE,
    		User_Action_Model::OBJECT_ID 		=> $Card_Sn_Model->id,
    		User_Action_Model::DATE 				=> $now,
    		User_Action_Model::SP_ID				=> $Card_Model->sp_id,
    		User_Action_Model::DEVICE_ID 		=> null,
    		User_Action_Model::CREATED_ON 	=> $now,
    );
    $action = User_Action_Model::saveAction($action_data);
} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_PAY_FROM_PAY_CELL, function (YDWXEventUser_pay_from_pay_cell $msg) {
        
} );

YDWXHook::add_hook ( YDWXHook::EVENT_USER_VIEW_CARD, function ($msg) {
} );

YDWXHook::add_hook ( YDWXHook::EVENT_CARD_MERCHANT_CHECK_RESULT, function (YDWXEventCard_merchant_check_result $msg) {
    $merchant = reset(Merchants_Model::find_by_attrs(array("wx_merchant_id"=>$msg->MerchantId)));
    if( ! $merchant)return;
    $merchant->set("check_status",  $msg->IsPass ? Merchants_Model::PASSED : Merchants_Model::NOT_PASS)
    ->set("check_comment",          $msg->Reason)
    ->set("check_time",             date("Y-m-d H:i:s"))
    ->save();
} );