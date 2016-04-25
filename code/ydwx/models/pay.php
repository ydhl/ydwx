<?php

/**
 * 微信支付统一下单结果
 *
 * @author leeboo
 * @see https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_1
 */
class YDWXPayUnifiedOrderResponse extends YDWXPayBaseResponse{
    public $prepay_id;
    public $trade_type;
    public $code_url;
    public $device_info;
     
}


/**
 * 微信扫码支付中间环节的通知
 *  - 这时表示用户即将进行付款，需要商户先通过ydwx_pay_unifiedorder接口生成预支付订单；
 *  这时的通知中有product_id
 *
 * @author leeboo
 * @see https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=6_4
 */
class YDWXPayingNotifyResponse extends YDWXResponse{
    const IS_SUBSCRIBE_Y = "Y";
    const IS_SUBSCRIBE_N = "N";
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

     
    public function isSuccess(){
        return true;
    }
    public function build($msg){
        $arr = simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA);
        foreach ((array)$arr as $name=>$value){
            $this->$name = $value;
        }
    }
}


/**
 * 微信支付成功后的通知：
 *  - js调起支付，成功后的通知
 *  - 二维码一扫即付款成功后的通知
 *
 * @author leeboo
 * @see https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_7
 */
class YDWXPaiedNotifyResponse extends YDWXPayBaseResponse{
    /**
     * 代金券或立减优惠ID,下标从0开始编号
     * @var unknown
     */
    public $coupon_id = array();
    /**
     * 单个代金券或立减优惠支付金额,下标从0开始编号
     * @var unknown
     */
    public $coupon_fees= array();

    /**
     * 微信支付分配的终端设备号
     * @var unknown
     */
    public $device_info;

    /**
     * JSAPI、NATIVE、APP
     * @var unknown
     */
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
        $arr = simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA);
        foreach ((array)$arr as $name=>$value){
            if(preg_match("/^coupon_id_(?P<id>\d+)$/", $name, $match)){
                $this->coupon_id[] = $match['id'];
            }else if(preg_match("/^coupon_fee_(?P<fee>\d+)$/", $name, $match)){
                $this->coupon_fee[] = $match['fee'];
            }else{
                $this->$name = $value;
            }
        }
        if( ! $this->isPrepaySuccess() && $this->return_code){
            $this->errcode = -1;
            $this->errmsg  = $this->return_msg;
        }
        if( ! $this->isPrepayResultSuccess() && $this->result_code){
            $this->errcode = -1;
            $this->errmsg  = $this->err_code_des;
        }
    }
}

/**
 * 该接口提供所有微信支付订单的查询，商户可以通过该接口主动查询订单状态，完成下一步的业务逻辑。
 * 需要调用查询接口的情况：
 * ◆ 当商户后台、网络、服务器等出现异常，商户系统最终未接收到支付通知；
 * ◆ 调用支付接口后，返回系统错误或未知交易状态情况；
 * ◆ 调用被扫支付API，返回USERPAYING的状态；
 * ◆ 调用关单或撤销接口API之前，需确认支付状态；
 * 接口链接
 * https://api.mch.weixin.qq.com/pay/orderquery
 * @author leeboo
 *
 */
class YDWXOrderQueryResponse extends YDWXPaiedNotifyResponse{
    CONST TRADE_STATE_SUCCESS = "SUCCESS";
    CONST TRADE_STATE_REFUND = "REFUND";
    CONST TRADE_STATE_NOTPAY = "NOTPAY";
    CONST TRADE_STATE_CLOSED = "CLOSED";
    CONST TRADE_STATE_REVOKED = "REVOKED";
    CONST TRADE_STATE_USERPAYING = "USERPAYING";
    CONST TRADE_STATE_PAYERROR = "PAYERROR";
    /**
     * SUCCESS—支付成功
     * REFUND—转入退款
     * NOTPAY—未支付
     * CLOSED—已关闭
     * REVOKED—已撤销（刷卡支付）
     * USERPAYING--用户支付中
     * PAYERROR--支付失败(其他原因，如银行返回失败)
     * @var unknown
     */
    public $trade_state;
    /**
     * 对当前查询订单状态的描述和下一步操作的指引
     * @var unknown
     */
    public $trade_state_desc;
}

