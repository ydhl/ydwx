<?php


//引用你项目的文件及库

YDWXHook::add_hook(YDWXHook::EVENT_MSG_TEXT, function(YDWXEventMsgText $msg){


});
YDWXHook::add_hook(YDWXHook::EVENT_LOCATION, function(YDWXEventLocation $msg){
// 公众平台验证时开启它    
//     $answerMsg = YDWXAnswerMsg::buildTextMsg("LOCATIONfrom_callback", $msg);
//         ydwx_answer_msg($answerMsg);
//         die;

});