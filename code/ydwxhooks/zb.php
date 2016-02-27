<?php
use app\common\Option_Model;
use app\zb\Page_Link_Model;
use app\sp\Service_Provider_Model;
use app\zb\Device_Model;
use app\data\User_Action_Model;
use yangzie\YZE_Hook;
$oldcwd = getcwd ();
chdir ( dirname ( __FILE__ ) . '/../' );
require_once 'init.php';
chdir ( $oldcwd );

YDWXHook::add_hook ( YDWXHook::EVENT_SHAKEAROUNDUSERSHAKE, function (YDWXEventShakearoundusershake $msg) {
    $consumer  = \register_consumer($msg->FromUserName, $msg->APPID);
    $sp        = Service_Provider_Model::find_by_attrs(array("appid"=>$msg->APPID), true);
    $device    = Device_Model::find_by_attrs(array(
            "major"=>$msg->ChosenBeacon->major,
            "minor"=>$msg->ChosenBeacon->minor,
            "uuid" =>$msg->ChosenBeacon->uuid), true);

    $device->set("last_actived_on", date("Y-m-d H:i:s"))->save();
//     $action    = new User_Action_Model();
    
//     $action->set(User_Action_Model::ACTION, User_Action_Model::ACTION_SHAKE)
//     ->set(User_Action_Model::USER_ID,       $consumer->user_id)
//     ->set(User_Action_Model::TYPE,          User_Action_Model::TYPE_ZBAD)
//     ->set(User_Action_Model::OBJECT_TYPE,   Device_Model::TABLE)
//     ->set(User_Action_Model::OBJECT_ID,     $device->id)
//     ->set(User_Action_Model::DATE,          date("Y-m-d H:i:s"))
//     ->set(User_Action_Model::DEVICE_ID,     $device->id)
//     ->set(User_Action_Model::CREATED_ON,    date("Y-m-d H:i:s"))
//     ->save();
    
    $action_data = array(
    		User_Action_Model::ACTION 			=> User_Action_Model::ACTION_SHAKE,
    		User_Action_Model::USER_ID 			=> $consumer->user_id,
    		User_Action_Model::TYPE 				=> User_Action_Model::TYPE_ZBAD,
    		User_Action_Model::OBJECT_TYPE 	=> Device_Model::TABLE,
    		User_Action_Model::OBJECT_ID 		=> $device->id,
    		User_Action_Model::DATE 				=> date("Y-m-d H:i:s"),
    		User_Action_Model::SP_ID				=> $sp->id,
    		User_Action_Model::DEVICE_ID 		=> $device->id,
    		User_Action_Model::CREATED_ON 	=> date("Y-m-d H:i:s"),
    );
	$action = User_Action_Model::saveAction($action_data);
    
    YZE_Hook::do_hook(YDMARKET_HOOK_CALCULATE_AD_PRICE, array("sp_id"=>$sp->id,"action_id"=>$action->id));
} );