/**
 * 申请退款响应数据
 * @author leeboo
 * @see https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_4
 */
class YDWXPayRefundResponse extends YDWXPayBaseResponse
{
    const REFUND_CHANNEL_ORIGINAL = ORIGINAL;
    const REFUND_CHANNEL_BALANCE = BALANCE;

    /**
     * 微信支付分配的终端设备号，与下单一致
     * @var String
     */
    public $device_info = null;

    /**
     * 微信订单号
     * @var String
     */
    public $transaction_id = null;

    /**
     * 商户系统内部的订单号
     * @var String
     */
    public $out_trade_no = null;

    /**
     * 商户退款单号
     * @var String
     */
    public $out_refund_no = null;

    /**
     * 微信退款单号
     * @var String
     */
    public $refund_id = null;

    /**
     * ORIGINAL—原路退款 BALANCE—退回到余额
     * @var String
     */
    public $refund_channel = null;

    /**
     * 退款总金额,单位为分,可以做部分退款
     * @var integer
     */
    public $refund_fee = null;

    /**
     * 订单总金额，单位为分，只能为整数，详见支付金额
     * @var Integer
     */
    public $total_fee = null;

    /**
     * 订单金额货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
     * @var String
     */
    public $fee_type = null;

    /**
     * 现金支付金额，单位为分，只能为整数，详见支付金额
     * @var integer
     */
    public $cash_fee = null;

    /**
     * 现金退款金额，单位为分，只能为整数，详见支付金额
     * @var integer
     */
    public $cash_refund_fee = null;

    /**
     * 代金券或立减优惠退款金额=订单金额-现金退款金额，注意：立减优惠金额不会退回
     * @var Integer
     */
    public $coupon_refund_fee = null;

    /**
     * 代金券或立减优惠使用数量
     * @var Integer
     */
    public $coupon_refund_count = null;

    /**
     * 代金券或立减优惠ID
     * @var String
     */
    public $coupon_refund_id = null;
}

/**
 * 查询退款结果
 * @author leeboo
 * @see https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_5
 */
class YDWXPayRefundQueryResponse extends YDWXPayBaseResponse
{


    /**
     * 终端设备号
     * @var String
     */
    public $device_info = null;

    /**
     * 微信订单号
     * @var String
     */
    public $transaction_id = null;

    /**
     * 商户系统内部的订单号
     * @var String
     */
    public $out_trade_no = null;

    /**
     * 订单总金额，单位为分，只能为整数，详见支付金额
     * @var integer
     */
    public $total_fee = null;

    /**
     * 订单金额货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
     * @var String
     */
    public $fee_type = null;

    /**
     * 现金支付金额，单位为分，只能为整数，详见支付金额
     * @var Integer
     */
    public $cash_fee = null;

    /**
     * 退款记录数
     * @var Integer
     */
    public $refund_count = null;

    /**
     * 商户退款单号
     * @var array
     */
    public $out_refund = array();

    /**
     * 微信退款单号
     * @var array
    */
    public $refund_id = array();

    /**
     * ORIGINAL—原路退款 BALANCE—退回到余额
     * @var array
    */
    public $refund_channel = array();

    /**
     * 退款总金额,单位为分,可以做部分退款
     * @var array
    */
    public $refund_fee = array();

    /**
     * 代金券或立减优惠退款金额<=退款金额，退款金额-代金券或立减优惠退款金额为现金，说明详见代金券或立减优惠
     * @var array
    */
    public $coupon_refund_fee = array();

    /**
     * 代金券或立减优惠使用数量 ,下标,从0开始编号
     * @var array
    */
    public $coupon_refund_count = array();

    /**
     * 批次ID二位数组，下标，从0开始编号
     * @var array
    */
    public $coupon_refund_batch_id = array();

    /**
     * 代金券或立减优惠ID,二位数组，下标，从0开始编号
     * @var array
    */
    public $conpon_refund_id = array();

    /**
     * 单个代金券或立减优惠支付金额,二位数组，下标，从0开始编号
     * @var array
    */
    public $conpon_refund_fee = array();

