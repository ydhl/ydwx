<?php

/**
 * 微信hook定义
 *
 * 该文件为系统提供hook机制
 * @author leeboo
 * @since 2009-9-1
 */

final class YDWXHook {
    /**
     * 记录log, 参数为string
     * @var unknown
     */
    const YDWX_LOG = "YDWX_LOG";
    /**
     * 无参数，返回access token
     * @var unknown
     */
    const GET_ACCESS_TOKEN = "GET_ACCESS_TOKEN";
    /**
     * 无参数，返回企业号access token
     * @var unknown
     */
    const GET_QY_ACCESS_TOKEN = "GET_QY_ACCESS_TOKEN";
    /**
     * 无参数，返回第三方平台的access token
     * @var unknown
     */
    const GET_AGENT_ACCESS_TOKEN = "GET_AGENT_ACCESS_TOKEN";
    /**
     * 获取托管的公众号的acess token，参数是appid
     * @var unknown
     */
    const GET_HOST_ACCESS_TOKEN = "GET_HOST_ACCESS_TOKEN";
    /**
     * 获取托管的公众号的JSAPI_TICKET，参数是appid
     * @var unknown
     */
    const GET_HOST_JSAPI_TICKET = "GET_HOST_JSAPI_TICKET";
    /**
     * 获取托管的公众号的CARD_JSAPI_TICKET，参数是appid
     * @var unknown
     */
    const GET_HOST_CARD_JSAPI_TICKET= "GET_HOST_CARD_JSAPI_TICKET";
    /**
     * 第三方平台取得授权公众号的mch key, 参数是app id
     * @var unknown
     */
    const GET_HOST_MCH_KEY = "GET_HOST_MCH_KEY";
    /**
     * 第三方平台取得授权公众号的mch id, 参数是app id
     * @var unknown
     */
    const GET_HOST_MCH_ID  = "GET_HOST_MCH_ID";
    /**
     * 第三方平台取得授权公众号证书pem格式的绝对路径, 参数是app id
     * @var unknown
     */
    const GET_HOST_APICLIENT_CERT_PATH = "GET_HOST_APICLIENT_CERT_PATH";
    /**
     * 第三方平台取得授权公众号证书密钥pem格式的绝对路径, 参数是app id
     * @var unknown
     */
    const GET_HOST_APICLIENT_KEY_PATH  = "GET_HOST_APICLIENT_KEY_PATH";
    /**
     * 第三方平台取得授权公众号CA证书pem格式的绝对路径, 参数是app id
     * @var unknown
     */
    const GET_HOST_ROOT_CA  = "GET_HOST_ROOT_CA";
    /**
     * 无参数，返回jsapi ticket
     * @var unknown
     */
    const GET_JSAPI_TICKET = "GET_JSAPI_TICKET";
    /**
     * 无参数，返回企业号jsapi ticket
     * @var unknown
     */
    const GET_QY_JSAPI_TICKET = "GET_QY_JSAPI_TICKET";
    /**
     * 刷新公众号的token 参数YDWXAccessTokenResponse
     * @var unknown
     */
    const REFRESH_ACCESS_TOKEN = "REFRESH_ACCESS_TOKEN";
    /**
     * 刷新企业号的token 参数YDWXAccessTokenResponse
     * @var unknown
     */
    const REFRESH_QY_ACCESS_TOKEN = "REFRESH_QY_ACCESS_TOKEN";
    /**
     * 刷新公众号的js ticket 参数 YDWXJsapiTicketResponse
     * @var unknown
     */
    const REFRESH_JSAPI_TICKET = "REFRESH_JSAPI_TICKET";
    /**
     * 刷新企业号的js ticket 参数 YDWXJsapiTicketResponse
     * @var unknown
     */
    const REFRESH_QY_JSAPI_TICKET = "REFRESH_QY_JSAPI_TICKET";
    /**
     * 刷新公众号微信卡券 js ticket 参数 YDWXJsapiTicketResponse
     * @var unknown
     */
    const REFRESH_CARD_JSAPI_TICKET = "REFRESH_CARD_JSAPI_TICKET";
    /**
     * 刷新企业号微信卡券 js ticket 参数 YDWXJsapiTicketResponse
     * @var unknown
     */
    const REFRESH_QY_CARD_JSAPI_TICKET = "REFRESH_QY_CARD_JSAPI_TICKET";
    /**
     * 无参数，返回微信卡券用的jsapi ticket
     * @var unknown
     */
    const GET_CARD_JSAPI_TICKET = "GET_CARD_JSAPI_TICKET";
    
