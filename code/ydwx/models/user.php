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