    /**
     * 退款状态：SUCCESS—退款成功
     * FAIL—退款失败
     * PROCESSING—退款处理中
     * NOTSURE—未确定，需要商户原退款单号重新发起
     * CHANGE—转入代发，退款到银行发现用户的卡作废或者冻结了，导致原路退款银行卡失败，
     * 资金回流到商户的现金帐号，需要商户人工干预，通过线下或者财付通转账的方式进行退款。
     * @var array
    */
    public $refund_status = array();

    const REFUND_STATUS_SUCCESS = "SUCCESS";
    const REFUND_STATUS_FAIL = "FAIL";
    const REFUND_STATUS_PROCESSING = "PROCESSING";
    const REFUND_STATUS_NOTSURE = "NOTSURE";
    const REFUND_STATUS_CHANGE = "CHANGE";
}

class YDWXPayDownloadbillResponse extends YDWXResponse{
    private $return_code;
    private $return_msg;
    /**
     * 总交易单数
     * @var unknown
     */
    public $total_order_count;
    public $bill_type;
    /**
     * 总交易额
     * @var unknown
     */
    public $total_trade_fee;
    /**
     * 总退款金额
     * @var unknown
     */
    public $total_refund_fee;
    /**
     * 总代金券或立减优惠退款金额
     * @var unknown
     */
    public $total_coupon_refund_fee;
    /**
     * 手续费总金额
     * @var unknown
     */
    public $total_poundage;
    /**
     * 订单列表 YDWXPayBillInfo
     * @var YDWXPayBillInfo
     */
    public $list = array();

    /**
     * 出错时返回xml，成功时返回文本格式的数据
     *
     * @param string $msg
    */
    public function build($msg){
        $arr = simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA);
        if($arr){//has error
            foreach ((array)$arr as $name=>$value){
                $this->$name = $value;
            }

            $this->errcode = -1;
            $this->errmsg  = $this->return_msg;
            return;
        }

        $this->format($msg);
    }
    /**
     * 当日所有订单
     * 交易时间,公众账号ID,商户号,子商户号,设备号,微信订单号,商户订单号,用户标识,交易类型,交易状态,付款银行,货币种类,总金额,代金券或立减优惠金额,微信退款单号,商户退款单号,退款金额,代金券或立减优惠退款金额，退款类型，退款状态,商品名称,商户数据包,手续费,费率
     *
     * 当日成功支付的订单
     * 交易时间,公众账号ID,商户号,子商户号,设备号,微信订单号,商户订单号,用户标识,交易类型,交易状态,付款银行,货币种类,总金额,代金券或立减优惠金额,商品名称,商户数据包,手续费,费率
     *
     * 当日退款的订单
     * 交易时间,公众账号ID,商户号,子商户号,设备号,微信订单号,商户订单号,用户标识,交易类型,交易状态,付款银行,货币种类,总金额,代金券或立减优惠金额,退款申请时间,退款成功时间,微信退款单号,商户退款单号,退款金额,代金券或立减优惠退款金额,退款类型,退款状态,商品名称,商户数据包,手续费,费率
     *
     * 倒数第二行为订单统计标题，最后一行为统计数据
     * 总交易单数，总交易额，总退款金额，总代金券或立减优惠退款金额，手续费总金额
     * @param unknown $msg
     */
    public function format($msg){
        //TODO 解析文本
    }
}

class YDWXPayBillInfo
{
    /**
     * 交易时间
     * @var String
     */
    public $trade_date = null;

    /**
     * 公众账号ID
     * @var String
     */
    public $app_id = null;

    /**
     * 商户号
     * @var String
     */
    public $mch_id = null;

    /**
     * 子商户号
     * @var String
     */
    public $sub_mch_id = null;

    /**
     * 设备号
     * @var String
     */
    public $device_info = null;

    /**
     * 微信订单号
     * @var String
     */
    public $transaction_id = null;

    /**
     * 商户订单号
     * @var String
     */
    public $out_trade_no = null;

    /**
     * 用户标识
     * @var Integer
     */
    public $user_type = null;

    /**
     * 交易类型
     * @var String
     */
    public $trade_type = null;

    /**
     * 交易状态
     * @var String
     */
    public $trade_state = null;

