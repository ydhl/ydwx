<?php
use app\common\User_Model;
use app\wxmsg\Auto_Reply_Model;
use app\sp\Consumer_Model;
use app\common\Option_Model;
use app\sp\Service_Provider_Model;

$oldcwd = getcwd();
chdir ( dirname ( __FILE__ ) . '/../' );
require_once 'init.php';
chdir ( $oldcwd );

YDWXHook::add_hook(YDWXHook::EVENT_MSG_TEXT, function(YDWXEventMsgText $msg){
    if($msg->Content=="TESTCOMPONENT_MSG_TYPE_TEXT"){
        $answerMsg = YDWXAnswerMsg::buildTextMsg("TESTCOMPONENT_MSG_TYPE_TEXT_callback", $msg);
        ydwx_answer_msg($answerMsg);
        die;
    }
    
    if(preg_match("/^QUERY_AUTH_CODE:(?P<code>.+)$/", $msg->Content, $matches)){
        try{
       	 	$auth = ydwx_agent_query_auth($matches['code']);
        	YDWXHook::do_hook(YDWXHook::YDWX_LOG, $matches['code']."///".$auth->authorizer_access_token);
        	echo "";
        	ob_flush();
        	$cusMsg = YDWXMassCustomRequest::buildTextMsg($matches['code']."_from_api");
            $cusMsg->to = $msg->FromUserName;
            YDWXHook::do_hook(YDWXHook::YDWX_LOG, "custom message".$cusMsg->toJSONString());
        	ydwx_message_custom_send($auth->authorizer_access_token, $cusMsg);
        }catch(\Exception $e){
            YDWXHook::do_hook(YDWXHook::YDWX_LOG, $e->getMessage());
        }
        die;
    }
    
    $keyword = trim($msg->Content);
    if (empty($keyword)) return;
    
    $openid = $msg->FromUserName;
    $consumer = Consumer_Model::get_by_openid($openid);
    
    if( ! $consumer) {
        $sp = reset(Service_Provider_Model::find_by_attrs(array("appid"=>$msg->APPID)));
        if( ! $sp) return;
        $sp_id = $sp->get_key();
    } else {
        $sp_id = $consumer->get("sp_id");
    }

    auto_reply($sp_id, "keywords", $msg, $keyword);

});
YDWXHook::add_hook(YDWXHook::EVENT_LOCATION, function(YDWXEventLocation $msg){
// 公众平台验证时开启它    
//     $answerMsg = YDWXAnswerMsg::buildTextMsg("LOCATIONfrom_callback", $msg);
//         ydwx_answer_msg($answerMsg);
//         die;

});