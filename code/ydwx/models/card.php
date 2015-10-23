<?php
use app\zb\buile_url_select;
/**
 * 卡券相关数据对象
 */


/**
 * 
 * 卡券基础信息字段
 * @author leeboo
 *
 */
abstract class YDWXCardBase extends YDWXRequest{
    /**
     * 卡券状态，创建时不用设置，见YDWX_CARD_STATUS_XX常量
     * @var unknown
     */
    public $status;
    /**
     * 卡券的商户logo，建议像素为300*300。
     * @var string
     */
    public $logo_url;

    /**
     * 商户名字,字数上限为12个汉字。
     * @var string
     */
    public $brand_name;

    /**
     * 见YDWX_CARD_CODE_TYPEXX常量
     * @access public
     * @var string
     */
    public $code_type;

    /**
     * 卡券名，字数上限为9个汉字。(建议涵盖卡券属性、服务及金额)
     * @var string
     */
    public $title;

    /**
     * 券名，字数上限为18个汉字。
     * @var string
     */
    public $sub_title;

    /**
     * 券颜色。见YDWX_CARD_COLOR_XX常量
     */
    public $color;

    /**
     * 卡券使用提醒，字数上限为16个汉字。
     * @var string
     */
    public $notice;

    /**
     * 客服电话。
     * @var string
     */
    public $service_phone;

    /**
     * 卡券使用说明，字数上限为1024个汉字。
     * @var string
     */
    public $description;

    /**
     * 使用时间的类型, YDWX_DATE_TYPE_FIX_TIME_RANGE或者YDWX_DATE_TYPE_FIX_TERM
     * @var string
     */
    public $date_info_type;

    /**
     * type为DATE_TYPE_FIX_TIME_RANGE时专用，表示起用时间。从1970年1月1日00:00:00至起用时间的秒数，最终需转换为字符串形态传入。（东八区时间，单位为秒）
     * @var string
     */
    public $date_info_begin_timestamp;

    /**
     * type为DATE_TYPE_FIX_TIME_RANGE时专用，表示结束时间，建议设置为截止日期的23:59:59过期。（东八区时间，单位为秒）
     * @var string
     */
    public $date_info_end_timestamp;

    /**
     * 卡券全部库存的数量，上限为100000000
     * @var string
     */
    public $sku_total_quantity;
    /**
     * 卡券现有库存的数量，上限为100000000
     * @var string
     */
    public $sku_quantity;

    /**
     * 每人可领券的数量限制,不填写默认为50。
     * @var Integer
     */
    public $get_limit;

    /**
     * 是否自定义Code码。填写true或false，默认为false。
     * 通常自有优惠码系统的开发者选择自定义Code码，并在卡券投放时带入Code码
     * @var boolean
     */
    public $use_custom_code;

    /**
     * 是否指定用户领取，填写true或false。默认为false。通常指定特殊用户群体投放卡券或防止刷券时选择指定用户领取。
     * @var boolean
     */
    public $bind_openid;

    /**
     * 卡券领取页面是否可分享。
     * 建议指定Code码、指定OpenID等强限制条件的卡券填写false
     * @var boolean
     */
    public $can_share;

    /**
     * 卡券是否可转赠。
     * @var boolean
     */
    public $can_give_friend;

    /**
     * 门店位置poiid。调用POI门店管理接口获取门店位置poiid。具备线下门店的商户为必填。
     * @var array
     */
    public $location_id_list = array();

    /**
     * 自定义跳转外链的入口名字
     * 仅卡券被用户领取且处于有效状态时显示（转赠中、核销后不显示）
     * @var string
    */
    public $custom_url_name;

    /**
     * 自定义跳转的URL。
     * 仅卡券被用户领取且处于有效状态时显示（转赠中、核销后不显示）
     * @var string
     */
    public $custom_url;

    /**
     * 显示在入口右侧的提示语。
     * 仅卡券被用户领取且处于有效状态时显示（转赠中、核销后不显示）
     * @var string
     */
    public $custom_url_sub_title;

    /**
     * 营销场景的自定义入口名称。
     * 卡券处于正常状态、转赠中、核销后等异常状态均显示该入口
     * @var string
     */
    public $promotion_url_name;

    /**
     * 显示在入口右侧的提示语。
     * 卡券处于正常状态、转赠中、核销后等异常状态均显示该入口。
     * @var string
     */
    public $promotion_url_sub_title;

    /**
     * 自定义跳转的URL。
     * 卡券处于正常状态、转赠中、核销后等异常状态均显示该入口
     * @var string
     */
    public $promotion_url;

    /**
     * 第三方来源名，例如同程旅游、大众点评。
     * @var string
     */
    public $source;


