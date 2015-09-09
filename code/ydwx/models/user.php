<?php
/**
 * 企业认证用户信息
 * @author leeboo
 * @see http://qydev.weixin.qq.com/wiki/index.php?title=OAuth%E9%AA%8C%E8%AF%81%E6%8E%A5%E5%8F%A3
 */
class YDWXOAuthCropUser extends YDWXResponse{
    /**
     * 该用户在企业号后台的账号
     * @var unknown
     */
    public $UserId;
    /**
     * 非企业成员时返回openid
     * @var unknown
     */
    public $OpenId;
    public $DeviceId;
    /**
     * OAuth授权流程中自定义参数
     * @var unknown
     */
    public $state;
}

/**
 * web 认证用户信息和微信内非静默授权获取到的用户信息
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html#.E7.AC.AC.E5.9B.9B.E6.AD.A5.EF.BC.9A.E6.8B.89.E5.8F.96.E7.94.A8.E6.88.B7.E4.BF.A1.E6.81.AF.28.E9.9C.80scope.E4.B8.BA_snsapi_userinfo.29
 * @see https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1419316518&token=&lang=zh_CN
 */
class YDWXOAuthUser extends YDWXResponse{
    public $openid;
    public $nickname;
    public $sex;
    public $province;
    public $city;
    public $country;
    public $headimgurl;
    /**
     *
     * @var array
     */
    public $privilege;
    public $unionid;
    /**
     * OAuth授权流程中自定义参数
     * @var unknown
     */
    public $state;

}

/**
 * 公众号获取自己粉丝用户信息, 无privilege
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/14/bb5031008f1494a59c6f71fa0f319c66.html
 */
class YDWXSubscribeUser extends YDWXOAuthUser{
    public $subscribe;
    public $language;
    /**
     * 用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间
     * @var unknown
     */
    public $subscribe_time;
    /**
     * 公众号运营者对粉丝的备注，公众号运营者可在微信公众平台用户管理界面对粉丝添加备注
     * @var unknown
     */
    public $remark;
    /**
     * 用户所在的分组ID
     * @var unknown
     */
    public $groupid;

}

class YDWXAuthFailResponse extends YDWXResponse{
    public function isSuccess(){
        return false;
    }

    public static function errMsg($msg, $errcode=-1){
        $fail = new YDWXAuthFailResponse();
        $fail->errmsg  = $msg;
        $fail->errcode = $errcode;
        return $fail;
    }
}


/**
 * 公众号授权给第三方平台的信息
 * @author leeboo
 *
 */
class YDWXAgentAuthInfo extends YDWXResponse{
    /**
     * 授权方appid
     */
    public $authorizer_appid;
    /**
     * 授权方令牌（在授权的公众号具备API权限时，才有此返回值）
     */
    public $authorizer_access_token;
    /**
     * 有效期（在授权的公众号具备API权限时，才有此返回值）
     */
    public $expires_in;
    /**
     * 刷新令牌（在授权的公众号具备API权限时，才有此返回值），刷新令牌主要用于公众号第三方平台获取和刷新已授权用户的access_token，只会在授权时刻提供，请妥善保存。 一旦丢失，只能让用户重新授权，才能再次拿到新的刷新令牌
     */
    public $authorizer_refresh_token;
    /**
     * 公众号授权给开发者的权限集列表（请注意，当出现用户已经将消息与菜单权限集授权给了某个第三方，
     * 再授权给另一个第三方时，由于该权限集是互斥的，后一个第三方的授权将去除此权限集，
     * 开发者可以在返回的func_info信息中验证这一点，避免信息遗漏），见YDWX_FUNC_XX常量；
     * 请注意，该字段的返回不会考虑公众号是否具备该权限集的权限（因为可能部分具备），请根据公众号的帐号类型和认证情况，来判断公众号的接口权限。
     * @var array
     */
    public $func_info;

    public function build($msg){
        parent::build($msg);
        $this->authorizer_appid             = $this->rawData['authorization_info']['authorizer_appid'];
        $this->authorizer_access_token      = $this->rawData['authorization_info']['authorizer_access_token'];
        $this->authorizer_refresh_token     = $this->rawData['authorization_info']['authorizer_refresh_token'];
        $this->expires_in                   = $this->rawData['authorization_info']['expires_in'];

        foreach ($this->rawData['authorization_info']['func_info'] as $func){
            $this->func_info[] = $func['funcscope_category']['id'];
        }
    }
}

/**
 * 授权给第三方平台的公众号信息
 * @author leeboo
 *
 */
class YDWXAgentAuthUser extends YDWXResponse{
    /**
     * 授权方昵称
     */
    public $nick_name;
    /**
     * 授权方头像
     */
    public $head_img;
    /**
     * 授权方公众号类型，0代表订阅号，1代表由历史老帐号升级后的订阅号，2代表服务号
     * 见YDWX_WEIXIN_ACCOUNT_TYPE_XX常量
     */
    public $service_type_info;
    /**
     * 授权方认证类型，-1代表未认证，0代表微信认证，
     * 1代表新浪微博认证，2代表腾讯微博认证，
     * 3代表已资质认证通过但还未通过名称认证，
     * 4代表已资质认证通过、还未通过名称认证，但通过了新浪微博认证，
     * 5代表已资质认证通过、还未通过名称认证，但通过了腾讯微博认证
     * 见 YDWX_WEIXIN_VERIFY_TYPE_XX常量
     */
    public $verify_type_info;
    /**
     * 授权方公众号的原始ID
     */
    public $user_name;
    /**
     * 授权方公众号所设置的微信号，可能为空
     */
    public $alias;
    /**
     * 二维码图片的URL，开发者最好自行也进行保存
     */
    public $qrcode_url;
    /**
     * 授权方appid
     */
    public $appid;
    public $func_info;
    
    public function build($msg){
        parent::build($msg);
        $this->nick_name             = $this->rawData['authorizer_info']['nick_name'];
        $this->head_img              = $this->rawData['authorizer_info']['head_img'];
        $this->service_type_info     = $this->rawData['authorizer_info']['service_type_info'];
        $this->verify_type_info      = $this->rawData['authorizer_info']['verify_type_info'];
        $this->user_name             = $this->rawData['authorizer_info']['user_name'];
        $this->alias                 = $this->rawData['authorizer_info']['alias'];
        
        $this->appid                 = $this->rawData['authorization_info']['appid'];
        foreach ($this->rawData['authorization_info']['func_info'] as $func){
            foreach ($this->rawData['authorization_info']['func_info'] as $func){
                $this->func_info[] = $func['funcscope_category']['id'];
            }
        }
    }
}