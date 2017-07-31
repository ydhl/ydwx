<?php
/**
 * 红包数据对象
 */

/**
 * 红包预下单请求数据
 * @author leeboo
 *
 */
class YDWXPacketPreorderRequest extends YDWXRequest{
    const TYPE_SHAKEAROUND = "shakearound";
    const TYPE_YAOTV       = "yaotv";
    /**
     * 随机字符串，不长于32位
     * @var unknown
     */
    protected $nonce_str;
    /**
     * 商户订单号（每个订单号必须唯一）组成： mch_id+yyyymmdd+10位一天内不能重复的数字。接口根据商户订单号支持重入， 如出现超时可再调用。
     * @var unknown
     */
    public $mch_billno;
    /**
     * 红包提供者的商户号（微信支付分配的商户号）
     * @var unknown
     */
    public $mch_id;
    
    /**
     * 红包提供者的商户key
     * @var unknown
     */
    public $mch_key;
    /**
     * 红包提供者公众号的appid，对应头像展示在红包页面
     * @var unknown
     */
    public $wxappid;
    /**
     * 红包提供者名称，展示在红包页面
     * @var unknown
     */
    public $send_name;
    /**
     * NORMAL-普通红包；GROUP-裂变红包(可分享红包给好友，无关注公众号能力)。
     * 见YDWX_PACKET_TYPE_XX常量
     * @var unknown
     */
    public $hb_type;
    /**
     * 总付款金额，单位分
     * @var unknown
     */
    public $total_amount;
    /**
     * 红包发放总人数，即总共有多少人可以领到该组红包（包括分享者）。普通红包填1，裂变红包必须大于1。
     * @var unknown
     */
    public $total_num;
    /**
     * 红包金额设置方式，只对裂变红包生效。ALL_RAND—全部随机
     * 见YDWX_PACKET_AMT_TYPE_XX常量
     * @var unknown
     */
    public $amt_type;
    /**
     * 红包祝福语，展示在红包页面,如感谢您参加猜灯谜活动，祝您元宵节快乐
     * @var unknown
     */
    public $wishing;
    /**
     * 活动名称，在不支持原生红包的微信版本中展示在红包消息, 如猜灯谜抢红包活动
     * @var unknown
     */
    public $act_name;
    /**
     * 备注信息，在不支持原生红包的微信版本中展示在红包消息,如猜越多得越多，快来抢！
     * @var unknown
     */
    public $remark;
    /**
     * 用于发红包时微信支付识别摇周边红包，所有开发者统一填写摇周边平台的商户号：1000052601
     * @var unknown
     */
    public $auth_mchid;
    /**
     * 用于发红包时微信支付识别摇周边红包，所有开发者统一填写摇周边平台的appid:wxbf42bd79c4391863
     * @var unknown
     */
    public $auth_appid;
    /**
     * 用于管控接口风险。具体值如下：NORMAL—正常情况；IGN_FREQ_LMT—忽略防刷限制，强制发放；IGN_DAY_LMT—忽略单用户日限额限制，强制发放；IGN_FREQ_DAY_LMT—忽略防刷和单用户日限额限制，强制发放；如无特殊要求，请设为NORMAL。若忽略某项风险控制，可能造成资金损失，请谨慎使用。
     * 见YDWX_PACKET_RISK_CNTL_XX常量
     * @var unknown
     */
    public $risk_cntl;
    /**
     * 默认摇一摇红包
     * @var unknown
     */
    public $type="shakearound";
    protected function formatArgs(){
        if( ! $this->nonce_str)$this->nonce_str = uniqid();
        $args = parent::formatArgs();
        $args['total_amount']   = intval($args['total_amount']);
        $args['total_num']      = intval($args['total_num']);
        if($this->type==self::TYPE_YAOTV){
            $args['auth_mchid']     = "1000048201";
            $args['auth_appid']     = 'wxbe43ea14debca355';
        }else{
            $args['auth_mchid']     = "1000052601";
            $args['auth_appid']     = 'wxbf42bd79c4391863';
        }
        unset( $args['type'] );
        unset( $args['mch_key'] );
        return $args;
    }
    public function sign(){
        $str = $this->toString();
        $this->sign = strtoupper(md5(urldecode($str)."&key=".$this->mch_key));
    }
    public function valid(){
        if( ! $this->mch_id)throw new YDWXException("mch id missing");
        if( ! $this->mch_key)throw new YDWXException("mch id missing");
    }
}

/**
 * 红包预下单返回结果
 * @author leeboo
 *
 */