    /**
     * type为DATE_TYPE_FIX_TERM时专用，表示自领取后多少天内有效，不支持填写0
     * @var Integer
     */
    public $fixed_term = null;

    /**
     * type为DATE_TYPE_FIX_TERM时专用，表示自领取后多少天开始生效，领取后当天生效填写0。（单位为天）
     * @var Integer
     */
    public $fixed_begin_term = null;
    
    /**
     * 该字段设置为GET_CUSTOM_CODE_MODE_DEPOSIT后，自定义code卡券方可进行导入code并投放的动作
     * @var unknown
     */
    public $get_custom_code_mode = null;
    /**
     * 代子商户发卡时传入子商户的id，无公众号子商户可通过ydwx_card_submerchant_submit创建
     * @var unknown
     */
    public $merchant_id;
    
    public function valid(){
        //TODO http://mp.weixin.qq.com/wiki/8/b7e310e7943f7763450eced91fa793b0.html#.E5.8D.A1.E5.88.B8.E5.9F.BA.E7.A1.80.E4.BF.A1.E6.81.AF.E5.AD.97.E6.AE.B5.EF.BC.88.E9.87.8D.E8.A6.81.EF.BC.89
    } 
    
    
    protected function formatArgs(){
        $args = parent::formatArgs();
        $array = array();
        $cls  = get_class($this);
        $type = constant("{$cls}::CARD_TYPE");
        $array['card']['card_type']     = $type;
        if($this->sku_quantity){
            $args['sku']['quantity']        = intval($this->sku_quantity);
        }

        if($args['location_id_list']){
            $args['location_id_list'] = array_map(function($item){
                return intval($item);
            }, $args['location_id_list']);
        }
        
        if($this->date_info_type==YDWX_DATE_TYPE_FIX_TIME_RANGE && $this->date_info_begin_timestamp){
            $args['date_info']['type']        = $this->date_info_type;
            $args['date_info']['begin_timestamp']  = intval($this->date_info_begin_timestamp);
            $args['date_info']['end_timestamp']    = intval($this->date_info_end_timestamp);
        }else if($this->fixed_term){
            $args['date_info']['type']        = $this->date_info_type;
            $args['date_info']['fixed_term']        = intval($this->fixed_term);
            $args['date_info']['fixed_begin_term']  = intval($this->fixed_begin_term);
        }
        
        if(@$args['merchant_id']){
            $args['sub_merchant_info'] = $args['merchant_id'];
        }
        
        unset($args['sku_total_quantity']);
        unset($args['sku_quantity']);
        unset($args['date_info_begin_timestamp']);
        unset($args['date_info_end_timestamp']);
        unset($args['date_info_type']);
        unset($args['fixed_term']);
        unset($args['fixed_begin_term']);
        unset($args['merchant_id']);
        
        
        
        $array['card'][strtolower($type)]['base_info'] = $args;
        return $array;
    }
}

/**
 * 礼品券
 * @author leeboo
 *
 */
class YDWXCardGift extends YDWXCardBase{
    /**
     * 礼品券专用，填写礼品的名称。
     */
    public $gift = null;
    
    const CARD_TYPE = 'GIFT';
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['card']['gift']['gift'] = $this->gift;
        unset($args['card']['gift']['base_info']['gift']);
        return $args;
    }
}

/**
 * 团购券
 * @author leeboo
 *
 */
class YDWXCardGroupon extends YDWXCardBase{
    /**
     * 团购券专用，团购详情。
     */
    public $deal_detail = '';
    const  CARD_TYPE = 'GROUPON';
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['card']['groupon']['deal_detail'] = $this->deal_detail;
        unset($args['card']['groupon']['base_info']['deal_detail']);
        return $args;
    }
}

/**
 * 折扣券
 * @author leeboo
 *
 */
class YDWXCardDiscount extends YDWXCardBase{
    /**
     * 扣券专用，表示打折额度（百分比）。填30就是七折。
     */
    public $discount = '';
    const  CARD_TYPE = 'DISCOUNT';
    protected function formatArgs(){
        $args = parent::formatArgs();
        if($this->discount){
            $args['card']['discount']['discount'] = intval($this->discount);
        }
        unset($args['card']['discount']['base_info']['discount']);
        return $args;
    }
}

/**
 * 代金券
 * @author leeboo
 *
 */
class YDWXCardCash extends YDWXCardBase{
    const CARD_TYPE = 'CASH';

    /**
     * 代金券专用，表示起用金额（单位为分）,如果无起用门槛则填0。
     */
    public $least_cost = null;

    /**
     * 代金券专用，表示减免金额。（单位为分）
     */
    public $reduce_cost = null;
    
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['card']['cash']['least_cost']  = intval($this->least_cost);
        $args['card']['cash']['reduce_cost'] = intval($this->reduce_cost);
        unset($args['card']['cash']['base_info']['least_cost']);
        unset($args['card']['cash']['base_info']['reduce_cost']);
        return $args;
    }
}

