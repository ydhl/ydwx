<?php
/**
 * 模板消息发送后返回结构
 * @author leeboo
 *
 */
class YDWXTemplateResult extends YDWXMsg{
    public $msgid;
}
/**
 * 群发消息发送后返回结构
 * @author leeboo
 *
 */
class YDWXMassResult extends YDWXMsg{
    /**
     * 消息发送任务的ID
     * @var unknown
     */
    public $msg_id;
    /**
     * 消息的数据ID，，该字段只有在群发图文消息时，才会出现。可以用于在图文分析数据接口中，
     * 获取到对应的图文消息的数据，是图文分析数据接口中的msgid字段中的前半部分，
     * 详见图文分析数据接口中的msgid字段的介绍。
     * @var unknown
     */
    public $msg_data_id;
}
/**
 * 企业认证用户信息
 * @author leeboo
 *
 */
class YDWXOAuthCropUser extends YDWXMsg{
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
 * 公众号认证用户信息
 * @author leeboo
 *
 */
class YDWXOAuthUser extends YDWXMsg{
    public $subscribe;
    public $openid;
    public $nickname;
    public $sex;
    public $language;
    public $city;
    public $province;
    public $country;
    public $headimgurl;
    public $subscribe_time;
    public $unionid;
    public $remark;
    public $groupid;
    /**
     * OAuth授权流程中自定义参数
     * @var unknown
     */
    public $state;
    
}
/**
 * web 认证用户信息
 * @author leeboo
 *
 */
class YDWXOAuthSnsUser extends YDWXMsg{
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

class YDWXAccessTokenRefresh extends YDWXMsg{
   public $access_token;
   public $expires_in;
} 

class YDWXJsapiTicketRefresh extends YDWXMsg{
    public $ticket;
    public $expires_in;
}
class YDWXPayUnifiedOrderMsg extends YDWXMsg{
    public $return_code;
    public $return_msg;
    public $result_code;
    public $err_code;
    public $err_code_des;
    
    public $appid;
    public $mch_id;
    public $nonce_str;
    public $sign;
    public $result_code;
    public $prepay_id;
    public $trade_type;
    public $code_url;
    public $device_info;
     
    public function isSuccess(){
        return $this->isPrepaySuccess() &&  $this->isPrepayResultSuccess();
    }
    public function build($msg){
        foreach (simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA) as $name=>$value){
            $this->$name = $value;
        }
        if( ! $this->isPrepaySuccess()){
            $this->errcode = -1;
            $this->errmsg  = $this->return_msg;
        }
        if( ! $this->isPrepayResultSuccess()){
            $this->errcode = -1;
            $this->errmsg  = $this->err_code_des;
        }
    }

    private function isPrepaySuccess(){
        return strcasecmp($this->return_code, "success")==0;
    }
    
    private function isPrepayResultSuccess(){
        return strcasecmp($this->result_code, "success")==0;
    }
}

class YDWXAuthFail extends YDWXMsg{
    public function isSuccess(){
        return false;
    }
    
    public static function errMsg($msg, $errcode=-1){
        $fail = new YDWXAuthFail();
        $fail->errmsg  = $msg;
        $fail->errcode = $errcode;
        return $fail;
    }
}
class YDWXQrcodeScanNotifyMsg extends YDWXMsg{
    public $appid;
    public $openid;
    public $mch_id;
    public $is_subscribe;
    public $nonce_str;
    /**
     * 商户定义的商品id 或者订单号, 在扫码后在支付时通知用到
     * @var unknown
     */
    public $product_id;
    public $sign;
    
}
class YDWXPayNotifyMsg extends YDWXQrcodeScanNotifyMsg{
    public $return_code;
    public $return_msg;
    public $result_code;
    public $err_code;
    public $err_code_des;

    public $device_info;

    public $trade_type;
    /**
     * CMC	银行类型，采用字符串类型的银行标识，银行类型见银行列表
     * @var unknown
     */
    public $bank_type;
    /**
     * 订单总金额，单位为分
     * @var unknown
     */
    public $total_fee;
    /**
     * CNY	货币类型，符合ISO4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
     * @var unknown
     */
    public $fee_type;
    /**
     * 现金支付金额订单现金支付金额，详见支付金额
     * @var unknown
     */
    public $cash_fee;
    /**
     * CNY	货币类型，符合ISO4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
     * @var unknown
     */
    public $cash_fee_type;
    /**
     * 代金券或立减优惠金额<=订单总金额，订单总金额-代金券或立减优惠金额=现金支付金额，详见支付金额
     * @var unknown
     */
    public $coupon_fee;
    /**
     * 代金券或立减优惠使用数量
     * @var unknown
     */
    public $coupon_count;

    /**
     * 微信支付订单号
     * @var unknown
     */
    public $transaction_id;
    /**
     * 商户系统的订单号，与请求一致。
     * @var unknown
     */
    public $out_trade_no;
    /**
     * 商家数据包，原样返回
     * @var unknown
     */
    public $attach;
    /**
     * 支付完成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010
     */
    public $time_end;
     
    public function isSuccess(){
        return $this->isPrepaySuccess() &&  $this->isPrepayResultSuccess();
    }
    public function build($msg){
        foreach (simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA) as $name=>$value){
            $this->$name = $value;
        }
        if( ! $this->isPrepaySuccess()){
            $this->errcode = -1;
            $this->errmsg  = $this->return_msg;
        }
        if( ! $this->isPrepayResultSuccess()){
            $this->errcode = -1;
            $this->errmsg  = $this->err_code_des;
        }
    }

    private function isPrepaySuccess(){
        return strcasecmp($this->return_code, "success")==0;
    }

    private function isPrepayResultSuccess(){
        return strcasecmp($this->result_code, "success")==0;
    }
}