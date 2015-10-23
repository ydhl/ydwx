<?php
/**
 * 微信事件通知消息，XML结构
 */
class YDWXEvent extends YDWXResponse{
    /**
     * 消息类型,见MSG_TYPE_*
     * @var unknown
     */
    public $MsgType;
    /**
     * 事件类型，对于接受到的普通消息，为空
     * @var unknown
     */
    public $Event;
    public $ToUserName;
    public $CreateTime;
    public $FromUserName;
    /**
     * 对于第三方平台，该字段标明通知消息是那个托管的公众号的
     */
    public $APPID;
    protected $xml;
    public function build($msg){
        $this->xml = simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA);
        if( ! $this->xml){
            $this->errcode = -1;
            $this->errmsg = "无法解析xml";
            return;
        }
        foreach((array)$this->xml as $name=>$value){
            $this->$name = $value;
        }
    }
    /**
     * 
     * @return YDWXEvent
     */
    public static function CreateEventMsg($msg){
        $obj =  new YDWXEvent($msg);
        if($obj->Event){
            if($obj->Event=="user_consume_card"){//同一个事件的两种作用
                if($obj->TransId){
                    $clsname = "YDWXEventUserPaidByCard";
                }else{
                    $clsname = "YDWXEventUserConsumeCard";
                }
            }else{
                $clsname  = "YDWX".ucfirst((strtolower($obj->MsgType))).ucfirst(strtolower($obj->Event));
            }
        }else if($obj->MsgType){
            $clsname = "YDWXEventMsg".ucfirst(strtolower($obj->MsgType));
        }else if($obj->InfoType){
            $clsname = "YDWXEvent".ucfirst(strtolower($obj->InfoType));
        }else{
            $clsname = "YDWXEventUnknow";
        }
        return new $clsname($msg);
    }
    /**
     * 返回处理自己的hook 名称，默认以MsgType_Event作为hook name
     * 没有Event就以EVENT_MSG_MsgType作为hook name
     * @return string
     */
    public function HookName(){
        if($this->Event){
            if($obj->Event=="user_consume_card"){//同一个事件的两种作用
                if($obj->TransId){
                    $hookname = "EVENT_USER_PAID_BY_CARD";
                }else{
                    $hookname = "EVENT_USER_CONSUME_CARD";
                }
            }else{
                $hookname = strtoupper($this->MsgType."_".$this->Event);
            }
        }else if($this->MsgType){
            $hookname = "EVENT_MSG_".strtoupper($this->MsgType);
        }else if($this->InfoType){
            $hookname = "EVENT_".strtoupper($this->InfoType);
        }else{
            $hookname = "EVENT_UNKONW";
        }
        return constant("YDWXHook::{$hookname}");
    }
}
/**
 * 用户在进入会员卡时，微信会把这个事件推送。
 * 需要开发者在创建会员卡时填入need_push_on_view	字段并设置为true。开发者须综合考虑领卡人数和服务器压力，决定是否接收该事件。
 * @author leeboo
 *
 */
class YDWXEventUser_view_card extends YDWXEvent{
    public $CardId;
    /**
     * code序列号。自定义code及非自定义code的卡券被领取后都支持事件推送。
     * @var unknown
     */
    public $UserCardCode;
}
/**
 * 领取卡券事件消息
 * @author leeboo
 *
 */
class YDWXEventUser_get_card extends YDWXEvent{
    /**
     * 卡券ID。
     * @var unknown
     */
    public $CardId;
   
    /**
     * 卡券Code码。
     * @var unknown
     */
    public $UserCardCode;
    /**
     * 投放二维码时设置,领取场景值，用于领取渠道数据统计。可在生成二维码接口及添加JS API接口中自定义该字段的整型值。
     * @var unknown
     */
    public $OuterId;
    /**
     * 是否为转赠，1代表是，0代表否。
     * @var unknown
     */
    public $IsGiveByFriend;
    /**
     * 赠送方账号（一个OpenID），"IsGiveByFriend”为1时填写该参数。
     * @var unknown
     */
    public $FriendUserName;
    /**
     * 转赠前的code序列号。
     * @var unknown
     */
    public $OldUserCardCode;
}
/**
 * 生成的卡券通过审核Event
 * @author leeboo
 *
 */
class YDWXEventCard_pass_check extends YDWXEvent{
    public $CardId;
}
/**
 * 生成的卡券不通过审核Event
 * @author leeboo
 *
 */
