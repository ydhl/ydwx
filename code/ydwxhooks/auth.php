<?php
use yangzie\YZE_Hook;
use app\sp\Consumer_Model;
use app\common\User_Model;
use yangzie\YZE_SQL;
use yangzie\YZE_DBAImpl;
use yangzie\YZE_Redirect;
use app\sp\Service_Provider_Model;
use app\card\Merchants_Model;
use yangzie\YZE_Request;
use app\admin\Admin_Model;
use yangzie\YZE_FatalException;
use app\robot\Robot_Model;
$oldcwd = getcwd();
chdir ( dirname ( __FILE__ ) . '/../' );
require_once 'init.php';
chdir ( $oldcwd );

YDWXHook::add_hook(YDWXHook::AUTH_CANCEL, function(){
    //用户取消登录了做什么，如
    header("Location: /signin");die;
});

YDWXHook::add_hook(YDWXHook::AUTH_FAIL, function(YDWXAuthFailResponse $info){
    //用户登录是把了做什么，如
    header("Location: /signin?error=".urlencode($info->errmsg));die;
});

YDWXHook::add_hook(YDWXHook::AUTH_INAPP_SUCCESS, function(YDWXOAuthUser $userinfo){
    //微信app内登录成功做什么，如判断该微信用户是否在系统中存在，不存在建立用户数据，存在标记为登录状态，并
    //导航到登录后看到的页面
    $consumer = Consumer_Model::get_by_openid($userinfo->openid, $userinfo->appid);
    if ( ! $consumer){
        $consumer = register_consumer($userinfo->openid,  $userinfo->appid, $userinfo);
    } else {
    	$sp_id =null;
    	if($userinfo->appid){
    		$sp = reset(Service_Provider_Model::find_by_attrs(array("appid"=>$userinfo->appid)));
    		$sp_id = $sp ? $sp->get_key() : null;
    	}
    	
    	$consumer->set("nickname",       $userinfo->nickname)
    	->set("sex",            $userinfo->sex)
    	->set("avatar",         $userinfo->headimgurl)
    	->set("country",        $userinfo->country)
    	->set("province",       $userinfo->province)
    	->set("sp_id",          $sp_id)
    	->set("city",           $userinfo->city)
    	->save();
    }
    
    YZE_Hook::do_hook( YZE_HOOK_SET_LOGIN_USER, $consumer );
    header("Location: ".$userinfo->state);die;
});

YDWXHook::add_hook(YDWXHook::AUTH_WEB_SUCCESS, function(YDWXOAuthUser $wxuser){
    if( ! $wxuser ){
        header("Location: /signin?error=".urlencode("微信登录失败"));die;
    }
    
    $openid = $wxuser->openid;
    $find_consumer = Consumer_Model::get_by_openid($openid, $wxuser->appid);

    $_SESSION['wxuser'] = $wxuser; //$wxuser存到session中，sq微信注册时用到
    // 这是用户通过微信登录网站，成功取得微信用户的信息
    // 首先判断openid在系统中存在不
    //    存在则判断用户是什么身份，标记相关的登录回话然后重定向到自己的后台
    //    不存在，则需要判断用户是否登录了
    //      如果用户登录了，则建立微信登录身份与用户登录身份的绑定关系
    //      如果没有登录，则判断$info['state'],
    //          如果是admin，则表示平台管理员微信登录，提示用户进行admin登录界面
    //          如果是sp，则提示用户进入sp注册界面，
    
    $admin = YZE_Hook::do_hook( YZE_HOOK_GET_LOGIN_USER );
    //用户没有登录帐号（即：微信登录）
    if( empty($admin) ){
    	
    	if( empty($find_consumer) && $wxuser->state == "admin" ){  //通过admin登录的用户,没在consumer表中没找到，返回登录错误
    		header("Location: /admin/signin?error=".urlencode("微信登录失败"));die;
    	}
    	if( empty($find_consumer) && $wxuser->state == "sp" ){  //通过sp登录的用户,没在consumer表中找没到，跳转到sp微信绑定、注册界面
    		header("Location: /signin/wxsignup");die;
    	}
    	if( ! empty($find_consumer) ){  //在consumer表中找到
    		$sql = new YZE_SQL();
    		$sql->from('app\admin\Admin_Model', 'a')
    			->left_join('app\common\User_Model', 'u', 'a.user_id=u.id')
    			->select('a')
    			->select('u', array('id', 'type'))
    			->where('u', 'id', YZE_SQL::EQ, $find_consumer->user_id);
    		$find_user = YZE_DBAImpl::getDBA()->select($sql);
    		if( @$find_user[0]['u']->type != $wxuser->state ){ //sp用户通过admin地址登录的情况( 或admin用户通过sp地址登录 )，返回错误
    			header("Location: /signin?error=".urlencode("微信登录失败,请确定登录地址是否正确"));die;
    		}
    		//登录成功，保存session
			$admin = $find_user[0]['a'];
    		YZE_Hook::do_hook( YZE_HOOK_SET_LOGIN_USER, $admin );
    		if( $find_user[0]['u']->type == "admin" ){
    			header("Location: /admin");die;
    		}else {
    			header("Location: /sp");die;
    		}
    	}
    }
    //用户已登录帐号（即：微信绑定）
    else {
    	Service_Provider_Model::user_wxbind($wxuser, $wxuser->state);
    }
});

