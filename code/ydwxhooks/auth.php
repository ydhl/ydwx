<?php
use app\sp\Consumer_Model;
use app\sp\User_Model;
chdir(dirname(dirname(__FILE__)));
include_once './ydwx/libs/wx.php';
include_once "./init.php";


YDHook::add_hook(WXHooks::AUTH_CANCEL, function(){
    header("Location: /signin");die;
});

YDHook::add_hook(WXHooks::AUTH_FAIL, function($info){
    header("Location: /signin?error=".$info['errmsg']);die;
});

YDHook::add_hook(WXHooks::AUTH_SUCCESS, function(array $info){
    $wxuser = getWebUserInfo($info['access_token'], $info['openid']);
    if( !$wxuser ){
        header("Location: /signin?error=微信登录失败");die;
    }
    $consumer = end(Consumer_Model::find_by_attrs(array("openid"=>$info['openid'])));
    if ( ! $consumer){
        $consumer = new Consumer_Model();
        $consumer->set("openid",    $info['openid'])
            ->set("nickname",       $wxuser['nickname'])
            ->set("sex",            $wxuser['sex'])
            ->set("avatar",         $wxuser['headimgurl'])
            ->set("country",        $wxuser['country'])
            ->set("province",       $wxuser['province'])
            ->set("city",           $wxuser['city'])
            ->set("created_on",     date("Y-m-d H:i:s"))
            ->save();
        $_SESSION['consumerid'] = $consumer->get_key();
        header("Location: /wxsignin/success");die;
    }
    
    $user = end(User_Model::find_by_attrs(array("consumer_id"=>$consumer->get_key())));
    if( ! $user){
        $_SESSION['consumerid'] = $consumer->get_key();
        header("Location: /wxsignin/success");die;
    }
    $_SESSION['user']=$user;
    
    header("Location: /dashboard");die;
});