class YDWXEventCard_not_pass_check extends YDWXEvent{
    public $CardId;
}
/**
 * 红包绑定用户事件通知 FromUserName	红包绑定用户（一个OpenID）。
 * 注：红包绑定用户不等同于用户领取红包。用户进入红包页面后，有可能不拆红包，但该红包ticket已被绑定，不能再被其他用户绑定，过期后会退回商户财付通账户
 * @author leeboo
 *
 */
class YDWXEventShakearoundlotterybind extends YDWXEvent{
    /**
     * 红包活动id
     * @var unknown
     */
    public $LotteryId;
    /**
     * 红包ticket
     * @var unknown
     */
    public $Ticket;
    /**
     * 红包金额
     * @var unknown
     */
    public $Money;
    /**
     * 红包绑定时间
     * @var unknown
     */
    public $BindTime;
}
class YDWXEventPoi_check_notify extends YDWXEvent{
    /**
     * 商户自己内部ID，即字段中的sid
     * @var unknown
     */
    public $UniqId;
    /**
     * 微信的门店ID，微信内门店唯一标示ID
     * @var unknown
     */
    public $PoiId;
    /**
     * 审核结果，成功succ 或失败fail
     * @var unknown
     */
    public $Result;
    /**
     * 成功的通知信息，或审核失败的驳回理由
     * @var unknown
     */
    public $Msg;
}
/**
 * 用户在卡券里点击查看公众号进入会话时（需要用户已经关注公众号），微信推送事件。
 * 开发者可识别从卡券进入公众号的用户身份
 * @author leeboo
 *
 */
class YDWXEventUser_enter_session_from_card extends YDWXEvent{
    public $CardId;
    /**
     * code序列号。自定义code及非自定义code的卡券被领取后都支持事件推送。
     * @var unknown
     */
    public $UserCardCode;
}
/**
 * 用户删除会员卡事件推送
 * @author leeboo
 *
 */
class YDWXEventUser_del_card extends YDWXEvent{
    public $CardId;
    /**
     * code序列号。自定义code及非自定义code的卡券被领取后都支持事件推送。
     * @var unknown
     */
    public $UserCardCode;
}
class YDWXEventConsumeCardBase extends YDWXEvent{
    /**
     * 卡券ID。
     * @var unknown
     */
    public $CardId;
    /**
     * 卡券Code码。
     * @var unknown
     */
    public $UserCardCode;
    /**
     * 核销来源。支持开发者统计API核销（YDWX_CARD_Consume_FROM_API）、公众平台核销（YDWX_CARD_Consume_FROM_MP）、
     * 卡券商户助手核销（YDWX_CARD_Consume_FROM_MOBILE_HELPER）（核销员微信号）
     * @var unknown
     */
    public $ConsumeSource;
}
/**
 * 核销事件推送
 * @author leeboo
 *
 */
class YDWXEventUserConsumeCard extends YDWXEventConsumeCardBase{
    /**
     * 门店名称，当前卡券核销的门店名称（只有通过卡券商户助手和买单核销时才会出现）
     * @var unknown
     */
    public $LocationName;
    /**
     * 核销该卡券核销员的openid（只有通过卡券商户助手核销时才会出现）
     * @var unknown
     */
    public $StaffOpenId;
}
/**
 * 微信卡券买单事件通知
 * @author leeboo
 *
 */
class YDWXEventUserPaidByCard extends YDWXEventConsumeCardBase{
    /**
     * 微信支付交易订单号
     * @var unknown
     */
    public $OutTradeNo;
    /**
     * 商户订单号
     * @var unknown
     */
    public $TransId;
}
/**
 * 第三方平台的ticket刷新通知
 * @author leeboo
 *
 */
class YDWXEventComponent_verify_ticket extends YDWXEvent{
    public $AppId;
    public $CreateTime;
    public $InfoType;
    public $ComponentVerifyTicket;
}

/**
 * 微信公众号取消第三方授权的通知
 * @author leeboo
 *
 */
class YDWXEventUnauthorized extends YDWXEvent{
    public $AppId;
    public $CreateTime;
    public $InfoType;
    /**
     * 取消授权的公众号
     */
    public $AuthorizerAppid;
}

/**
 * 未知微信消息
 * @author leeboo
 *
 */
class YDWXEventUnknow extends YDWXEvent{
    
}