    /**
     * 无参数，返回企业号微信卡券用的jsapi ticket
     * @var unknown
     */
    const GET_QY_CARD_JSAPI_TICKET = "GET_QY_CARD_JSAPI_TICKET";

    /**
     * 刷新托管平台的token 参数 YDWXAccessTokenResponse
     * @var unknown
     */
    const REFRESH_AGENT_ACCESS_TOKEN = "REFRESH_AGENT_ACCESS_TOKEN";

    /**
     * 无参数，返回托管平台的ticket（微信推送过来的，通过YDWXHook::EVENT_COMPONENT_VERIFY_TICKET得到）
     * @var unknown
     */
    const GET_VERIFY_TICKET = "GET_VERIFY_TICKET";
    
    /**
     * 用户取消授权, hook 无参数
     * @var unknown
     */
    const AUTH_CANCEL  = "auth_cancel";
    /**
     * hook 参数YDWXAuthFailResponse
     * @var unknown
     */
    const AUTH_FAIL    = "auth_fail";
    /**
     * 公众号授权第三方平台成功，hook参数是是数组，array(YDWXAgentAuthInfo, YDWXAgentAuthUser)
     * @var unknown
     */
    const AUTH_AGENT_SUCCESS   = "AUTH_AGENT_SUCCESS"; 
    /**
     * 微信app内 web应用登录成功
     * 参数 YDWXOAuthUser
     * @var unknown
     */
    const AUTH_INAPP_SUCCESS    = "AUTH_INAPP_SUCCESS";
    /**
     * web应用登录成功
     * 参数 YDWXOAuthUser
     * @var unknown
     */
    const AUTH_WEB_SUCCESS      = "AUTH_WEB_SUCCESS";
    /**
     * 微信app内 企业web应用登录成功
     * 参数 YDWXOAuthCropUser array 为用户的信息 array(UserId=>"该用户在企业号后台的账号","OpenId"=>"非企业成员时返回openid", DeviceId=>"手机设备号") 注意大小写
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
     * 微信支付通知
     * hook参数 YDWXPaiedNotifyResponse 
     * https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_7
     * 处理hook的函数需要返回bool值，true表示通知已经处理了，微信不用在通知了
     * @var unknown
     */
    const PAY_NOTIFY_SUCCESS    = "PAY_NOTIFY_SUCCESS";
    /**
     * 微信支付通知失败
     * 返回的参数YDWXPaiedNotifyResponse 
     * @var unknown
     */
    const PAY_NOTIFY_ERROR      = "PAY_NOTIFY_ERROR";
    
    /**
     * 微信扫码支付通知成功
     * hook参数YDWXPayingNotifyResponse, hook 函数根据接收的数据在自己的系统中生成订单.
     * 参数中有被扫描产品的product id,并构建YDWXPayUnifiedOrderRequest并返回
     * 如果出现错误抛出异常，
     * @var unknown
     */
    const QRCODE_PAY_NOTIFY_SUCCESS    = "QRCODE_PAY_NOTIFY_SUCCESS";
    

    /**
     * 微信推送来文本消息，YDWXEventMsgText
     * @var unknown
     */
    const EVENT_MSG_TEXT    = "text";
    
    /**
     * 微信推送来图片消息，参数是YDWXEventMsgImage
     * @var unknown
     */
    const EVENT_MSG_IMAGE    = "image";
    /**
     * 微信推送来语音消息，参数是YDWXEventMsgVoice
     * @var unknown
     */
    const EVENT_MSG_VOICE    = "voice";
    
    /**
     * 微信推送来视频消息，参数是YDWXEventMsgVideo
     * @var unknown
     */
    const EVENT_MSG_VIDEO    = "video";
    /**
     * 微信推送来小视频消息，参数是YDWXEventMsgShortVideo
     * @var unknown
     */
    const EVENT_MSG_SHORTVIDEO = "shortvideo";
    /**
     * 微信推送来地理位置消息，参数是YDWXEventMsgLocation
     * @var unknown
     */
    const EVENT_MSG_LOCATION = "location";
    