//公众号授权成功
YDWXHook::add_hook(YDWXHook::AUTH_AGENT_SUCCESS, function(array $info){
    /**
     * @var YDWXAgentAuthInfo
     */
    $YDWXAgentAuthInfo = $info[0];
   /**
    * @var YDWXAgentAuthUser
    */
    $YDWXAgentAuthUser  = $info[1];
    
    //这是sp授权信息，把这两个信息保存起来
    $sp = Service_Provider_Model::find_by_id($YDWXAgentAuthInfo->query['sp_id']);
    $verify_appid = $YDWXAgentAuthInfo->query['aid'];
    if($verify_appid && $verify_appid != $YDWXAgentAuthInfo->authorizer_appid){//子商户授权情况
        $merchant = reset(Merchants_Model::find_by_attrs(array("appid"=>$verify_appid)));
        die("公众号不匹配，请使用".($merchant ? $merchant->name : $verify_appid)."的公众号授权");
    }
    $back = $YDWXAgentAuthInfo->query['back'];
    if( ! $back) $back = "/wxbind";
    
    $app_id = $YDWXAgentAuthInfo->authorizer_appid;
    //清空该sp原来的微信绑定的相关信息
    $find_sp = Service_Provider_Model::find_by_attrs(array("appid" => $app_id), true);
    
    if( ! $sp){
        #如果sp不存在，则看成新公众号授权登录
        if(! $find_sp){
            $find_sp = new Service_Provider_Model();
            $find_sp->set("sp_name", $YDWXAgentAuthUser->nick_name)
            ->set("type",           Service_Provider_Model::COMMON)
            ->set("uuid",           get_uuid())
            ->set("created_on",     date("Y-m-d H:is:s"))
            ->set("appid",          $YDWXAgentAuthInfo->authorizer_appid)
            ->set("access_token",   $YDWXAgentAuthInfo->authorizer_access_token)
            ->set("access_token_expires",   $YDWXAgentAuthInfo->expires_in + time())
            ->set("public_account_name",    $YDWXAgentAuthUser->nick_name)
            ->set("account_avatar",         $YDWXAgentAuthUser->head_img)
            ->set("account_login_name",      $YDWXAgentAuthUser->alias)
            ->set("qrcode",                 $YDWXAgentAuthUser->qrcode_url)
            ->set("settings",               json_encode($setting))
            ->save();
        }else {
            $user = User_Model::get_bind_account($find_sp->id);
            $user = $user ? $user->get_admin() : null;
        }
        if( ! $user){
            $user = User_Model::register_sp_user($find_sp->id, Admin_Model::SUPER, "", "",$YDWXAgentAuthUser->alias);//作为公众号授权直接登录用户
            $user->set("bind_account", 1)->save();
        }
        YZE_Hook::do_hook(YZE_HOOK_SET_LOGIN_USER, $user);
        //生成刷新token的机器人
       	$sp_id = $find_sp->id;
       	YZE_Hook::do_hook(YDWXP_ADD_REFRESH_TOKEN_ROBOT, array("sp_id" => $sp_id));
        header("Location: ".\yangzie\yze_merge_query_string($back));die;
    }
    
    if( $find_sp && $find_sp->id != $sp->id ){
    	throw new YZE_FatalException("该公众号已被绑定");
    }
    
    $sp->set("appid", $YDWXAgentAuthInfo->authorizer_appid)
        ->set("access_token", $YDWXAgentAuthInfo->authorizer_access_token)
        ->set("access_token_expires", $YDWXAgentAuthInfo->expires_in + time())
        ->set("public_account_name", $YDWXAgentAuthUser->nick_name)
        ->set("account_avatar", $YDWXAgentAuthUser->head_img)
        ->set("account_login_name", $YDWXAgentAuthUser->alias)
        ->set("qrcode", $YDWXAgentAuthUser->qrcode_url);
    
    $sp->setSetting("wx_refresh_token",  $YDWXAgentAuthInfo->authorizer_refresh_token)
    ->setSetting("wx_func_info",         $YDWXAgentAuthInfo->func_info)
    ->setSetting("wx_service_type_info", $YDWXAgentAuthUser->service_type_info)
    ->setSetting("wx_verify_type_info",  $YDWXAgentAuthUser->verify_type_info)
    ->setSetting("wx_user_name",         $YDWXAgentAuthUser->user_name)
    ->save();
    //再次绑定时，生成刷新token的机器人
    $sp_id = $sp->id;
  	YZE_Hook::do_hook(YDWXP_ADD_REFRESH_TOKEN_ROBOT, array("sp_id" => $sp_id));
    
    header("Location: {$back}");die;
});



YDWXHook::add_hook(YDWXHook::AUTH_CROP_SUCCESS, function(YDWXOAuthCropUser $info){
    //微信企业号app登录成功做什么，如判断该微信用户是否在系统中存在，不存在建立用户数据，存在标记为登录状态，并
    //导航到登录后看到的页面
});