/**
 * 优惠券
 * @author leeboo
 *
 */
class YDWXCardGeneralCoupon extends YDWXCardBase
{
    /**
     * 优惠券专用，填写优惠详情。
     * @var string
     */
    public $default_detail = '';

    const CARD_TYPE = 'GENERAL_COUPON';

    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['card']['general_coupon']['default_detail']  = $this->default_detail;
        unset($args['card']['general_coupon']['base_info']['default_detail']);
        return $args;
    }
}
/**
 * 会员卡
 * @author leeboo
 *
 */
class YDWXCardMemberCard extends YDWXCardBase{
    const CARD_TYPE = "MEMBER_CARD";
    /**
     * 会员卡专属字段，表示是否支持积分，填写true或false，如填写true，积分相关字段均为必填。
     * @var unknown
     */
    public $supply_balance;
    /**
     * 会员卡专属字段，表示否支持储值，填写true或false，如填写true，储值相关字段均为必填。
     * @var unknown
     */
    public $supply_bonus;
    /**
     * 积分清零规则。
     * @var unknown
     */
    public $bonus_cleared;
    /**
     * 积分规则。
     * @var unknown
     */
    public $bonus_rules;
    /**
     * 储值规则。
     * @var unknown
     */
    public $balance_rules;
    /**
     * 会员卡专属字段，表示特权说明。
     * @var unknown
     */
    public $prerogative;
    /**
     * 绑定旧卡的url。
     * @var unknown
     */
    public $bind_old_card_url;
    /**
     * 进入会员卡时是否推送事件，填写true或false。
     * @var unknown
     */
    public $need_push_on_view;
    /**
     * 激活会员卡。
     * @var unknown
     */
    public $activate_url;
    
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['card']['member_card']['supply_balance'] = $this->supply_balance;
        $args['card']['member_card']['supply_bonus'] = $this->supply_bonus;
        $args['card']['member_card']['activate_url'] = $this->activate_url;
        $args['card']['member_card']['need_push_on_view'] = $this->need_push_on_view;
        $args['card']['member_card']['bind_old_card_url'] = $this->bind_old_card_url;
        $args['card']['member_card']['prerogative'] = $this->prerogative;
        $args['card']['member_card']['balance_rules'] = $this->balance_rules;
        $args['card']['member_card']['bonus_rules'] = $this->bonus_rules;
        $args['card']['member_card']['onus_cleared'] = $this->onus_cleared;
        unset($args['card']['member_card']['base_info']['supply_balance']);
        unset($args['card']['member_card']['base_info']['supply_bonus']);
        unset($args['card']['member_card']['base_info']['activate_url']);
        unset($args['card']['member_card']['base_info']['need_push_on_view']);
        unset($args['card']['member_card']['base_info']['bind_old_card_url']);
        unset($args['card']['member_card']['base_info']['prerogative']);
        unset($args['card']['member_card']['base_info']['balance_rules']);
        unset($args['card']['member_card']['base_info']['bonus_rules']);
        unset($args['card']['member_card']['base_info']['onus_cleared']);
        return $args;
    }
}
/**
 * 飞机票
 * @author leeboo
 *
 */
class YDWXCardBoardingPass extends YDWXCardBase{
    const CARD_TYPE = "BOARDING_PASS";
    /**
     * 飞机票的起点，上限为18个汉字。
     * @var unknown
     */
    public $from;
    /**
     * 飞机票的终点，上限为18个汉字。
     * @var unknown
     */
    public $to;
    /**
     * 航班。
     * @var unknown
     */
    public $flight;
    /**
     * 起飞时间。（Unix时间戳格式）
     * @var unknown
     */
    public $departure_time;
    /**
     * 降落时间。（Unix时间戳格式）
     * @var unknown
     */
    public $landing_time;
    /**
     * 在线值机的链接。
     * @var unknown
     */
    public $check_in_url;
    /**
     * 登机口。如发生登机口变更，建议商家实时调用该接口变更。
     * @var unknown
     */
    public $gate;
    /**
     * 登机时间，只显示“时分”不显示日期。（Unix时间戳格式）
     * @var unknown
     */
    public $boarding_time;
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['card']['boarding_pass']['boarding_time']     = $this->boarding_time;
        $args['card']['boarding_pass']['gate']              = $this->gate;
        $args['card']['boarding_pass']['check_in_url']      = $this->check_in_url;
        $args['card']['boarding_pass']['landing_time']      = $this->landing_time;
        $args['card']['boarding_pass']['departure_time']    = $this->departure_time;
        $args['card']['boarding_pass']['flight']            = $this->flight;
        $args['card']['boarding_pass']['from']              = $this->from;
        $args['card']['boarding_pass']['to']                = $this->to;
        unset($args['card']['boarding_pass']['base_info']['boarding_time']);
        unset($args['card']['boarding_pass']['base_info']['gate']);
        unset($args['card']['boarding_pass']['base_info']['check_in_url']);
        unset($args['card']['boarding_pass']['base_info']['landing_time']);
        unset($args['card']['boarding_pass']['base_info']['departure_time']);
        unset($args['card']['boarding_pass']['base_info']['flight']);
        unset($args['card']['boarding_pass']['base_info']['from']);
        unset($args['card']['boarding_pass']['base_info']['to']);
        return $args;
    }
}