    /**
     * 付款银行
     * @var String
     */
    public $bank_type = null;

    /**
     * 货币种类
     * @var String
     */
    public $fee_type = null;

    /**
     * 总金额
     * @var Integer
     */
    public $total_fee = null;

    /**
     * 代金券或立减优惠金额
     * @var String
     */
    public $coupon_fee = null;

    /**
     * 微信退款单号
     * @var String
     */
    public $refund_id = null;

    /**
     * 商户退款单号
     * @var String
     */
    public $out_refund_no = null;

    /**
     * 退款金额
     * @var integer
     */
    public $refund_fee = null;

    /**
     * 代金券或立减优惠退款金额
     * @var Integer
     */
    public $coupon_refund_fee = null;

    /**
     * 退款类型
     * @var String
     */
    public $refund_channel = null;

    /**
     * 退款状态
     * @var String
     */
    public $refund_status = null;

    /**
     * 商户数据包
     * @var String
     */
    public $attach = null;

    /**
     * 商品名称
     * @var String
     */
    public $product_name = null;

    /**
     * 手续费
     * @var String
     */
    public $fee = null;

    /**
     * 费率
     * @var String
     */
    public $fee_tax = null;

    /**
     * 退款申请时间
     * @var String
     */
    public $refund_date = null;

    /**
     * 退款成功时间
     * @var String
     */
    public $refund_finish_date = null;
}
class YDWXPayShorturlResponse extends  YDWXPayBaseResponse{
    /**
     * 转换后的URL
     * @var unknown
     */
    public $short_url;
}
////////////////////////////// request ////////////////////////////////////////

/**
 * appid 和mch id默认读取的是config中的配置，如果作为第三方平台带公众号处理的时候可自行设置
 * @author leeboo
 *
 */
class YDWXPayBaseRequest extends YDWXRequest{
    protected $nonce_str;
    public $appid;
    public $mch_id;
    public $mch_key;

    /**
     * 终端设备号(门店号或收银设备ID)，注意：PC网页或公众号内支付请传"WEB"
     * @var unknown
     */
    private $device_info ="WEB";

    public function formatArgs(){
        if( ! $this->nonce_str) $this->nonce_str = uniqid();
        
        $args = parent::formatArgs();
        unset($args['mch_key']);
        return $args;
    }
    
    public function valid(){
        if( ! $this->appid)   throw new YDWXException("appid missing");
        if( ! $this->mch_id)  throw new YDWXException("mch_id missing");
        if( ! $this->mch_key) throw new YDWXException("mch_key missing");
    }
    public function sign(){
        $str = $this->toString();
        $this->sign = strtoupper(md5(urldecode($str)."&key=".$this->mch_key));
    }
}

class YDWXPayShorturlRequest extends YDWXPayBaseRequest{
    /**
     * 需要转换的URL
     * @var unknown
     */
    public $long_url;
    public $encode_long_url;
    public function formatArgs(){
        $this->encode_long_url = urlencode($this->long_url);
        $args = parent::formatArgs();
        $args['long_url'] = $args['encode_long_url'];
        unset($args['encode_long_url']);
        return $args;
    }
    public function sign(){
    	
        //$long_url 签名用原串，传输需URLencode
        $this->valid();
        $args = YDWXRequest::ignoreNull($this->sortArg());
        

        $str = "appid=".$this->appid
        ."&long_url=".$this->long_url
        ."&mch_id=".$this->mch_id
        ."&nonce_str=".$this->nonce_str;
        $signStr = strtoupper(md5($str."&key=".$this->mch_key));
         
        
        $this->sign = $signStr;
    }
}
/**
 * 微信支付通知的回复
 *
 * @author leeboo
 * @see https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=6_4
 * @see YDWXPaiedNotifyResponse
 */
class YDWXPayNotifyRequest extends YDWXPayBaseRequest{
    /**
     * SUCCESS/FAIL,此字段是通信标识，非交易标识，交易是否成功需要查看result_code来判断
     * @var unknown
     */
    public $return_code;
    /**
     * 返回信息，如非空，为错误原因;签名失败;具体某个参数格式校验错误.
     * @var unknown
     */
    public $return_msg;
    /**
     * 调用统一下单接口生成的预支付ID
     * @var unknown
     */
    public $prepay_id;
    /**
     * SUCCESS/FAIL
     * @var unknown
     */
    public $result_code;
    /**
     * 当result_code为FAIL时，商户展示给用户的错误提
     * @var unknown
     */
    public $err_code_des;
    