/**
 * 摇一摇事件通知
 * 推送内容包含摇一摇时“周边”页卡展示出来的页面所对应的设备信息，以及附近最多五个属于该公众账号的设备的信息
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/4/f70a51e8d80631751514778070e2c2b0.html
 */
class YDWXEventShakearoundusershake extends YDWXEvent{
    /**
     * YDWXZBChooseDevice
     * @var YDWXZBChooseDevice
     */
    public $ChosenBeacon;
    /**
     * YDWXZBChooseDevice 组成的数组
     * @var array
     */
    public $AroundBeacons = array();
    
    public function build($msg){
        parent::build($msg);
        if( $this->xml){
            $device = new YDWXZBChosenBeacon();
            
            $beacon = (array)$this->ChosenBeacon;
            $device->uuid  = $beacon['Uuid'];
            $device->major = $beacon['Major'];
            $device->minor = $beacon['Minor'];
            $device->distance = $beacon['Distance'];
            $this->ChosenBeacon = $device;
            
            
            $beacons = (array)$this->AroundBeacons;
            $array = array();
            
            foreach($beacons['AroundBeacon'] as $value){
                $device = new YDWXZBChosenBeacon();
                $value = (array)$value;
            
                $device->uuid  = $value['Uuid'];
                $device->major = $value['Major'];
                $device->minor = $value['Minor'];
                $device->distance = $value['Distance'];
                $array[] = $device;
            }
            $this->AroundBeacons = $array;
        }
    }
}

/**
 * 普通消息事件推送基类
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/10/79502792eef98d6e0c6e1739da387346.html
 */
class YDWXEventMsg extends YDWXEvent{
    public $MsgId;
}
/**
 * 文字消息
 * @author leeboo
 *
 */
class YDWXEventMsgText extends YDWXEventMsg{
    public $Content;
}
/**
 * 图片消息
 * @author leeboo
 *
 */
class YDWXEventMsgImage extends YDWXEventMsg{
    /**
     * 图片链接
     * @var unknown
     */
    public $PicUrl;
    /**
     * 图片消息媒体id，可以调用多媒体文件下载接口拉取数据。
     * @var unknown
     */
    public $MediaId;
}
/**
 * 音频消息
 * @author leeboo
 *
 */
class YDWXEventMsgVoice extends YDWXEventMsg{
    /**
     * 格式
     * @var unknown
     */
    public $Format;
    public $MediaId;
    /**
     * 识别出的文字内容，如果公众号没有开启，它为空
     * @var unknown
     */
    public $Recognition;
}
/**
 * 连接消息
 * @author leeboo
 *
 */
class YDWXEventMsgLink extends YDWXEventMsg{
    public $Title;
    public $Description;
    public $Url;
}
/**
 * 地址消息
 * @author leeboo
 *
 */
class YDWXEventMsgLocation extends YDWXEventMsg{
    public $Location_X;
    public $Location_Y;
    public $Scale;
    public $Label;
}
/**
 * 视频消息
 * @author leeboo
 *
 */
class YDWXEventMsgVideo extends YDWXEventMsg{
    public $MediaId;
    public $ThumbMediaId;
}
/**
 * 小视频消息
 * @author leeboo
 *
 */
class YDWXEventMsgShortVideo extends YDWXEventMsgVideo{}
class YDWXEventUnsubscribe extends YDWXEvent{}
/**
 * 用户关注事件，有两张情况
 * 1.扫描二维码关注推送的消息，这是EventKey与Ticket有值
 * 2.正常关注
 * @author leeboo
 *
 */
class YDWXEventSubscribe extends YDWXEvent{
    /**
     * 事件KEY值，qrscene_为前缀，后面为二维码的参数值
     * @var unknown
     */
    public $EventKey;
    /**
     * 二维码的ticket，可用来换取二维码图片
     * @var unknown
     */
    public $Ticket;
    public function HookName(){
        if($this->EventKey) return YDWXHook::EVENT_SCAN_SUBSCRIBE;
        return parent::HookName();
    }
}

/**
 * 扫描带参数二维码事件
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/2/5baf56ce4947d35003b86a9805634b1e.html#.E6.89.AB.E6.8F.8F.E5.B8.A6.E5.8F.82.E6.95.B0.E4.BA.8C.E7.BB.B4.E7.A0.81.E4.BA.8B.E4.BB.B6
 */