/**
 * 创建一张卡券二维码参数
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/12/ccd3aa0bddfe5211aace864de00b42e0.html#.E5.88.9B.E5.BB.BA.E4.BA.8C.E7.BB.B4.E7.A0.81.E6.8E.A5.E5.8F.A3
 */
class YDWXCardQrcodeRequest extends YDWXRequest{
    /**
     * 卡券ID。
     * @var unknown
     */
    public $card_id;
    /**
     * 卡券Code码,创建卡券时use_custom_code字段为true的卡券必须填写，非自定义code不必填写。
     * @var unknown
     */
    public $code;
    /**
     * 指定领取者的openid，只有该用户能领取。bind_openid字段为true的卡券必须填写，非指定openid不必填写。
     * @var unknown
     */
    public $openid;
    /**
     * 指定二维码的有效时间，范围是60 ~ 1800秒。不填默认为永久有效。
     */
    public $expire_seconds;
    /**
     * 指定下发二维码，生成的二维码随机分配一个code，领取后不可再次扫描。填写true或false。默认false。
     * @var unknown
     */
    public $is_unique_code;
    /**
     * 领取场景值，用于领取渠道的数据统计，默认值为0，字段类型为整型，
     * 长度限制为60位数字。用户领取卡券后触发的事件推送(YDWXHOOK:EVENT_USER_GET_CARD)中会带上此自定义场景值。
     * @var unknown
     */
    public $outer_id;
    
    public function valid(){
        
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        unset($args['expire_seconds']);
        
        $args['action_name']    = "QR_CARD";
        $args['expire_seconds'] = $this->expire_seconds;
        $args['action_info']['card'] = $args;
        return $args;
    }
}
/**
 * 景点门票
 * @author leeboo
 *
 */
class YDWXCardScenicTicket extends YDWXCardBase{
    const CARD_TYPE = "SCENIC_TICKET";
    protected function formatArgs(){
        $args = parent::formatArgs();
//         $args['scenic_ticket']['meeting_detail']     = $this->meeting_detail;
        return $args;
    }
}
/**
 * 电源门票
 * @author leeboo
 *
 */
class YDWXCardMovieTicket extends YDWXCardBase{
    const CARD_TYPE = "MOVIE_TICKET";
    protected function formatArgs(){
        $args = parent::formatArgs();
        //         $args['movie_ticket']['meeting_detail']     = $this->meeting_detail;
        return $args;
    }
}
/**
 * 会议门票
 * @author leeboo
 *
 */
class YDWXCardMeetingTicket extends YDWXCardBase{
    const CARD_TYPE = "MEETING_TICKET";
    /**
     * 会议详情。
     * @var unknown
     */
    public $meeting_detail;
    /**
     * 会场导览图。
     * @var unknown
     */
    public $map_url;
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['card']['meeting_ticket']['meeting_detail']     = $this->meeting_detail;
        $args['card']['meeting_ticket']['map_url']              = $this->map_url;
        unset($args['card']['meeting_ticket']['base_info']['meeting_detail']);
        unset($args['card']['meeting_ticket']['base_info']['map_url']);
        return $args;
    }
}
/**
 * 汽车票
 * @author leeboo
 *
 */
class YDWXCardBusTicket extends YDWXCardBase{
    const CARD_TYPE = "BUS_TICKET";
    protected function formatArgs(){
        $args = parent::formatArgs();
        //         $args['bus_ticket']['meeting_detail']     = $this->meeting_detail;
        return $args;
    }
}

class YDWXCard extends YDWXRequest{
    /**
     * 卡券ID。
     * @var unknown
     */
    public $card_id;
    /**
     * 卡券Code码,创建卡券时use_custom_code字段为true的卡券必须填写，非自定义code不必填写。
     * @var unknown
     */
    public $code;
    public function valid(){
    
    }
}
/**
 * 创建卡券货架请求数据
 * @author leeboo
 *
 */
