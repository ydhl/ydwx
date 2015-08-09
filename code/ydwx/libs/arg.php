<?php

class YDWXPayNotifyResult extends YDWXArg{
    public $return_code;
    public $return_msg;
    private $appid;
    private $mch_id;
    public $nonce_str;
    public $prepay_id;
    public $result_code;
    public $err_code_des;
    public $sign;
    
    public function valid(){
        $this->appid = WEIXIN_APP_ID;
        $this->mch_id = WEIXIN_MCH_ID;
    }
}
class YDWXPayUnifiedOrderArg extends YDWXArg{
    /**
     * 如Ipad mini  16G  白色	商品或支付单简要描述
     * @var unknown
     */
    public $body;
    /**
     * such as: Ipad mini  16G  白色	商品名称明细列表
     * @var unknown
     */
    public $detail;
    /**
     * 说明	附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
     * @var unknown
     */
    public $attach;
    /**
     * 商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
     * @var unknown
     */
    public $out_trade_no;
    /**
     * CNY	符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
     * @var unknown
     */
    public $fee_type = "CNY";
    /**
     * 订单总金额，只能为整数，单位为分，详见支付金额
     * @var unknown
     */
    public $total_fee;

    /**
     * 订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。其他详见时间规则
     * @var unknown
     */
    public $time_start;
    /**
     * 订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见时间规则;注意：最短失效时间间隔必须大于5分钟
     * @var unknown
     */
    public $time_expire;
    /**
     * WXG	商品标记，代金券或立减优惠功能的参数，说明详见代金券或立减优惠
     * @var unknown
     */
    public $goods_tag;
    
    /**
     * trade_type=NATIVE，此参数必传。此id为二维码中包含的商品ID，商户自行定义。
     * @var unknown
     */
    public $product_id;
    /**
     * no_credit	no_credit--指定不能使用信用卡支付
     * @var unknown
     */
    public $limit_pay;
    /**
     * trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识。下单前需要调用【网页授权获取用户信息】接口获取到用户的Openid。
     * 企业号请使用【企业号OAuth2.0接口】获取企业号内成员userid，再调用【企业号userid转openid接口】进行转换
     * @var unknown
     */
    public $openid;

    /**
     * 取值如下：JSAPI，NATIVE，APP，WAP,详细说明见参数规定
     * @var unknown
     */
    public $trade_type;
    /**
     * 终端设备号(门店号或收银设备ID)，注意：PC网页或公众号内支付请传"WEB"
     * @var unknown
     */
    private $device_info ="WEB";
    /**
     * 随机字符串，不长于32位。推荐随机数生成算法
     * @var unknown
     */
    private $nonce_str;
    private $notify_url;
    /**
     * 微信分配的公众账号ID（企业号corpid即为此appId）
     * @var unknown
     */
    private $appid;
    /**
     * 微信支付分配的商户号
     */
    private $mch_id;
    /**
     * APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。
     * @var unknown
     */
    private $spbill_create_ip;

    
    public function __construct(){
        $this->appid        = WEIXIN_APP_ID;
        $this->mch_id       = WEIXIN_MCH_ID;
        $this->nonce_str    = uniqid();
        $this->notify_url  = YDWX_SITE_URL."pay-notify.php";
    }
    public function valid(){
        if($this->trade_type == "JSAPI"){
            $this->spbill_create_ip = $_SERVER['REMOTE_ADDR'];
        }else if($this->trade_type == "NATIVE"){
            $this->spbill_create_ip = $_SERVER['SERVER_ADDR'];
        }
        
        if ($this->trade_type=="JSAPI" && ! $this->openid){
            throw new YDWXException("JSAPI支付时openid不能为空");
        }else if ($this->trade_type=="NATIVE" && ! $this->product_id){
            throw new YDWXException("NATIVE支付时product_id不能为空");
        }
    }
    
}