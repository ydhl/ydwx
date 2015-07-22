<?php

/**
 * 企业号发送消息
 * 
 * @see http://qydev.weixin.qq.com/wiki/index.php?title=%E6%B6%88%E6%81%AF%E7%B1%BB%E5%9E%8B%E5%8F%8A%E6%95%B0%E6%8D%AE%E6%A0%BC%E5%BC%8F
 */
function qySendTextMsg($accessToken,  $content, $safe=false, $toUser="@all", $toParty=null, $toTag=null){

    if( WEIXIN_ACCOUNT_TYPE != WEIXIN_ACCOUNT_CROP)return array();
    $msgs = array();
    if($toUser){
        $msgs['touser']      = is_array($toUser) ? join("|", $toUser) : $toUser;
    }
    if($toParty){
        $msgs['toparty']     = is_array($toParty) ? join("|", $toParty) : $toParty;
    }
    if($toTag){
        $msgs['totag']       = is_array($toTag) ? join("|", $toTag) : $toTag;
    }
    $msgs['msgtype']         = "text";
    $msgs['agentid']         = WEIXIN_CROP_AGENT_ID;
    $msgs['text']['content'] = urlencode($content);
    $msgs['safe']            = $safe ? 1 : 0;

    
    $http = new YDHttp();
    $info = $http->post(WEIXIN_QY_BASE_URL."message/send?access_token={$accessToken}",
    urldecode(json_encode($msgs)));
    $info = json_decode($info, true);
    return !@$info['errcode'];
}

/**
 * 企业号发送消息
 *
 * @param $messageArray array(array("title","description","url","picurl"))
 * @see http://qydev.weixin.qq.com/wiki/index.php?title=%E6%B6%88%E6%81%AF%E7%B1%BB%E5%9E%8B%E5%8F%8A%E6%95%B0%E6%8D%AE%E6%A0%BC%E5%BC%8F
 */
function qySendNewsMsg($accessToken,  $messageArray, $safe=false, $toUser="@all", $toParty=null, $toTag=null){

    if( WEIXIN_ACCOUNT_TYPE != WEIXIN_ACCOUNT_CROP)return array();
    $msgs = array();
    if($toUser){
        $msgs['touser']      = is_array($toUser) ? join("|", $toUser) : $toUser;
    }
    if($toParty){
        $msgs['toparty']     = is_array($toParty) ? join("|", $toParty) : $toParty;
    }
    if($toTag){
        $msgs['totag']       = is_array($toTag) ? join("|", $toTag) : $toTag;
    }
    $msgs['msgtype']         = "news";
    $msgs['agentid']         = WEIXIN_CROP_AGENT_ID;
    $msgs['news']['articles']= $messageArray;
    $msgs['safe']            = $safe ? 1 : 0;


    $http = new YDHttp();
    $info = $http->post(WEIXIN_QY_BASE_URL."message/send?access_token={$accessToken}",
    yd_json_encode($msgs));
    
    $info = json_decode($info, true);
    return !@$info['errcode'];
}

/**
 * 发送模板消息
 * @param unknown $accessToken
 * @param unknown $openid
 * @param unknown $template_id
 * @param unknown $url
 * @param unknown $data
 * @return multitype:|boolean
 */
function sendTplMsg($accessToken,  $openid, $template_id, $url, $data){

    if( ! WEIXIN_IS_AUTHED || WEIXIN_ACCOUNT_TYPE != WEIXIN_ACCOUNT_SERVICE)return array();
    $msgs = array();
    $msgs['touser']          = $openid;
    $msgs['template_id']     = $template_id;
    $msgs['url']             = $url;
    $msgs['topcolor']        = "#ff0000";
    $msgs['data']            = $data;
    
    $http = new YDHttp();
    $info = $http->post(WEIXIN_BASE_URL."message/template/send?access_token={$accessToken}",
    json_encode($msgs));
    insert("logs", array("content"=>$info.json_encode($msgs)));
    $info = json_decode($info, true);
    return !@$info['errcode'];
}

/**
 * 根据openid进行群发文本消息 (订阅号不可用，服务号认证后可用)
 */
function SendTextByOpenids($accessToken,  $openids, $content){
    $openids = (array)$openids;
    if( ! WEIXIN_IS_AUTHED || WEIXIN_ACCOUNT_TYPE != WEIXIN_ACCOUNT_SERVICE)return array();
    $msgs = array();
    $msgs['text']['content'] = urlencode($content);
    $msgs['msgtype']         = "text";
    $msgs['touser']          = $openids;
    $http = new YDHttp();
    $info = $http->post(WEIXIN_BASE_URL."message/mass/send?access_token={$accessToken}", 
        urldecode(json_encode($msgs)));
    insert("logs", array("content"=>$info.urldecode(json_encode($msgs))));
    $info = json_decode($info, true);
    return !@$info['errcode'];
}

/**
 * 向微信回复文字内容
 * 
 * @param from 公众号openid
 * @param to 接受者微信openid
 */
function answerTextMessage($from, $to, $contentStr){
	$time = time();
	$textTpl = "<xml>
	<FromUserName><![CDATA[%s]]></FromUserName>
    <ToUserName><![CDATA[%s]]></ToUserName>
	<CreateTime>%s</CreateTime>
	<MsgType><![CDATA[text]]></MsgType>
	<Content><![CDATA[%s]]></Content>
	</xml>";
	
	insert("logs", array("content"=>"reply text:".sprintf($textTpl, $from, $to, $time,  $contentStr)));
	ob_start();
	echo sprintf($textTpl, $from, $to, $time,  $contentStr);
	ob_end_flush();
}

/**
 * 向微信回复图片内容
 *
 * @param from 公众号openid
 * @param to 接受者微信openid
 */
function answerPicMessage($from, $to, $contentStr){
    $time = time();
    $tpl = "<xml>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[image]]></MsgType>
    <Image>
    <MediaId><![CDATA[media_id]]></MediaId>
    </Image>
    </xml>";

    insert("logs", array("content"=>"reply pic:".sprintf($tpl, $from, $to, $time,  $contentStr)));
    ob_start();
    echo sprintf($tpl, $from, $to, $time,  $contentStr);
    ob_end_flush();
}

/**
 * 向微信回复图文内容
 *
 * @param from 公众号openid
 * @param to 接受者微信openid
 * @param array(array(title,desc,picurl,url))
 */
function answerTextPicMessage($from, $to, $articles){
    if( ! $articles)return;
    
    ob_start();
    echo "<xml>
    <FromUserName><![CDATA[$from]]></FromUserName>
    <ToUserName><![CDATA[$to]]></ToUserName>
    <CreateTime>", time(), "</CreateTime>
    <MsgType><![CDATA[news]]></MsgType>
    <ArticleCount>", count($articles), "</ArticleCount>
    <Articles>";
    
    foreach ($articles as $article){
        echo "<item>
        <Title><![CDATA[",$article['title'],"]]></Title> 
        <Description><![CDATA[",$article['desc'],"]]></Description>
        <PicUrl><![CDATA[",$article['picurl'],"]]></PicUrl>
        <Url><![CDATA[",$article['url'],"]]></Url>
        </item>";
    }
        
    echo "</Articles></xml> ";
    ob_end_flush();
}