class YDWXCardLandingPageRequest extends YDWXRequest{
    /**
     * 页面的banner图片链接，须调用，建议尺寸为640*300。
     * @var unknown
     */
    public $banner;
    /**
     * 页面的title。
     * @var unknown
     */
    public $title;
    /**
     * 页面是否可以分享,填入true/false
     * @var unknown
     */
    public $can_share;
    /**
     * 见YDWX_CARD_SCENE_XX常量
     * @var unknown
     */
    public $scene;
    private $cardlist = array();
    public function addCard($cardid, $thumb_url){
        $this->cardlist[] = array("card_id"=>$cardid, "thumb_url"=>$thumb_url);
        return $this;
    }
    public function valid(){

    }
}

/**
 * 用于微信卡券js添加接口的数据对象
 * @author leeboo
 *
 */
class YDWXCardExt extends YDWXRequest{
    /**
     * 卡券ID。
     * @var unknown
     */
    public $cardId;
    /**
     * 卡券Code码,创建卡券时use_custom_code字段为true的卡券必须填写，非自定义code不必填写。
     * @var unknown
     */
    public $code;
    /**
     * 指定领取者的openid，只有该用户能领取。bind_openid字段为true的卡券必须填写，bind_openid字段为false不必填写。
     * @var unknown
     */
    public $openid;
    /**
     * 用于签名
     * @var unknown
     */
    public $jsApiTicket;
    private $args = array();
    private function format(){
        if($this->args)return $this->args;
        
        $args = array();
        $args['cardId'] = $this->cardId;
        $args['cardExt']['code']    = $this->code;
        $args['cardExt']['openid']  = $this->openid;
        $args['cardExt']['timestamp'] = strval(time());
        $args['cardExt']['nonce_str'] = uniqid();
        $this->args = YDWXRequest::ignoreNull($args);
        return $this->args;
    }
    protected function formatArgs(){
        $args = $this->format();
        $args['cardExt']['signature'] = $this->sign();
        $args['cardExt'] = json_encode($args['cardExt']);
        return $args;
    }
    public function sign(){
        $args = $this->format();
        $values = array();
        $values[] = $this->cardId;
        $values[] = $this->code;
        $values[] = $this->openid;
        $values[] = $this->jsApiTicket;
        $values[] = $args['cardExt']['timestamp'];
        $values[] = $args['cardExt']['nonce_str'];
        $values = YDWXRequest::ignoreNull($values);
        sort($values);
        return sha1(join("", $values));
    }
    public function valid(){

    }
}

/**
 * 创建多张卡券二维码参数
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/12/ccd3aa0bddfe5211aace864de00b42e0.html#.E5.88.9B.E5.BB.BA.E4.BA.8C.E7.BB.B4.E7.A0.81.E6.8E.A5.E5.8F.A3
 */
class YDWXMultiCardQrcodeRequest extends YDWXRequest{
    /**
     * 卡券ID。
     * @var YDWXCard
     */
    public $cards = array();
    
    public function valid(){
    
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
    
        $args['action_name']    = "QR_MULTIPLE_CARD";
        foreach ($this->cards as $card){
            $args['action_info']['multiple_card']['card_list'][] = $card->toArray();
        }
        return $args;
    }
}

/**
 * 创建卡券二维码的响应
 * 
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/12/ccd3aa0bddfe5211aace864de00b42e0.html#.E5.88.9B.E5.BB.BA.E4.BA.8C.E7.BB.B4.E7.A0.81.E6.8E.A5.E5.8F.A3
 */
class YDWXCardQrcodeResponse extends YDWXResponse{
    /**
     * 获取的二维码ticket，凭借此ticket调用通过ticket换取二维码接口可以在有效时间内换取二维码。
     * @var unknown
     */
    public $ticket;
    public $expire_seconds;
    /**
     * 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片
     * @var unknown
     */
    public $url;
    /**
     * 二维码显示地址，点击后跳转二维码页面
     * @var unknown
     */
    public $show_qrcode_url;
}
/**
 * 创建卡券货架结果
 * @author leeboo
 *
 */
class YDWXCardLandingPageResponse extends  YDWXResponse{
    /**
     * 货架链接。
     * @var unknown
     */
    public $url;
    /**
     * 货架ID。货架的唯一标识。
     * @var unknown
     */
    public $page_id;
}
/**
 * 核查导入微信卡券的code结果数据
 * @author leeboo
 *
 */
class YDWXCardCodeCheckResponse extends  YDWXResponse{
    /**
     * 已经成功存入的code。
     * @var unknown
     */
    public $exist_code;
    /**
     * 没有存入的code。
     * @var unknown
     */
    public $not_exist_code;
}

/**
 * 导入微信微信卡券的结果数据
 * @author leeboo
 *
 */
