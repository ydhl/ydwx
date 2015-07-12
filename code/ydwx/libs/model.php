<?php
/**
 * 菜单模型
 * @author leeboo
 *
 */
class Menu{
    const TYPE_CLICK = "click";
    const TYPE_VIEW  = "view";
    const TYPE_SCANCODE_PUSH = "scancode_push";
    const TYPE_SCANCODE_WAITMSG = "scancode_waitmsg";
    const TYPE_PIC_SYSPHOTO = "pic_sysphoto";
    const TYPE_PIC_PHOTO_OR_ALBUM = "pic_photo_or_album";
    const TYPE_PIC_WEIXIN = "pic_weixin";
    const TYPE_LOCATION_SELECT = "location_select";
    
    public $name;
    public $key;
    public $type;
    public $url;
    public $sub_button = array();
    
    public static function build(array $msg){
        $obj = new Menu();
       
        $obj->name = $msg['name'];
        $obj->type = $msg['type'];
        $obj->key  = $msg['key'];
        $obj->url  = $msg['url'];
        
        $obj->sub_button = array();
        
        foreach ($msg['sub_button'] as $subbtn){
            $obj->sub_button[] = Menu::build($subbtn);
        }
        return $obj;
    }
    
    public function toArray(){
        $array = array();
        if ($this->type) $array['type'] = $this->type;
        if ($this->name) $array['name'] = urlencode($this->name);
        if ($this->key)  $array['key'] = $this->key;
        if ($this->url)  $array['url'] = $this->url;
        if ($this->sub_button) {
            foreach ($this->sub_button as $button){
                $array['sub_button'][] = $button->toArray();
            }
        }
        return $array;
    }
}


/**
 * 微信消息封装基类,便于知道每种消息有什么内容
 */
class WXMsg{
    const ToUserName    = "ToUserName";
    const FromUserName  = "FromUserName";
    const CreateTime    = "CreateTime";
    /**
     * 消息类型,见MSG_TYPE_*
     * @var unknown
     */
    const MsgType   = "MsgType";

    const Content   = "Content";
    const MsgId     = "MsgId";
    const MediaId   = "MediaId";
    const PicUrl    = "PicUrl";
    
    /**
     * 语音格式，如amr，speex等
     * @var unknown
     */
    const Voice_Format       = "Format";
    const Video_ThumbMediaId = "ThumbMediaId";
    const Location_X = "Location_X";
    const Location_Y = "Location_Y";
    const Location_Scale = "Scale";
    const Location_Label = "Label";
    const Location_Poiname = "Poiname";
    const Title = "Title";
    const Description = "Description";
    const Url = "Url";
    
    const Event = "Event";
    const EventKey = "EventKey";
    /**
     * 有值表示通过二维码关注后微信会将带场景值关注事件推送给开发者
     * @var unknown
     */
    const Ticket    = "Ticket";
    const Latitude  = "Latitude";
    const Longitude = "Longitude";
    /**
     * 地理位置精度
     * @var unknown
     */
    const Precision = "Precision";

    
    const MSG_TYPE_EVENT = "event";
    const EVENT_CLICK = "CLICK";
    
    const ScanType = "ScanType";
    const ScanResult = "ScanResult";
    
    const SendPicCount = "Count";
    const SendPicMd5Sum = "PicMd5Sum";
    
    const MASSSendJobFinish_Status = "Status";
    const MASSSendJobFinish_TotalCount ="TotalCount";
    const MASSSendJobFinish_FilterCount ="FilterCount";
    const MASSSendJobFinish_SentCount ="SentCount";
    const MASSSendJobFinish_ErrorCount ="ErrorCount";
    
    
    const AuthAccess_token  = "access_token";
    const AuthExpires_in    = "expires_in";
    const AuthRefresh_token = "refresh_token";
    const AuthOpenid = "openid";
    const AuthScope = "scope";
    
    const PrePayAppId = "appid";
    const PrePayMCHId  = "mch_id";
    const PrePayDeviceInfo= "device_info";
    const PrePayNonceStr = "nonce_str";
    const PrePaySign = "sign";
    const PrePayResultCode = "result_code";
    const PrePayReturnCode = "return_code";
    const PrePayReturnMsg = "return_msg";
    const PrePayErrCode = "err_code";
    const PrePayErrCodeDes = "err_code_des";
    const PrePayTradeType = "trade_type";
    const PrePayPrepayId  = "prepay_id";
    const PrePayCodeUrl = "code_url";

    public $msg;
    public $rawData;
    
    public static function build($msg){
        $xml = simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA);
        $obj = new WXMsg();
        $obj->rawData = $msg;
        $obj->msg     = $xml;
        
        return $obj;
    }
    
    public function get($name){
        if(in_array(strtotime($name), 
                array(self::ScanType, self::ScanType))){
            return (string)$this->msg->ScanCodeInfo->$name;
        }
        if(strtotime($name) == self::SendPicCount){
            return (string)$this->msg->SendPicsInfo->$name;
        }
        if(strtotime($name) == self::SendPicMd5Sum){
            $array = array();
            foreach ($this->msg->SendPicsInfo->PicList as $info){
                $array[] = (string)$info->item->PicMd5Sum;
            }
            return $array;
        }
        if(in_array(strtotime($name),
                array(self::Location_X, self::Location_Y, self::Location_Scale, self::Location_Label, self::Location_Poiname))){
            return (string)$this->msg->SendLocationInfo->$name;
        }
        return (string)$this->msg->$name;
    }
    
    public function isPrepaySuccess(){
        return strcasecmp($this->get(WXMsg::PrePayReturnCode), "success")==0;
    }
    public function isPrepayResultSuccess(){
        return strcasecmp($this->get(WXMsg::PrePayResultCode), "success")==0;
    }
}