class YDWXPacketPreorderResponse extends YDWXPayBaseResponse{
    /**
     * 商户订单号（每个订单号必须唯一）组成： mch_id+yyyymmdd+10位一天内不能重复的数字。
     * @var unknown
     */
    public $mch_billno;
    /**
     * 总付款金额，单位分
     * @var unknown
     */
    public $total_amount;
    /**
     * sp_ticket，一个普通红包对应一个ticket
     * @var unknown
     */
    public $sp_ticket;
    /**
     * 红包内部订单号
     * @var unknown
     */
    public $detail_id;
    
    /**
     * 红包发放时间 20150429203444
     * @var unknown
     */
    public $send_time;
    
    public function build($msg){
        parent::build($msg);
        $this->appid = $this->wxappid;
    }
}

/**
 * 创建红包活动请求数据
 * @author leeboo
 *
 */
class YDWXPacketAddLotteryInfoRequest extends YDWXRequest{
    /**
     * 抽奖活动名称（选择使用模板时，也作为摇一摇消息主标题），最长6个汉字，12个英文字母。
     * @var unknown
     */
    public $title;
    /**
     * 抽奖活动描述（选择使用模板时，也作为摇一摇消息副标题），最长7个汉字，14个英文字母。
     * @var unknown
     */
    public $desc;
    /**
     * 抽奖开关。0关闭，1开启，默认为1
     * @var unknown
     */
    public $onoff;
    /**
     * 抽奖活动开始时间，unix时间戳，单位秒
     * @var unknown
     */
    public $begin_time;
    /**
     * 抽奖活动结束时间，unix时间戳，单位秒
     * @var unknown
     */
    public $expire_time;
    /**
     * 红包提供商户公众号的appid，需与预下单中的公众账号appid（wxappid）一致
     * @var unknown
     */
    public $sponsor_appid;
    /**
     * 红包总数，红包总数是录入红包ticket总数的上限，因此红包总数应该大于等于预下单时红包ticket总数。
     * @var unknown
     */
    public $total;
    /**
     * 红包关注界面后可以跳转到第三方自定义的页面
     * @var unknown
     */
    public $jump_url;
    /**
     * 开发者自定义的key，用来生成活动抽奖接口的签名参数，长度32位。使用方式见sign生成规则
     * @var unknown
     */
    public $key;
    public function valid(){
    
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['total']          = floatval($args['total']);
        $args['expire_time']    = floatval($args['expire_time']);
        $args['begin_time']     = floatval($args['begin_time']);
        $args['onoff']          = intval($args['onoff']);
        return $args;
    }
}

/**
 * 增加红包结果
 * @author leeboo
 *
 */
class YDWXPacketAddLotteryInfoResponse extends YDWXResponse{
    /**
     * 生成的红包活动id
     * @var unknown
     */
    public $lottery_id;
    /**
     * 生成的模板页面ID
     * @var unknown
     */
    public $page_id;
}

/**
 * 录入红包信息
 * @author leeboo
 *
 */
class YDWXPacketSetPrizeBucketRequest extends YDWXRequest{
    /**
     * 红包抽奖id，来自ydwx_packet_addlotteryinfo返回的lottery_id
     * @var unknown
     */
    public $lottery_id;
    /**
     * 红包提供者的商户号，，需与预下单中的商户号mch_id一致
     * @var unknown
     */
    public $mchid;
    /**
     * 红包提供商户公众号的appid，需与预下单中的公众账号appid（wxappid）一致
     * @var unknown
     */
    public $sponsor_appid;
    /**
     * 红包ticket列表，如果红包数较多，
     * 可以一次传入多个红包，批量调用该接口设置红包信息。每次请求传入的红包个数上限为100
     * @var array
     */
    public $prize_info_list;
    public function valid(){
        
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['prize_info_list'] = array_map(function($item){
            return array("ticket"=>$item);
        }, $args['prize_info_list']);
        return $args;
    }
}

/**
 * 录入红包结果
 * @author leeboo
 *
 */
class YDWXPacketSetPrizeBucketResponse extends YDWXResponse{
    /**
     * 重复使用的ticket列表
     * @var array
     */
    public $repeat_ticket_list;
    /**
     * 过期的ticket列表
     * @var array
     */
    public $expire_ticket_list;
    /**
     * 金额不在大于1元，小于1000元的ticket列表
     * @var unknown
     */
    public $invalid_amount_ticket_list;
    /**
     * 成功录入的红包数量
     * @var unknown
     */
    public $success_num;
    