class YDWXCardCodeImportResponse extends YDWXResponse{
    /**
     * 成功个数
     * @var unknown
     */
    public $succ_code;
    /**
     * 重复导入的code会自动被过滤。
     * @var unknown
     */
    public $duplicate_code;
    /**
     * 失败个数。
     * @var unknown
     */
    public $fail_code;
}

/**
 * 卡券检查结果
 * @author leeboo
 *
 */
class YDWXCardCheckCodeResponse extends YDWXResponse{
    /**
     * 用户openid
     * @var unknown
     */
    public $openid;
    /**
     * 卡券ID
     * @var unknown
     */
    public $card_id;
    /**
     * 起始使用时间
     * @var unknown
     */
    public $begin_time;
    /**
     * 结束时间
     * @var unknown
     */
    public $end_time;
    /**
     * 见YDWX_CARD_USER_STATUS_XX
     * @var unknown
     */
    public $user_card_status;
    /**
     * 是否可以核销，true为可以核销，false为不可核销
     * @var unknown
     */
    public $can_consume;
}

/**
 * 核销卡券返回结果
 * @author leeboo
 *
 */
class YDWXCardConsumeCodeResponse extends YDWXResponse{
    public $card_id;
    public $openid;
    
    function build($msg){
        parent::build($msg);
        $this->card_id = $this->card['card_id'];
    }
}

/**
 * 卡券查询返回结果
 * @author leeboo
 *
 */
class YDWXCardResponse extends YDWXResponse{
    /**
     * 
     * @var YDWXCardBase
     */
    public $card;
    public $use_limit;
    public $appid;
    public $id;
    public function build($msg){
        parent::build($msg);
        switch ($this->card['card_type']){
            case YDWXCardCash::CARD_TYPE:                   $this->card = new YDWXCardCash();break;
            case YDWXCardGeneralCoupon::CARD_TYPECARD_TYPE: $this->card = new YDWXCardGeneralCoupon();break;
            case YDWXCardGift::CARD_TYPE:                   $this->card = new YDWXCardGift();break;
            case YDWXCardDiscount::CARD_TYPE:               $this->card = new YDWXCardDiscount();break;
            case YDWXCardGroupon::CARD_TYPE:                
            default:                                        $this->card = new YDWXCardGroupon();break;
        }
        
        $type = strtolower($this->card['card_type']);
        $info = $this->card['card'][$type];
        
        $this->id = $info['base_info']['id'];
        $this->appid = $info['base_info']['appid'];
        $this->use_limit = $info['base_info']['use_limit'];
        
        foreach ($info['base_info'] as $name=>$value){
            if($name=="date_info"){
                foreach ($value as $subname=>$subvalue){
                    $subname = "date_info_{$subname}";
                    $this->card->$subname = $subvalue;
                }
            }else if($name=="sku"){
                foreach ($value as $subname=>$subvalue){
                    $subname = "sku_{$subname}";
                    $this->card->$subname = $subvalue;
                }
            }else{
                $this->card->$name = $value;
            }
        }
        unset($info['base_info']);
        foreach ($info as $name=>$value){
            $this->card->$name = $value;
        }
    }
}
class YDWXCardBatchgetResponse extends YDWXResponse{
    /**
     * 卡券ID列表。
     * @var unknown
     */
    public $card_id_list = array();
    /**
     * 该商户名下卡券ID总数。
     * @var unknown
     */
    public $total_num = array();
}

/**
 * 卡券统计结果
 * @author leeboo
 *
 */
class YDWXCardStatistic{
    /**
     * 日期信息
     * @var unknown
     */
    public $ref_date;
    /**
     * 浏览次数
     * @var unknown
     */
    public $view_cnt;
    /**
     * 浏览人数
     * @var unknown
     */
    public $view_user;
    /**
     * 领取次数
     * @var unknown
     */
    public $receive_cnt;
    /**
     * 领取人数
     * @var unknown
     */
    public $receive_user;
    /**
     * 使用次数
     * @var unknown
     */
    public $verify_cnt;
    /**
     * 使用人数
     * @var unknown
     */
    public $verify_user;
    /**
     * 转赠次数
     * @var unknown
     */
    public $given_cnt;
    /**
     * 转赠人数
     * @var unknown
     */
    public $given_user;
    /**
     * 过期次数
     * @var unknown
     */
    public $expire_cnt;
    /**
     * 过期人数
     * @var unknown
     */
    public $expire_user;
}
/**
 * 具体某个卡的统计
 * @author leeboo
 *
 */