class YDWXEventScan extends YDWXEvent{
    /**
     * 事件KEY值，是一个32位无符号整数，即创建二维码时的二维码scene_id
     * @var unknown
     */
    public $EventKey;
    /**
     * 二维码的ticket，可用来换取二维码图片
     * @var unknown
     */
    public $Ticket;
}
/**
 * 上报地理位置事件
 * 用户同意上报地理位置后，每次进入公众号会话时，都会在进入时上报地理位置，或在进入会话后每5秒上报一次地理位置
 * 
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/2/5baf56ce4947d35003b86a9805634b1e.html#.E4.B8.8A.E6.8A.A5.E5.9C.B0.E7.90.86.E4.BD.8D.E7.BD.AE.E4.BA.8B.E4.BB.B6
 *
 */
class YDWXEventLocation extends YDWXEvent{
    /**
     * 纬度
     * @var unknown
     */
    public $Latitude;
    /**
     * 经度
     * @var unknown
     */
    public $Longitude;
    /**
     * 精度
     */
    public $Precision;
}

/**
 * 点击菜单拉取消息时的事件推送
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/2/5baf56ce4947d35003b86a9805634b1e.html#.E8.87.AA.E5.AE.9A.E4.B9.89.E8.8F.9C.E5.8D.95.E4.BA.8B.E4.BB.B6
 */
class YDWXEventClick extends YDWXEvent{
    /**
     * 事件KEY值，与自定义菜单接口中KEY值对应
     * @var unknown
     */
    public $EventKey;
}
/**
 * 点击菜单跳转链接时的事件推送
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/2/5baf56ce4947d35003b86a9805634b1e.html#.E8.87.AA.E5.AE.9A.E4.B9.89.E8.8F.9C.E5.8D.95.E4.BA.8B.E4.BB.B6
 */
class YDWXEventView extends YDWXEvent{
    /**
     * 事件KEY值，设置的跳转URL
     * @var unknown
     */
    public $EventKey;
}
/**
 * 扫码推事件的事件推送
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/9/981d772286d10d153a3dc4286c1ee5b5.html#scancode_push.EF.BC.9A.E6.89.AB.E7.A0.81.E6.8E.A8.E4.BA.8B.E4.BB.B6.E7.9A.84.E4.BA.8B.E4.BB.B6.E6.8E.A8.E9.80.81
 */
class YDWXEventScancode_push extends YDWXEvent{
    /**
     * 事件KEY值，由开发者在创建菜单时设定
     * @var unknown
     */
    public $EventKey;
    /**
     * 扫描类型，一般是qrcode
     * @var unknown
     */
    public $ScanType;
    /**
     * 扫描结果，即二维码对应的字符串信息
     * @var unknown
     */
    public $ScanResult;
    private $ScanCodeInfo;
    public function build($msg){
        parent::build($msg);
        if( $this->xml ){
            $this->ScanType   = $this->ScanCodeInfo->ScanType;
            $this->ScanResult = $this->ScanCodeInfo->ScanResult;
        }
    }
}

/**
 * 扫码推事件且弹出“消息接收中”提示框的事件推送
 * @author leeboo
 *
 */
class YDWXEventScancode_waitmsg extends YDWXEventScancode_push{}

/**
 * 弹出系统拍照发图的事件推送
 * @author leeboo
 *
 */
class YDWXEventPic_sysphoto extends YDWXEventScancode_push{
    /**
     * 事件KEY值，由开发者在创建菜单时设定
     * @var unknown
     */
    public $EventKey;
    private $SendPicsInfo;
    /**
     * 发送的图片数量
     * @var unknown
     */
    public $Count;
    /**
     * 图片组数，格式array(array(PicMd5Sum=>""),array(PicMd5Sum=>""))
     * PicMd5Sum 图片的MD5值，开发者若需要，可用于验证接收到图片
     * @var unknown
     */
    public $PicList;
    public function build($msg){
        parent::build($msg);
        if( $this->xml ){
            $this->Count      = $this->SendPicsInfo->Count;
            $array = array();
            foreach ($this->SendPicsInfo->PicList->item as $value){
                $array[] = (array)$value;
            }
            $this->PicList    = $array;
        }
    }
}
/**
 * 弹出拍照或者相册发图的事件推送
 * @author leeboo
 *
 */
