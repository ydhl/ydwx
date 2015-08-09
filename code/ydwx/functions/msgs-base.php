<?php
/**
 * 向微信回复消息
 * 
 * @param YDWXAnswerMsg $msg
 */
function ydwx_answer_msg(YDWXAnswerMsg $msg){
	ob_start();
	echo $msg->toXMLString();
	ob_end_flush();
}