class YDWXCardCardStatistic extends YDWXCardStatistic{
    /**
     * 卡券ID
     * @var unknown
     */
    public $card_id;
    /**
     * cardtype:0：折扣券，1：代金券，2：礼品券，3：优惠券，
     * 4：团购券（暂不支持拉取特殊票券类型数据，电影票、飞机票、会议门票、景区门票）
     * @var unknown
     */
    public $card_type;
    /**
     * 是否付费券。0为非付费券，1为付费券
     * @var unknown
     */
    public $is_pay;
    
    public function card_type(){
        switch($this->card_type){
            case 0: return YDWXCardDiscount::CARD_TYPE;
            case 1: return YDWXCardCash::CARD_TYPE;
            case 2: return YDWXCardGift::CARD_TYPE;
            case 3: return YDWXCardGeneralCoupon::CARD_TYPE;
            case 4: return YDWXCardGroupon::CARD_TYPE;
        }
    }
}
class YDWXMemberCardStatistic{
    /**
     * 日期信息
     * @var unknown
     */
    public $ref_date;
    /**
     * 浏览次数
     * @var unknown
     */
    public $view_cnt;
    /**
     * 浏览人数
     * @var unknown
     */
    public $view_user;
    /**
     * 领取次数
     * @var unknown
     */
    public $receive_cnt;
    /**
     * 领取人数
     * @var unknown
     */
    public $receive_user;
    /**
     * 使用次数
     * @var unknown
     */
    public $verify_cnt;
    /**
     * 使用人数
     * @var unknown
     */
    public $verify_user;
    /**
     * 激活人数
     * @var unknown
     */
    public $active_user;
    /**
     * 有效会员总人数
     * @var unknown
     */
    public $total_user;
    /**
     * 历史领取会员卡总人数
     * @var unknown
     */
    public $total_receive_user;
}

/**
 * 创建无公众号子商户请求数据对象
 * @author leeboo
 *
 */
class YDWXCardSubmerchantRequest extends YDWXRequest{
    /**
     *  子商户名称id 更新信息时传入
     * @var unknown
     */
    public $merchant_id;
    /**
     *  子商户名称（ 12 个汉字内） 该名称将在制券时填入,并显示在 卡券页面上
     * @var unknown
     */
    public $brand_name;
    /**
     * 子商户 logo ，可通过上传logo 接口获 取。 该 logo 将在制券时填入 并显示在卡券 页面上
     * @var unknown
     */
    public $logo_url;
    /**
     * 授权协议media  id，需 先调用 “新增 临时 素材 接口 “得到 ydwx_media_upload
     * @var unknown
     */
    public $protocol;
    /**
     * 授权协议结束时间,时间戳格式
     * @var unknown
     */
    public $end_time;
    /**
     * 子商户一级目录
     * @var unknown
     */
    public $primary_category_id;
    /**
     * 子商户二级目录
     * @var unknown
     */
    public $secondary_category_id;
    
    public function valid(){
        
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['end_time'] = intval($args['end_time']);
        $args['primary_category_id']    = intval($args['primary_category_id']);
        $args['secondary_category_id']  = intval($args['secondary_category_id']);
        return array("info"=>$args);
    }
}

/**
 * 创建无公众号子商户结果数据对象
 * @author leeboo
 *
 */
class YDWXCardSubmerchantResponse extends YDWXResponse{
    /**
     * 子商户id，对于一个母商户唯一
     * @var unknown
     */
    public $merchant_id;
    /**
     * 子商户创建时间
     * @var unknown
     */
    public $create_time;
    /**
     * 子商户更新时间
     * @var unknown
     */
    public $update_time;
    /**
     * 子商户状态，YDWX_CARD_MERCHANT_XX
     * @var unknown
     */
    public $status;
    /**
     *  子商户名称（ 12 个汉字内） 该名称将在制券时填入,并显示在 卡券页面上
     * @var unknown
     */
    public $brand_name;
    /**
     * 子商户 logo ，可通过上传logo 接口获 取。 该 logo 将在制券时填入 并显示在卡券 页面上
     * @var unknown
     */
    public $logo_url;
    /**
     * 授权协议media  id，需 先调用 “新增 临时 素材 接口 “得到 ydwx_media_upload
     * @var unknown
     */
    public $protocol;
    /**
     * 授权协议结束时间
     * @var unknown
     */
    public $end_time;
    /**
     * 子商户一级目录
     * @var unknown
     */
    public $primary_category_id;
    /**
     * 子商户二级目录
     * @var unknown
     */
    public $secondary_category_id;
    
    public function build($msg){
        parent::build($msg);
        if($this->isSuccess()){
            $this->merchant_id = $this->info['merchant_id'];
            $this->create_time = $this->info['create_time'];
            $this->update_time = $this->info['update_time'];
            $this->brand_name  = $this->info['brand_name'];
            $this->logo_url    = $this->info['logo_url'];
            $this->status      = $this->info['status'];
            $this->protocol    = $this->info['protocol'];
            $this->end_time    = $this->info['end_time'];
            $this->primary_category_id   = $this->info['primary_category_id'];
            $this->secondary_category_id = $this->info['secondary_category_id'];
        }
    }
}