class YDWXEventPic_photo_or_album extends YDWXEventPic_sysphoto{}
/**
 * 弹出微信相册发图器的事件推送
 * @author leeboo
 *
 */
class YDWXEventPic_weixin extends YDWXEventPic_sysphoto{}
class YDWXEventLocation_select extends YDWXEvent{
    private $SendLocationInfo;
    /**
     * 事件KEY值，由开发者在创建菜单时设定
     * @var unknown
     */
    public $EventKey;
    public $Location_X;
    public $Location_Y;
    /**
     * 精度，可理解为精度或者比例尺、越精细的话 scale越高
     * @var unknown
     */
    public $Scale;
    /**
     * 地理位置的字符串信息
     * @var unknown
     */
    public $Label;
    /**
     * 朋友圈POI的名字，可能为空
     * @var unknown
     */
    public $Poiname;
}

/**
 * 群发事件推送群发结果
 * 
 * 由于群发任务提交后，群发任务可能在一定时间后才完成，因此，群发接口调用时，仅会给出群发任务是否提交成功的提示，若群发任务提交成功，则在群发任务结束时，会向开发者在公众平台填写的开发者URL（callback URL）推送事件。
 * 需要注意，由于群发任务彻底完成需要较长时间，将会在群发任务即将完成的时候，就推送群发结果，此时的推送人数数据将会与实际情形存在一定误差
 * @author leeboo
 *
 */
class YDWXEventMASSSENDJOBFINISH extends YDWXEvent{
    const STATUS_SUCCESS = "send success";
    const STATUS_FAIL    = "send fail";
    /**
     * 涉嫌广告
     */
    const ERR_10001      = "err(10001)";
    /**
     * 涉嫌政治
     */
    const ERR_20001      = "err(20001)";
    /**
     * 涉嫌色情
     */
    const ERR_20002      = "err(20002)";
    /**
     * 涉嫌社会
     */
    const ERR_20004      = "err(20004)";
    /**
     * 涉嫌违法犯罪
     */
    const ERR_20006      = "err(20006)";
    /**
     * 涉嫌欺诈
     */
    const ERR_20008      = "err(20008)";
    /**
     * 涉嫌版权
     */
    const ERR_20013      = "err(20013)";
    /**
     * 涉嫌其他
     */
    const ERR_21000      = "err(21000)";
    /**
     * 涉嫌互推(互相宣传)
     */
    const ERR_22000      = "err(22000)";
    
    /**
     * 群发的消息ID
     * @var unknown
     */
    public $MsgID;
    /**
     * 群发的结果，为“send success”或“send fail”或“err(num)”。
     * 但send success时，也有可能因用户拒收公众号的消息、系统错误等原因造成少量用户接收失败。
     * err(num)是审核失败的具体原因，可能的情况如下：
     * err(10001), //涉嫌广告 err(20001), //涉嫌政治 err(20004),  //涉嫌社会 
     * err(20002), //涉嫌色情 err(20006), //涉嫌违法犯罪 err(20008), //涉嫌欺诈 
     * err(20013), //涉嫌版权 err(22000), //涉嫌互推(互相宣传) err(21000), //涉嫌其他
     * @var unknown
     */
    public $Status;
    /**
     * group_id下粉丝数；或者openid_list中的粉丝数
     * @var unknown
     */
    public $TotalCount;
    /**
     * 过滤（过滤是指特定地区、性别的过滤、用户设置拒收的过滤，用户接收已超4条的过滤）后，
     * 准备发送的粉丝数，原则上，FilterCount = SentCount + ErrorCount
     * @var unknown
     */
    public $FilterCount;
    /**
     * 发送成功的粉丝数
     * @var unknown
     */
    public $SendCount;
    /**
     * 发送失败的粉丝数
     * @var unknown
     */
    public $ErrorCount;
}
class YDWXEventTEMPLATESENDJOBFINISH extends YDWXEvent{
    /**
     * 发送成功
     * @var unknown
     */
    const STATUS_SUCCESS = "success";
    /**
     * 用户拒绝
     * @var unknown
     */
    const STATUS_USER_BLOCK = "failed:user block";
    /**
     * 发送失败
     * @var unknown
     */
    const STATUS_SYSTEM_FAILED = "failed: system failed";
    /**
     * 群发的消息ID
     * @var unknown
     */
    public $MsgID;
    /**
     * 发送结果
     * 
     */
    public $Status;
} 