    /**
     * 微信推送来链接消息，参数是YDWXEventMsgLink
     * @var unknown
     */
    const EVENT_MSG_LINK = "link";
    /**
     * 用户订阅事件，参数是YDWXEventSubscribe
     * @var unknown
     */
    const EVENT_SUBSCRIBE           = "event_subscribe";
    /**
     * 用户扫描二维码后进行订阅事件 参数YDWXEventSubscribe
     * @var unknown
     */
    const EVENT_SCAN_SUBSCRIBE           = "event_scan_subscribe";
    /**
     * 用户取消订阅事件，参数是YDWXEventUnsubscribe
     * @var unknown
     */
    const EVENT_UNSUBSCRIBE         = "event_unsubscribe";
    /**
     * 用户扫描二维码后推送事件 参数YDWXEventScan
     * @var unknown
     */
    const EVENT_SCAN           = "event_scan";
    /**
     * 菜单点击事件，参数是YDWXEventClick
     * @var unknown
     */
    const EVENT_CLICK               = "event_click";
    /**
     * 菜单跳转事件，参数是YDWXEventView
     * @var unknown
     */
    const EVENT_VIEW                = "event_view";
    /**
     * 扫码推事件的事件推送，YDWXEventScancode_push
     * @var unknown
     */
    const EVENT_SCANCODE_PUSH       = "event_scancode_push";
    /**
     * 扫码推事件且弹出“消息接收中”提示框的事件推送，YDWXEventScancode_waitmsg
     * @var unknown
     */
    const EVENT_SCANCODE_WAITMSG    = "event_scancode_waitmsg";
    
    /**
     * 弹出系统拍照发图的事件推送，参数是YDWXEventPic_sysphoto
     * @var unknown
     */
    const EVENT_PIC_SYSPHOTO    = "event_pic_sysphoto";
    
    /**
     * 弹出拍照或者相册发图的事件推送，参数是YDWXEventPic_photo_or_album
     * @var unknown
     */
    const EVENT_PIC_PHOTO_OR_ALBUM    = "event_pic_photo_or_album";
    
    /**
     * 弹出微信相册发图器的事件推送，参数是YDWXEventPic_Weixin
     * @var unknown
     */
    const EVENT_PIC_WEIXIN    = "event_pic_weixin";
    
    /**
     * 弹出地理位置选择器的事件推送，参数是YDWXEventLocation_select
     * @var unknown
     */
    const EVENT_LOCATION_SELECT    = "event_location_select";
    const EVENT_LOCATION    = "event_location";
    
    /**
     * 群发推送结果 YDWXEventMASSSENDJOBFINISH
     * @var unknown
     */
    const EVENT_MASSSENDJOBFINISH  = "event_masssendjobfinish";
    
    /**
     * 第三方平台刷新ticket通知 YDWXEventComponent_verify_ticket
     * @var unknown
     */
    const EVENT_COMPONENT_VERIFY_TICKET  = "event_component_verify_ticket";

    /**
     * 未知事件 YDWXEventUnknow
     * @var unknown
     */
    const EVENT_UNKONW  = "EVENT_UNKONW";
    
    /**
     * 摇一摇周边事件通知 YDWXEventShakearoundusershake
     * @var unknown
     */
    const EVENT_SHAKEAROUNDUSERSHAKE  = "EVENT_SHAKEAROUNDUSERSHAKE";
    
    /**
     * 核销事件通知 YDWXEventUser_consume_card
     * @var unknown
     */
    const EVENT_USER_CONSUME_CARD     = "EVENT_USER_CONSUME_CARD";
    
    /**
     * 微信买单完成时推送 YDWXEventUser_pay_from_pay_cell
     * @var unknown
     */
    const EVENT_USER_PAY_FROM_PAY_CELL = "EVENT_USER_PAY_FROM_PAY_CELL";
    /**
     * 用户领取卡券事件通知 YDWXEventUser_get_card
     * @var unknown
     */
    const EVENT_USER_GET_CARD     = "EVENT_USER_GET_CARD";
    /**
     * 生成的卡券通过审核时，微信事件推送YDWXEventCard_pass_check
     * @var unknown
     */
    const EVENT_CARD_PASS_CHECK   = "EVENT_CARD_PASS_CHECK";
    /**
     * 生成的卡券没通过审核时，微信事件推送YDWXEventCard_not_pass_check
     * @var unknown
     */
    const EVENT_CARD_NOT_PASS_CHECK   = "EVENT_CARD_NOT_PASS_CHECK";
    /**
     * 用户在删除卡券时推送事件YDWXEventUser_del_card
     * @var unknown
     */
    const EVENT_USER_DEL_CARD   = "EVENT_USER_DEL_CARD";
    /**
     * 进入会员卡事件推送 YDWXEventUser_view_card
     * 需要开发者在创建会员卡时填入need_push_on_view	字段并设置为true。开发者须综合考虑领卡人数和服务器压力，决定是否接收该事件。
     * @var unknown
     */
    const EVENT_USER_VIEW_CARD   = "EVENT_USER_VIEW_CARD";
    /**
     * 当用户的会员卡积分余额发生变动时，微信会推送事件告知开发者
     * @var YDWXEventUpdate_member_card
     */
    const EVENT_UPDATE_MEMBER_CARD   = "EVENT_UPDATE_MEMBER_CARD";
    
