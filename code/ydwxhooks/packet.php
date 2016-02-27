<?php
use app\package\Wx_Packet_Ticket_Model;
use app\package\Wx_Packet_Activity_Model;
use app\package\Wx_Packet_Order_Model;
use app\data\User_Action_Model;
/**
 * 红包事件回调
 */

YDWXHook::add_hook(YDWXHook::EVENT_SHAKEAROUNDLOTTERYBIND, function (YDWXEventShakearoundLotteryBind $bind){
    //微信红包绑定事件
	$consumer = register_consumer($bind->FromUserName, $bind->APPID);
	$find_order = Wx_Packet_Order_Model::find_by_attrs(array("sp_ticket" => $bind->Ticket));
	if( ! $find_order ){
		return "";
	}
	$find_order = reset($find_order);
	$find_activity = Wx_Packet_Activity_Model::find_by_attrs(array("lottery_id" => $bind->LotteryId));
	if( ! $find_activity ){
		return "";
	}
	$find_activity = reset($find_activity);
	$now = yd_date();
	if( ! $bind->BindTime ){
		$got_time = $now;
	}else {
		$got_time = date('Y-m-d H:i:s', $bind->BindTime);
	}
	$ticket = new Wx_Packet_Ticket_Model();
	$ticket->set("ticket", $bind->Ticket)
		->set("status", Wx_Packet_Ticket_Model::GOT)
		->set("packet_order_id", $find_order->id)
		->set("packet_activitity_id", $find_activity->id)
		->set("got_time", $got_time)
		->set("money", 0)#$bind->Money 不是真是领取金额
		->set("consumer_id", $consumer->id)
		->set("created_on", $now)
		->save();
	
	//保存领取摇一摇红包user_action记录 @liulongxing20160106
	$action_data = array(
			User_Action_Model::ACTION 			=> User_Action_Model::ACTION_GOT,
			User_Action_Model::USER_ID 			=> $consumer->user_id,
			User_Action_Model::TYPE 				=> User_Action_Model::TYPE_PACKAGE,
			User_Action_Model::OBJECT_TYPE 	=> Wx_Packet_Ticket_Model::TABLE,
			User_Action_Model::OBJECT_ID 		=> $ticket->id,
			User_Action_Model::DATE 				=> $now,
			User_Action_Model::SP_ID				=> $find_order->sp_id,
			User_Action_Model::CREATED_ON 	=> $now,
	);
	User_Action_Model::saveAction($action_data);
});