    public function build($msg){
        parent::build($msg);
        
        if($this->repeat_ticket_list){
            $this->repeat_ticket_list = array_map(function($item){
                return $item['ticket'];
            }, $this->repeat_ticket_list);
        }
        
        if($this->expire_ticket_list){
            $this->expire_ticket_list = array_map(function($item){
                return $item['ticket'];
            }, $this->expire_ticket_list);
        }
        
        if($this->invalid_amount_ticket_list){
            $this->invalid_amount_ticket_list = array_map(function($item){
                return $item['ticket'];
            }, $this->invalid_amount_ticket_list);
        }
    }
}

/**
 * 查询红包信息
 * @author leeboo
 *
 */
class YDWXPacketGetHBInfoRequest extends YDWXRequest{
    /**
     * 随机字符串，不长于32位
     * @var unknown
     */
    protected $nonce_str;
    /**
     * 商户订单号（每个订单号必须唯一）组成： mch_id+yyyymmdd+10位一天内不能重复的数字。接口根据商户订单号支持重入， 如出现超时可再调用。
     * @var unknown
     */
    public $mch_billno;
    /**
     * 红包提供者的商户号（微信支付分配的商户号）
     * @var unknown
     */
    public $mch_id;
    
    /**
     * 红包提供者的商户key
     * @var unknown
     */
    public $mch_key;
    
    /**
     * 微信分配公众号id，企业号是cropid
     * @var unknown
     */
    public $appid;

    protected  $bill_type;
    
    protected function formatArgs(){
        if( ! $this->nonce_str)$this->nonce_str = uniqid();
        $args = parent::formatArgs();
        $args['bill_type']   = "MCHT";
        unset( $args['mch_key'] );
        return $args;
    }
    public function sign(){
        $str = $this->toString();
        $this->sign = strtoupper(md5(urldecode($str)."&key=".$this->mch_key));
    }
    public function valid(){
        if( ! $this->mch_id)throw new YDWXException("mch id missing");
        if( ! $this->mch_key)throw new YDWXException("mch key missing");
    }
}

/**
 * 查询红包结果
 * @author leeboo
 *
 */
class YDWXPacketGetHBInfoResponse extends YDWXPayBaseResponse{
    /**
     * 商户订单号（每个订单号必须唯一）组成： mch_id+yyyymmdd+10位一天内不能重复的数字。
     * @var unknown
     */
    public $mch_billno;
    /**
     * 使用API发放现金红包时返回的红包单号
     * @var unknown
     */
    public $detail_id;
    /**
     * 红包状态，SENDING:发放中 
     * SENT:已发放待领取 
     * FAILED：发放失败 
     * RECEIVED:已领取 
     * REFUND:已退款
     * @var unknown
     */
    public $status;
    /**
     * API:通过API接口发放 
     * UPLOAD:通过上传文件方式发放 
     * ACTIVITY:通过活动方式发放
     * @var unknown
     */
    public $send_type;
    /**
     * GROUP:裂变红包 
     * NORMAL:普通红包
     * @var unknown
     */
    public $hb_type;
    
    /**
     * 红包个数
     * @var unknown
     */
    public $total_num;
    /**
     * 红包总金额（单位分）
     * @var unknown
     */
    public $total_amount;
    
    /**
     * 发送失败原因
     */
    public $reason;
    
    /**
     * 红包发放时间 20150429203444
     * @var unknown
     */
    public $send_time;
    
    /**
     * 红包的退款时间（如果其未领取的退款）
     * @var unknown
     */
    public $refund_time;
    
    /**
     * 红包退款金额
     */
    public $refund_amount;
    /**
     * 祝福语
     * @var unknown
     */
    public $wishing;
    /**
     * 活动描述，低版本微信可见
     * @var unknown
     */
    public $remark;
    /**
     * 发红包的活动名称
     * @var unknown
     */
    public $act_name;
    
    /**
     * array(array(openid,amount,rcv_time))
     * @var unknown
     */
    public $hblist;
    
    public function build($msg){
        parent::build($msg);
        $array = array();
        $hblist = (array)$this->hblist;
        if( ! is_array($hblist['hbinfo'])){
            $hbinfo = (array)$hblist['hbinfo'];
            $array[] = array(
                    "openid"=>$hbinfo['openid'],
                    "amount"=>$hbinfo['amount'],
                    "rcv_time"=>$hbinfo['rcv_time']);
        }else{
            foreach ($hblist['hbinfo'] as $hbinfo){
                $hbinfo = (array)$hbinfo;
                $array[] = array(
                            "openid"=>$hbinfo['openid'],
                            "amount"=>$hbinfo['amount'],
                            "rcv_time"=>$hbinfo['rcv_time']);
            }
        }
        $this->hblist = $array;
    }
}


