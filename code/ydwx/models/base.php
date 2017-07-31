<?php
/**
 *
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/11/0e4b294685f817b95cbed85ba5e82b8f.html
 */
class YDWXAccessTokenResponse extends YDWXResponse{
    public $access_token;
    public $expires_in;
}

/**
 * 刷新授权公众号的令牌数据
 * @see https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1419318587&lang=zh_CN
 * @author leeboo
 *
 */
class YDWXAuthorizerTokenResponse extends YDWXResponse{
    public $authorizer_access_token;
    public $expires_in;
    public $authorizer_refresh_token;
}

/**
 *
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html#.E9.99.84.E5.BD.951-JS-SDK.E4.BD.BF.E7.94.A8.E6.9D.83.E9.99.90.E7.AD.BE.E5.90.8D.E7.AE.97.E6.B3.95
 */
class YDWXJsapiTicketResponse extends YDWXResponse{
    public $ticket;
    public $expires_in;
}

class YDWXPayBaseResponse extends YDWXResponse{
    /**
     * SUCCESS/FAIL
     * @var unknown
     */
    protected $return_code;
    /**
     * 返回信息，如非空，为错误原因
     * 签名失败
     * 参数格式校验错误
     * @var unknown
     */
    protected $return_msg;
    /**
     * SUCCESS/FAIL
     * SUCCESS退款申请接收成功，结果通过退款查询接口查询
     * FAIL
     * @var unknown
     */
    protected $result_code;
    /**
     * 详细参见第6节错误列表
     * @var unknown
     */
    protected $err_code;
    /**
     * 结果信息描述
     * @var unknown
     */
    protected $err_code_des;
    /**
     * 微信分配的公众账号ID
     * @var unknown
     */
    protected $appid;
    /**
     * 微信支付分配的商户号
     * @var unknown
     */
    protected $mch_id;
    /**
     * 随机字符串，不长于32位
     * @var unknown
     */
    protected $nonce_str;
    protected $sign;

    public function isSuccess(){
        return $this->isPrepaySuccess() &&  $this->isPrepayResultSuccess();
    }

    protected function isPrepaySuccess(){
        return !$this->return_code || strcasecmp($this->return_code, "success")==0;
    }

    protected function isPrepayResultSuccess(){
        return !$this->result_code || strcasecmp($this->result_code, "success")==0;
    }
    public function build($msg){
        $arr = simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA);
        foreach ((array)$arr as $name=>$value){
            $this->$name = $value;
        }
        if( ! $this->isPrepaySuccess() && $this->return_code){
            $this->errcode = -1;
            $this->errmsg  = $this->return_msg;
        }
        if( ! $this->isPrepayResultSuccess() && $this->result_code){
            $this->errcode = -1;
            $this->errmsg  .= $this->err_code_des;
        }
    }
}

/**
 * 通过code获取access token 响应
 * @author ydhlleeboo
 *
 */
class YDWXGetAccessTokenResponse extends YDWXResponse{
	public $access_token;
	public $expires_in;
	public $refresh_token;
	public $openid;
	public $scope;
	public $unionid;
}