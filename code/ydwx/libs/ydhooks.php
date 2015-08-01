<?php

/**
 * 微信hook定义
 */
class WXHooks{
    /**
     * 记录log
     * @var unknown
     */
    const YDWX_LOG = "YDWX_LOG";
    const GET_ACCESS_TOKEN = "GET_ACCESS_TOKEN";
    const GET_JSAPI_TICKET = "GET_JSAPI_TICKET";
    /**
     * access token 刷新hook，参数是array(access_token=>"",expire_in=>"")
     * @var unknown
     */
    const ACCESS_TOKEN_REFRESH = "ACCESS_TOKEN_REFRESH";
    const JSAPI_TICKET_REFRESH = "JSAPI_TICKET_REFRESH";
    
    /**
     * 用户订阅事件，参数是SubscribeEventMsg
     * @var unknown
     */
    const EVENT_SUBSCRIBE           = "event_subscribe";
    /**
     * 用户取消订阅事件，参数是WXMsg
     * @var unknown
     */
    const EVENT_UNSUBSCRIBE         = "event_unsubscribe";
    /**
     * 菜单点击事件，参数是WXMsg
     * @var unknown
     */
    const EVENT_CLICK               = "event_click";
    /**
     * 菜单跳转事件，参数是WXMsg
     * @var unknown
     */
    const EVENT_VIEW                = "event_view";
    /**
     * 扫码推事件的事件推送，参数是WXMsg( MsgType,Event,EventKey,ScanType=qrcode,ScanResult)
     * @var unknown
     */
    const EVENT_SCANCODE_PUSH       = "event_scancode_push";
    /**
     * 扫码推事件且弹出“消息接收中”提示框的事件推送，参数是WXMsg( MsgType,Event,EventKey,ScanType=qrcode,ScanResult)
     * @var unknown
     */
    const EVENT_SCANCODE_WAITMSG    = "event_scancode_waitmsg";
    
    /**
     * 弹出系统拍照发图的事件推送，参数是WXMsg( SendPicCount SendPicMd5Sum )
     * @var unknown
     */
    const EVENT_PIC_SYSPHOTO    = "event_pic_sysphoto";
    
    /**
     * 弹出拍照或者相册发图的事件推送，参数是WXMsg( SendPicCount SendPicMd5Sum )
     * @var unknown
     */
    const EVENT_PIC_PHOTO_OR_ALBUM    = "event_pic_photo_or_album";
    
    /**
     * 弹出微信相册发图器的事件推送，参数是WXMsg( SendPicCount SendPicMd5Sum )
     * @var unknown
     */
    const EVENT_PIC_WEIXIN    = "event_pic_weixin";
    
    /**
     * 弹出地理位置选择器的事件推送，参数是WXMsg
     * @var unknown
     */
    const EVENT_LOCATION_SELECT    = "event_location_select";
    
    /**
     * 群发推送结果 Status,TotalCount,FilterCount,SentCount,ErrorCount
     * @var unknown
     */
    const EVENT_MASSSENDJOBFINISH  = "event_masssendjobfinish";
    
    /**
     * 文本消息，WXMsg.Content为内容
     * @var unknown
     */
    const TEXT    = "text";

    /**
     * 图片消息，参数是WXMsg
     * @var unknown
     */
    const IMAGE    = "image";
    /**
     * 语音消息，参数是WXMsg
     * @var unknown
     */
    const VOICE    = "voice";    
    
    /**
     * 视频消息，参数是WXMsg
     * @var unknown
     */
    const VIDEO    = "video";
    /**
     * 小视频消息，参数是WXMsg
     * @var unknown
     */
    const SHORTVIDEO = "shortvideo";
    /**
     * 地理位置消息，参数是WXMsg
     * @var unknown
     */
    const LOCATION = "location";
    
    /**
     * 链接消息，参数是WXMsg
     * @var unknown
     */
    const LINK = "link";
    
    /**
     * 用户取消授权
     * @var unknown
     */
    const AUTH_CANCEL  = "auth_cancel";
    const AUTH_FAIL    = "auth_fail";
    /**
     * 微信app内 web应用登录成功
     * 参数 array 为用户的信息
     * @var unknown
     */
    const AUTH_INAPP_SUCCESS    = "AUTH_INAPP_SUCCESS";
    /**
     * web应用登录成功
     * 参数 array 为用户的信息
     * @var unknown
     */
    const AUTH_WEB_SUCCESS      = "AUTH_WEB_SUCCESS";
    /**
     * 微信app内 企业web应用登录成功
     * 参数 array 为用户的信息 array(UserId=>"该用户在企业号后台的账号","OpenId"=>"非企业成员时返回openid", DeviceId=>"手机设备号") 注意大小写
     * @var unknown
     */
    const AUTH_CROP_SUCCESS     = "AUTH_CROP_SUCCESS";
    
    /**
     * 预支付出现异常
     * @var unknown
     */
    const PREPARE_PAY_EXCEPTION = "PREPARE_PAY_EXCEPTION";
    /**
     * 预支付失败
     * @var unknown
     */
    const PREPARE_PAY_FAIL      = "PREPARE_PAY_FAIL";
    /**
     * 预支付成功
     * @var unknown
     */
    const PREPARE_PAY_SUCCESS   = "PREPARE_PAY_SUCCESS";
    
    /**
     * 微信支付通知成功，参数为WXMsg
     * 返回的参数https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_7
     * @var unknown
     */
    const PAY_NOTIFY_SUCCESS    = "PAY_NOTIFY_SUCCESS";
    /**
     * 微信支付通知失败
     * @var unknown
     */
    const PAY_NOTIFY_ERROR      = "PAY_NOTIFY_ERROR";
    
    /**
     * 微信扫码支付通知成功
     * @var unknown
     */
    const QRCODE_PAY_NOTIFY_SUCCESS    = "QRCODE_PAY_NOTIFY_SUCCESS";
    /**
     * 微信扫码支付通知失败
     * @var unknown
     */
    const QRCODE_PAY_NOTIFY_ERROR      = "QRCODE_PAY_NOTIFY_ERROR";
}

/**
 * 该文件为系统提供hook机制
 * @author liizii
 * @since 2009-9-1
 */

final class YDHook {
    private static $listeners = array ();
    /**
     * 增加hook
     */
    public static function add_hook($event, $func_name, $object = null) {
        self::$listeners [$event] [] = array (
                "function" => $func_name,
                "object" => $object 
        );
    }
    
    public static function do_hook($filter_name, $data=array()) {
        if (! self::has_hook ( $filter_name ))
            return $data;
        foreach ( self::$listeners [$filter_name] as $listeners ) {
            if (is_object ( $listeners ['object'] )) {
                $data = call_user_func ( array($listeners ['object'], $listeners ['function']), $data);
            } else {
                $data = call_user_func ( $listeners ['function'], $data );
            }
        }
        return $data;
    }
    
    public static function has_hook($filter_name) {
        return @self::$listeners [$filter_name];
    }
    
    public static function allhooks(){
        return self::$listeners;
    }
    
    public static function include_files($dir){
        if( ! file_exists($dir) )return;
        foreach(glob($dir."/*") as $file){
            if (is_dir($file)) {
                self::include_hooks($file);
            }else if(is_file($file)){
                require_once $file;
            }
        }
    }
}
?>