    /**
     * 从卡券进入公众号会话事件推送 YDWXEventUser_enter_session_from_card
     * @var unknown
     */
    const EVENT_USER_ENTER_SESSION_FROM_CARD   = "EVENT_USER_ENTER_SESSION_FROM_CARD";
    
    /**
     * 新创建的门店在审核通过后事件推送 YDWXEventPoi_check_notify
     * @var unknown
     */
    const EVENT_POI_CHECK_NOTIFY   = "EVENT_POI_CHECK_NOTIFY";
    /**
     * 红包绑定用户事件通知
     * 注：红包绑定用户不等同于用户领取红包。用户进入红包页面后，有可能不拆红包，但该红包ticket已被绑定，不能再被其他用户绑定，过期后会退回商户财付通账户
     * YDWXEventShakearoundlotterybind
     * @var unknown
     */
    const EVENT_SHAKEAROUNDLOTTERYBIND   = "EVENT_SHAKEAROUNDLOTTERYBIND";
    
    /**
     * 会员卡激活，用户填写、提交资料后，会有事件推送给商家，开发者可以在接收到事件通知后调用激活接口，
     * 传入会员卡号、初始积分等信息或者调用拉取会员信息接口获取会员信息，进行会员管理。
     * YDWXEventSubmit_membercard_user_info
     * @var unknown
     */
    const EVENT_SUBMIT_MEMBERCARD_USER_INFO   = "EVENT_SUBMIT_MEMBERCARD_USER_INFO";
    
    /**
     * 子商户审核结果通知
     * 开发者所代理的子商户审核通过后，会收到微信服务器发送的事件推送。
     * YDWXEventCard_merchant_check_result
     * @var unknown
     */
    const EVENT_CARD_MERCHANT_CHECK_RESULT = "EVENT_CARD_MERCHANT_CHECK_RESULT";
    /**
     * 商户审核结果通知
     * 当子商户资质审核通过时，开发者将收到微信服务器推送的事件。
     * YDWXEventCard_merchant_auth_check_result
     * @var unknown
     */
    const EVENT_CARD_MERCHANT_AUTH_CHECK_RESULT = "EVENT_CARD_MERCHANT_AUTH_CHECK_RESULT";
    
    /**
     * 当最后一个用户领券时，会发送事件给商户，事件每隔5min发送一次。
     * @var YDWXEventCard_sku_remind
     */
    const EVENT_CARD_SKU_REMIND = "EVENT_CARD_SKU_REMIND";
    
    /**
     * 资质认证成功
     * @var YDWXEventQualification_verify_success
     */
    const EVENT_QUALIFICATION_VERIFY_SUCCESS="EVENT_QUALIFICATION_VERIFY_SUCCESS";
    /**
     * 资质认证失败
     * @var YDWXEventQualification_verify_fail
     */
    const EVENT_QUALIFICATION_VERIFY_FAIL="EVENT_QUALIFICATION_VERIFY_FAIL";
    /**
     * 名称认证成功（即命名成功）
     * @var YDWXEventNaming_verify_success
     */
    const EVENT_NAMING_VERIFY_SUCCESS = "EVENT_NAMING_VERIFY_SUCCESS";
    /**
     * 名称认证失败（这时虽然客户端不打勾，但仍有接口权限）
     * @var YDWXEventNaming_verify_fail
     */
    const EVENT_NAMING_VERIFY_FAIL = "EVENT_NAMING_VERIFY_FAIL";
    /**
     * 公众号年审通知
     * @var YDWXEventAnnual_renew
     */
    const EVENT_ANNUAL_RENEW = "EVENT_ANNUAL_RENEW";
    /**
     * 认证过期失效通知
     * @var YDWXEventVerify_expired
     */
    const EVENT_VERIFY_EXPIRED = "EVENT_VERIFY_EXPIRED";
    
    /**
     * Wi-Fi连网成功事件
     * @var YDWXEventWificonnected
     */
    const EVENT_WIFICONNECTED = "EVENT_WIFICONNECTED";
    
    /**
     * 公众号对第三方平台取消授权通知
     * @var YDWXEventUnauthorized
     */
    const EVENT_UNAUTHORIZED = "EVENT_UNAUTHORIZED";
    
    /**
     * 公众号对第三方平台授权成功通知
     * @var YDWXEventAuthorized
     */
    const EVENT_AUTHORIZED = "EVENT_AUTHORIZED";
    
    /**
     * 公众号对第三方平台授权更新通知
     * @var YDWXEventUpdateauthorized
     */
    const EVENT_UPDATEAUTHORIZED = "EVENT_UPDATEAUTHORIZED";
    
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