    public function valid(){
        parent::valid();
    }
}

/**
 * 统一下单接口请求对象
 * @author leeboo
 * @see https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_1
 */
class YDWXPayUnifiedOrderRequest extends YDWXPayNotifyRequest{
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

    protected $notify_url;
    /**
     * APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。
     * @var unknown
     */
    protected $spbill_create_ip;

    /**
     * 取值如下：JSAPI(微信内浏览器h5支付)，NATIVE（扫码支付），APP，WAP（微信外浏览器h5支付）,详细说明见参数规定
     * @var unknown
     */
    public $trade_type="JSAPI";

    /**
     * 
     * @param string $return_code_url 是否返回扫描支付二维码内容, 如果为true，则必须指定product_id；否则必须指定openid
     */
    public function __construct($return_code_url=false){
        $this->notify_url  = YDWX_SITE_URL."ydwx/pay-notify.php";
        if($return_code_url){
            $this->trade_type = "NATIVE";
        }
    }
    public function valid(){
        parent::valid();
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


class YDWXCloseOrderRequest extends YDWXPayBaseRequest{
    
    /**
     * 商户系统内部的订单号，当没提供transaction_id时需要传这个
     * @var unknown
     */
    public $out_trade_no;
    public function valid(){
        parent::valid();
        if(!$this->out_trade_no){
            throw new YDWXException("out_trade_no不能为空");
        }
    }
}

/**
 * 订单查询请求
 * @author leeboo
 *
 */
class YDWXOrderQueryRequest extends YDWXPayBaseRequest{
    /**
     * 微信的订单号，优先使用
     * @var unknown
     */
    public $transaction_id;
    public function valid(){
        parent::valid();
        if(!$this->transaction_id && !$this->out_trade_no){
            throw new YDWXException("transaction_id与out_trade_no至少设置一个");
        }
    }
}
/**
 * 申请退款请求
 */
class YDWXPayRefundRequest extends YDWXPayBaseRequest{
    /**
     * 微信订单号
     * @var String
     */
    public $transaction_id = null;
    
    /**
     * 商户系统内部的订单号,transaction_id、out_trade_no二选一，如果同时存在优先级：transaction_id> out_trade_no
     * @var String
     */
    public $out_trade_no = null;
    
    /**
     * 商户系统内部的退款单号，商户系统内部唯一，同一退款单号多次请求只退一笔
     * @var String
     */
    public $out_refund_no = null;
    
    /**
     * 订单总金额，单位为分，只能为整数，详见支付金额
     * @var float
     */
    public $total_fee = 0.0;
    
    /**
     * 退款总金额，订单总金额，单位为分，只能为整数，详见支付金额
     * @var float
     */
    public $refund_fee = 0.0;
    
    /**
     * 货币类型，符合ISO 4217标准的三位字母代码，默认人民币：CNY，其他值列表详见货币类型
     * @var String
     */
    public $refund_fee_type = null;
    
    /**
     * 操作员帐号, 默认为商户号
     * @var String
     */
    public $op_user_id = null;
    public function valid(){
        parent::valid();
        if(!$this->transaction_id && !$this->out_trade_no){
            throw new YDWXException("transaction_id与out_trade_no至少设置一个");
        }
        if(!$this->out_refund_no){
            throw new YDWXException("未设置out_refund_no");
        }
        if(!$this->total_fee){
            throw new YDWXException("未设置total_fee");
        }
        if(!$this->refund_fee){
            throw new YDWXException("未设置refund_fee");
        }
        if(!$this->op_user_id){
            throw new YDWXException("未设置op_user_id");
        }
    }
}

/**
 * 退款查询申请
 * @author leeboo
 *
 */
class YDWXPayRefundQueryRequest extends YDWXPayRefundRequest
{
   
    /**
     * 微信订单号
     * @var String
     */
    public $transaction_id = null;

    /**
     * 商户系统内部的订单号
     * @var String
     */
    public $out_trade_no = null;

    /**
     * 商户退款单号
     * @var String
     */
    public $out_refund_no = null;

    /**
     * 微信退款单号
     * refund_id、out_refund_no、out_trade_no、transaction_id四个参数必填一个，如果同时存在优先级为：refund_id>out_refund_no>transaction_id>out_trade_no
     * @var String
     */
    public $refund_id = null;

    public function valid(){
        parent::valid();
        if(!$this->transaction_id && !$this->out_trade_no && !$this->refund_id && !$this->out_refund_no){
            throw new YDWXException("transaction_id与out_trade_no,out_refund_no,refund_id至少设置一个");
        }
    }
}
/**
 * 下载对账单
 * @author leeboo
 *
 */
class YDWXPayDownloadbillRequest extends YDWXPayBaseRequest
{
    
    /**
     * 下载对账单的日期，格式：20140603
     * @var String
     */
    public $bill_date = null;
    /**
     * ALL，返回当日所有订单信息，默认值
     * SUCCESS，返回当日成功支付的订单
     * REFUND，返回当日退款订单
     * REVOKED，已撤销的订单
     * @var unknown
     */
    public $bill_type = null;

    const BILL_TYPE_ALL = "ALL";
    const BILL_TYPE_SUCCESS = "SUCCESS";
    const BILL_TYPE_REFUND = "REFUND";
    const BILL_TYPE_REVOKED = "REVOKED";
    
    
    public function valid(){
        parent::valid();
        if(!$this->bill_date){
            throw new YDWXException("bill_date未设置");
        }
    }
}

/**
 * 用于企业向微信用户个人付款 目前支持向指定微信用户的openid付款。（获取openid参见微信公众平台开发者文
 * @author leeboo
 *
 */
class YDWXCropTransferRequest extends YDWXRequest{
	const CHECK_NAME_NO_CHECK = "NO_CHECK";
	const CHECK_NAME_FORCE_CHECK = "FORCE_CHECK";
	const CHECK_NAME_OPTION_CHECK = "OPTION_CHECK";
	/**
	 * 微信分配的公众账号ID（企业号corpid即为此appId）
	 * @var unknown
	 */
	public $mch_appid;
	/**
	 * 微信支付分配的商户号
	 * @var string
	 */
	public $mchid;
	/**
	 * 
	 * @var unknown
	 */
	public $mch_key;
    /**
     * 终端设备号(门店号或收银设备ID)，注意：PC网页或公众号内支付请传"WEB"
     * @var unknown
     */
	public $device_info;
	protected $nonce_str;
	/**
	 * 商户订单号，需保持唯一性
	 * @var unknown
	 */
	public $partner_trade_no;
	/**
	 * 商户appid下，某用户的openid
	 * @var string
	 */
	public $openid;
	/**
	 * NO_CHECK：不校验真实姓名 
	 * FORCE_CHECK：强校验真实姓名（未实名认证的用户会校验失败，无法转账） 
	 * OPTION_CHECK：针对已实名认证的用户才校验真实姓名（未实名认证用户不校验，可以转账成功）
	 * @var string
	 */
	public $check_name;
	/**
	 * 收款用户真实姓名。 如果check_name设置为FORCE_CHECK或OPTION_CHECK，则必填用户真实姓名
	 * @var string
	 */
	public $re_user_name;
	/**
	 * 企业付款金额，单位为分
	 * @var int
	 */
	public $amount;
	/**
	 * 企业付款操作说明信息。必填。
	 * @var string
	 */
	public $desc;
	/**
	 * 调用接口的机器Ip地址
	 * @var string
	 */
	protected $spbill_create_ip;
	
	public function formatArgs(){
        if( ! $this->nonce_str) $this->nonce_str = uniqid();
        if( ! $this->spbill_create_ip) $this->spbill_create_ip = $_SERVER['SERVER_ADDR'];
        
        $args = parent::formatArgs();
        unset($args['mch_key']);
        return $args;
    }
    
    public function valid(){
        if( ! $this->mch_appid)   throw new YDWXException("mch_appid missing");
        if( ! $this->mchid)  throw new YDWXException("mchid missing");
        if( ! $this->mch_key)  throw new YDWXException("mch_key missing");
        if( ! $this->partner_trade_no) throw new YDWXException("partner_trade_no missing");
        if( ! $this->openid) throw new YDWXException("openid missing");
        if( ! $this->check_name) throw new YDWXException("check_name missing");
        if( ! $this->amount) throw new YDWXException("amount missing");
        if( ! $this->desc) throw new YDWXException("desc missing");
        if( $this->check_name != self::CHECK_NAME_NO_CHECK && ! $this->re_user_name) throw new YDWXException("re_user_name missing");
    }
    public function sign(){
        $str = $this->toString();
        $this->sign = strtoupper(md5(urldecode($str)."&key=".$this->mch_key));
    }
}

/**
 * 企业向个人转账返回结果
 * @author leeboo
 *
 */
class YDWXCropTransferResponse extends YDWXResponse{
	/**
	 * SUCCESS/FAIL
	 * 此字段是通信标识，非交易标识，交易是否成功需要查看result_code来判断
	 * @var string
	 */
	protected $return_code;
	/**
	 * 返回信息，如非空，为错误原因 签名失败 参数格式校验错误
	 * @var string
	 */
	protected $return_msg;
	
	/**
	 * 微信分配的公众账号ID（企业号corpid即为此appId）
	 * @var string
	 */
	public $mch_appid;
	/**
	 * 微信支付分配的商户号
	 * @var string
	 */
	public $mchid;
	/**
	 * 微信支付分配的终端设备号
	 * @var string
	 */
	public $device_info;
	/**
	 *
	 * @var string
	 */
	protected $nonce_str;
	/**
	 * 业务结果，SUCCESS/FAIL
	 * @var string
	 */
	protected $result_code;
	/**
	 * 错误码信息
	 * @var string
	 */
	protected $err_code;
	/**
	 * 结果信息描述
	 * @var string
	 */
	protected $err_code_des;
	/**
	 * 商户订单号，需保持唯一性
	 * @var string
	 */
	public $partner_trade_no;
	/**
	 * 企业付款成功，返回的微信订单号
	 * @var string
	 */
	public $payment_no;
	/**
	 * 企业付款成功时间
	 * @var string
	 */
	public $payment_time;
	
	
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
 * 用于商户的企业付款操作进行结果查询，返回付款操作详细结果。
 * @author leeboo
 *
 */
class YDWXCropTransferQueryRequest extends YDWXPayBaseRequest{
	/**
	 * 商户调用企业付款API时使用的商户订单号
	 * @var unknown
	 */
	public $partner_trade_no;
	public function formatArgs(){
		$args = parent::formatArgs();
		unset($args['device_info']);
		return $args;
	}
}
/**
 * 用于商户的企业付款操作进行结果查询，返回付款操作详细结果。
 * @author leeboo
 *
 */
class YDWXCropTransferQueryResponse extends YDWXPayBaseResponse{
	const STATUS_SUCCESS = 'SUCCESS';
	const STATUS_FAILED = 'FAILED';
	const STATUS_PROCESSING = 'PROCESSING';
	/**
	 * 商户使用查询API填写的单号的原路返回. 
	 * @var unknown
	 */
	public $partner_trade_no;
	/**
	 * 调用企业付款API时，微信系统内部产生的单号
	 * @var unknown
	 */
	public $detail_id;
	/**
	 * SUCCESS:转账成功
	 * FAILED:转账失败
	 * PROCESSING:处理中
	 * @var unknown
	 */
	public $status;
	/**
	 * 如果失败则有失败原因
	 * @var unknown
	 */
	public $reason;
	/**
	 * 转账的openid
	 * @var unknown
	 */
	public $openid;
	/**
	 * 收款用户姓名
	 * @var unknown
	 */
	public $transfer_name;
	/**
	 * 付款金额单位分
	 * @var int
	 */
	public $payment_amount;
	/**
	 * 发起转账的时间
	 * @var unknown
	 */
	public $transfer_time;
	/**
	 * 付款时候的描述
	 * @var unknown
	 */
	public $desc;
	/**
	 * 无返回
	 */
	public $appid;
}