/**
 * 卡券类目结果
 * @author leeboo
 *
 */
class YDWXCardApplyProtocol{
    public $category_id;
    public $category_name;
    public $need_qualification_stuffs;
    public $can_choose_prepaid_card;
    public $can_choose_payment_card;
    /**
     * YDWXCardApplyProtocol 组成的子目录数据
     * @var unknown
     */
    public $secondary_category;
}

/**
 * 批量查询商户结果数组
 * @author leeboo
 *
 */
class YDWXCardSubmerchantBatchGetResponse extends YDWXResponse{
    /**
     * YDWXCardSubmerchantResponse 数组
     * @var array
     */
    public $merchants;
    /**
     * 当母商户的子商户数量超过 100 时，可通过填写 begin_id 的值，
     * 从而多次拉取列表方式来 满足查询需求。具体的 方式是，
     * 将上一次调用得到返回中next_begin_id的值作为下一次 调用中的 begin_id 的值。
     * @var unknown
     */
    public $next_begin_id;
    public function build($msg){
        parent::build($msg);
        $this->merchants = array();
        
        if( ! $this->isSuccess())return;
        foreach ($msg->info_list as $info){
            $merchant = new YDWXCardSubmerchantResponse();
            foreach ($info as $name=>$value){
                $merchant->$name = $value;
            }
            $this->merchants[] = $merchant;
        }
        
    }
}

/**
 * 协助制券（有公众号模式）母商户资质申请数据
 * @author leeboo
 *
 */
class YDWXCardAgentQualificationRequest extends YDWXRequest{
    /**
     * 注册资本单位：分
     * @var unknown
     */
    public $register_capital;
    /**
     * 营业执照扫描件的media_id
     * @var unknown
     */
    public $business_license_media_id;
    /**
     * 税务登记证media_id
     * @var unknown
     */
    public $tax_registration_certificate_media_id;
    /**
     * 上个季度纳税清单扫描件media_id
     * @var unknown
     */
    public $last_quarter_tax_listing_media_id;
    
    public function valid(){
        
    }
    
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['register_capital'] = intval($args['register_capital']);
        return $args;
    }
}
/**
 * 有公众号子商户资质申请数据
 * @author leeboo
 *
 */
class YDWXCardMerchantQualificationRequest extends YDWXRequest{
    /**
     * 子商户公众号的 appid
     * @var unknown
     */
    public $appid;
    /**
     * 子商户的商户名，显示在卡券券面的商户
     * @var unknown
     */
    public $name;
    /**
     * 子商户的 logo，显示在卡券券面的商户 logo
     * @var unknown
     */
    public $logo_media_id;
    /**
     * 营业执照或个体工商户执照扫描件的 media_id
     * @var unknown
     */
    public $business_license_media_id;
    /**
     * 子商户与第三方签署的代理授权函的 media_id
     * @var unknown
     */
    public $agreement_file_media_id;
    /**
     * 一级类目 id
     * @var unknown
     */
    public $primary_category_id;
    /**
     * 二级类目 id
     * @var unknown
     */
    public $secondary_category_id;

    public function valid(){

    }

    protected function formatArgs(){
        $args = parent::formatArgs();
        return $args;
    }
}

/**
 * 有公众号子商户返回数据
 * @author leeboo
 *
 */
class YDWXCardMPMerchantResponse extends YDWXResponse{
    public $appid;
    public $name;
    public $primary_category_id;
    public $secondary_category_id;
    public $submit_time;
    public $result;
}

/**
 * 有公众号子商户查询列表返回数据
 * @author leeboo
 *
 */
class YDWXCardMPMerchantBatchGetResponse extends YDWXResponse{
    /**
     * YDWXCardMPMerchantResponse 数组
     * @var array
     */
    public $merchants;
    /**
     * 当母商户的子商户数量超过 20 时，可通过填写 begin_id 的值，
     * 从而多次拉取列表方式来 满足查询需求。具体的 方式是，
     * 将上一次调用得到返回中next_begin_id的值作为下一次 调用中的 begin_id 的值。
     * @var unknown
     */
    public $next_get;
    public function build($msg){
        parent::build($msg);
        $this->merchants = array();

        if( ! $this->isSuccess())return;
        foreach ($msg->list as $info){
            $merchant = new YDWXCardMPMerchantResponse();
            foreach ($info as $name=>$value){
                $merchant->$name = $value;
            }
            $this->merchants[] = $merchant;
        }

    }
}