/**
 * 发送红包给指定用户
 * @author leeboo
 *
 */
class YDWXPacketSendRequest extends YDWXRequest{
    /**
     * 随机字符串，不长于32位
     * @var unknown
     */
    protected $nonce_str;
    /**
     * 商户订单号（每个订单号必须唯一）组成： mch_id+yyyymmdd+10位一天内不能重复的数字。接口根据商户订单号支持重入， 如出现超时可再调用。
     * @var unknown
     */
    public $mch_billno;
    /**
     * 红包提供者的商户号（微信支付分配的商户号）
     * @var unknown
     */
    public $mch_id;

    /**
     * 红包提供者的商户key
     * @var unknown
     */
    public $mch_key;

    /**
     * 微信分配公众号id，企业号是cropid
     * @var unknown
     */
    public $wxappid;

    /**
     * 红包发送者名称
     * @var unknown
     */
    public $send_name;
    
    /**
     * 接受红包的用户用户在wxappid下的openid
     * @var unknown
     */
    public $re_openid;
    
    /**
     * 付款金额，单位分
     */
    public $total_amount;
    
    /**
     * 红包发放总人数, 普通红包固定位1，裂变红包大于1
     * @var unknown
     */
    public $total_num;
    
    /**
     * 红包祝福语
     * @var unknown
     */
    public $wishing;
    
    /**
     * 调用接口的机器Ip地址
     * @var unknown
     */
    public $client_ip;
    
    /**
     * 活动名称
     * @var unknown
     */
    public $act_name;
    
    /**
     * 备注信息
     */
    public $remark;

    protected function formatArgs(){
        if( ! $this->nonce_str)$this->nonce_str = uniqid();
        $args = parent::formatArgs();
        $args['total_num']    = 1;
        $args['total_amount'] = intval($args['total_amount']);
        unset( $args['mch_key'] );
        return $args;
    }
    public function sign(){
        $str = $this->toString();
        $this->sign = strtoupper(md5(urldecode($str)."&key=".$this->mch_key));
    }
    public function valid(){
        if( ! $this->mch_id)throw new YDWXException("mch id missing");
        if( ! $this->mch_key)throw new YDWXException("mch key missing");
    }
}


/**
 * 向指定人发送红包结果
 * @author leeboo
 *
 */
class YDWXPacketSendResponse extends YDWXPayBaseResponse{
    /**
     * 商户订单号（每个订单号必须唯一）组成： mch_id+yyyymmdd+10位一天内不能重复的数字。
     * @var unknown
     */
    public $mch_billno;
    /**
     * 商户订单号（每个订单号必须唯一）组成：mch_id+yyyymmdd+10位一天内不能重复的数字
     * @var unknown
     */
    public $mch_id;
    /**
     * 商户appid
     * @var unknown
     */
    public $wxappid;
    /**
     * 接受收红包的用户,用户在wxappid下的openid
     * @var unknown
     */
    public $re_openid;
    /**
     * 付款金额，单位分
     * @var unknown
     */
    public $total_amount;
    /**
     * 红包发送时间
     * @var unknown
     */
    public $send_time;
    /**
     * 红包订单的微信单号
     * @var unknown
     */
    public $send_listid;
}

class YDWXPacketSendGroupRequest extends YDWXPacketSendRequest{
    /**
     * 红包金额设置方式
     * ALL_RAND—全部随机,商户指定总金额和红包发放总人数，由微信支付随机计算出各红包金额
     * 
     * @var unknown
     */
    public $amt_type;
    
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['total_num']    = intval($this->total_num);
        unset( $args['mch_key'] );
        unset( $args['client_ip'] );
        return $args;
    }
}

class YDWXPacketSendGroupResponse extends YDWXPayBaseResponse{
    /**
     * 商户订单号（每个订单号必须唯一）组成： mch_id+yyyymmdd+10位一天内不能重复的数字。
     * @var unknown
     */
    public $mch_billno;
    /**
     * 商户订单号（每个订单号必须唯一）组成：mch_id+yyyymmdd+10位一天内不能重复的数字
     * @var unknown
     */
    public $mch_id;
    /**
     * 商户appid
     * @var unknown
     */
    public $wxappid;
    /**
     * 接受收红包的用户,用户在wxappid下的openid
     * @var unknown
     */
    public $re_openid;
    /**
     * 付款金额，单位分
     * @var unknown
     */
    public $total_amount;
    /**
     * 红包发送时间
     * @var unknown
     */
    public $send_time;
    /**
     * 红包订单的微信单号
     * @var unknown
     */
    public $send_listid;
}