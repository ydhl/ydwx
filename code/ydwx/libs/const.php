<?php
/**
 * 订阅号
 * @var unknown
 */
define("YDWX_WEIXIN_ACCOUNT_TYPE_SUBSCRIBE",  0);
/**
 * 历史老帐号升级后的订阅号
 * @var unknown
*/
define("YDWX_WEIXIN_ACCOUNT_TYPE_UPGRADE_SUBSCRIBE",    1);
/**
 * 服务号
 * @var unknown
*/
define("YDWX_WEIXIN_ACCOUNT_TYPE_SERVICE",    2);
/**
 * 企业号
 * @var unknown
*/
define("YDWX_WEIXIN_ACCOUNT_TYPE_CROP",    3);

/**
 * 代表未认证
 * @var unknown
 */
define("YDWX_WEIXIN_VERIFY_TYPE_NONE",    -1);
/**
 * 代表微信认证
 * @var unknown
 */
define("YDWX_WEIXIN_VERIFY_TYPE_WEIXIN",    0);
/**
 * 代表新浪微博认证
 * @var unknown
 */
define("YDWX_WEIXIN_VERIFY_TYPE_SINA",    1);
/**
 * 代表腾讯微博认证
 * @var unknown
 */
define("YDWX_WEIXIN_VERIFY_TYPE_TENCENT",    2);
/**
 * 代表已资质认证通过但还未通过名称认证，
 * @var unknown
 */
define("YDWX_WEIXIN_VERIFY_TYPE_QUALIFICATION",    3);
/**
 * 代表已资质认证通过、还未通过名称认证，但通过了新浪微博认证，
 * @var unknown
 */
define("YDWX_WEIXIN_VERIFY_TYPE_QUALIFICATION_SINA",    4);

/**
 * 代表已资质认证通过、还未通过名称认证，但通过了腾讯微博认证
 */
define("YDWX_WEIXIN_VERIFY_TYPE_QUALIFICATION_TENCENT",    5);

/**
 * IT科技	互联网/电子商务
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_IT_INTERNET_ECOMMERCE",    1);
/**
 * IT科技	IT软件与服务
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_IT_SOFTWARE_SERVICE",    2);
/**
 * IT科技	IT硬件与设备
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_IT_HARDWARE_DEVICE",    3);
/**
 * IT科技	电子技术
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_IT_ELECTRONIC_TECHNIQUE",    4);
/**
 * IT科技	通信与运营商
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_IT_COMMUNICATION_CARRIER",    5);
/**
 * IT科技	网络游戏
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_IT_NETWORK_GAME",    6);
/**
 * 金融业	银行
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_BANK",    7);
/**
 * 金融业	基金|理财|信托
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_TRUST_FUND_MANAGEMENT",    8);
/**
 * 金融业	保险
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_INSURANCE",    9);
/**
 * 餐饮	餐饮
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_FOOD",    10);
/**
 * 酒店旅游	酒店
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_HOTEL",    11);
/**
 * 酒店旅游	旅游
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_TRAVEL",    12);
/**
 * 运输与仓储	快递
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_EXPRESS",    13);
/**
 * 运输与仓储	物流
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_LOGISTICS",    14);
/**
 * 运输与仓储	仓储
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_STORAGE",    15);
/**
 * 教育	培训
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_EDUCATION_TRAINING",    16);
/**
 * 教育	院校
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_COLLEGE",    17);
/**
 * 政府与公共事业	学术科研
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_RESEARCH",    18);
/**
 * 政府与公共事业	交警
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_TRAFFIC_POLICE",    19);
/**
 * 政府与公共事业	博物馆
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_MUSEUM",    20);
/**
 * 政府与公共事业	公共事业|非盈利机构
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_PUBLIC_UTILITY",    21);
/**
 * 医药护理	医药医疗
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_MEDICAL_TREATMENT",    22);
/**
 * 医药护理	护理美容
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_TEND_HAIRDRESSING",    23);
/**
 * 医药护理	保健与卫生
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_HEALTH",    24);
/**
 * 交通工具	汽车相关
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_VEHICLE_CAR",    25);
/**
 * 交通工具	摩托车相关
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_VEHICLE_MOTOR",    26);
/**
 * 交通工具	火车相关
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_VEHICLE_TRAIN",    27);
/**
 * 交通工具	飞机相关
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_VEHICLE_PLANE",    28);
/**
 * 房地产	建筑
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_REALTY_BUILDING",    29);
/**
 * 房地产	物业
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_REALTY_PROPERTY",    30);
/**
 * 消费品	消费品
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_CONSUMABLE",    31);
/**
 * 商业服务	法律
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_BUSINESS_LAW",    32);
/**
 * 商业服务	会展
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_BUSINESS_CONVENTION",    33);
/**
 * 商业服务	中介服务
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_BUSINESS_INTERMEDIARY",    34);
/**
 * 商业服务	认证
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_BUSINESS_IDENTIFICATION",    35);
/**
 * 商业服务	审计
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_BUSINESS_AUDIT",    36);
/**
 * 文体娱乐	传媒
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_MASS_MEDIA",    37);
/**
 * 文体娱乐	体育
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_SPORTS",    38);
/**
 * 文体娱乐	娱乐休闲
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_ENTERTAINMENT",    39);
/**
 * 印刷	印刷
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_PRESS",    40);
/**
 * 其它	其它
 * @VAR UNKNOWN
 */
DEFINE("YDWX_INDUSTRY_OTHER",    41);


/**
 * 消息与菜单权限集
 * @var unknown
 */
DEFINE("YDWX_FUNC_COMMAND_AND_MENU", 1);
/**
 * 用户管理权限集
 * @var unknown
 */
DEFINE("YDWX_FUNC_USER", 2);
/**
 * 帐号管理权限集
 * @var unknown
 */
DEFINE("YDWX_FUNC_ACCOUNT", 3);
/**
 * 网页授权权限集
 * @var unknown
 */
DEFINE("YDWX_FUNC_OAUTH", 4);
/**
 * 微信小店权限集
 * @var unknown
 */
DEFINE("YDWX_FUNC_SHOP", 5);
/**
 * 多客服权限集
 * @var unknown
 */
DEFINE("YDWX_FUNC_SUPPORT", 6);
/**
 * 业务通知权限集
 * @var unknown
 */
DEFINE("YDWX_FUNC_BUSINESS_NOTIFICATION", 7);
/**
 * 微信卡券权限集
 */
DEFINE("YDWX_FUNC_CARD", 8);
/**
 * 微信扫一扫权限集
 */
DEFINE("YDWX_FUNC_SCAN", 9);
/**
 * 微信连WIFI权限集
 */
DEFINE("YDWX_FUNC_WIFI", 10);
/**
 * 素材管理权限集
 */
DEFINE("YDWX_FUNC_MATERIAL", 11);
/**
 * 摇一摇周边权限集
 */
DEFINE("YDWX_FUNC_SHAKEAROUND", 12);
/**
 * 微信门店权限集
 */
DEFINE("YDWX_FUNC_STORE", 13);

/**
 * 颜色值<strong color='#63b359'>#63b359</strong>
 * 
 */
DEFINE("YDWX_CARD_COLOR_010", 'Color010');
/**
 * 颜色值<strong color='#2c9f67'>#2c9f67</strong>
 *
 */
DEFINE("YDWX_CARD_COLOR_020", 'Color020');
/**
 * 颜色值<strong color='#509fc9'>#509fc9</strong>
 *
 */
DEFINE("YDWX_CARD_COLOR_030", 'Color030');
/**
 * 颜色值<strong color='#5885cf'>#5885cf</strong>
 *
 */
DEFINE("YDWX_CARD_COLOR_040", 'Color040');
/**
 * 颜色值<strong color='#9062c0'>#9062c0</strong>
 *
 */
DEFINE("YDWX_CARD_COLOR_050", 'Color050');
/**
 * 颜色值<strong color='#d09a45'>#d09a45</strong>
 *
 */
DEFINE("YDWX_CARD_COLOR_060", 'Color060');
/**
 * 颜色值<strong color='#e4b138'>#e4b138</strong>
 *
 */
DEFINE("YDWX_CARD_COLOR_070", 'Color070');
/**
 * 颜色值<strong color='#ee903c'>#ee903c</strong>
 *
 */
DEFINE("YDWX_CARD_COLOR_080", 'Color080');
/**
 * 颜色值<strong color='#f08500'>#f08500</strong>
 *
 */
DEFINE("YDWX_CARD_COLOR_081", 'Color081');
/**
 * 颜色值<strong color='#a9d92d'>#a9d92d</strong>
 *
 */
DEFINE("YDWX_CARD_COLOR_082", 'Color082');
/**
 * 颜色值<strong color='#dd6549'>#dd6549</strong>
 *
 */
DEFINE("YDWX_CARD_COLOR_090", 'Color090');
/**
 * 颜色值<strong color='#cc463d'>#cc463d</strong>
 *
 */
DEFINE("YDWX_CARD_COLOR_100", 'Color100');
/**
 * 颜色值<strong color='#cf3e36'>#cf3e36</strong>
 *
 */
DEFINE("YDWX_CARD_COLOR_101", 'Color101');
/**
 * 颜色值<strong color='#5E6671'>#5E6671</strong>
 *
 */
DEFINE("YDWX_CARD_COLOR_102", 'Color102');

/**
 * 文本
 * @var unknown
 */
DEFINE("YDWX_CARD_CODE_TYPE_TEXT",        "CODE_TYPE_TEXT");
/**
 * 自定义使用按钮
 * @var unknown
 */
DEFINE("YDWX_CARD_CODE_TYPE_NONE",        "CODE_TYPE_NONE");

/**
 * 一维码
 * @var unknown
 */
DEFINE("YDWX_CARD_CODE_TYPE_BARCODE",     "CODE_TYPE_BARCODE");
/**
 * 二维码
 * @var unknown
 */
DEFINE("YDWX_CARD_CODE_TYPE_QRCODE",      "CODE_TYPE_QRCODE");
/**
 * 二维码无code显示
 * @var unknown
 */
DEFINE("YDWX_CARD_CODE_TYPE_ONLY_QRCODE", "CODE_TYPE_ONLY_QRCODE");
/**
 * 一维码无code显示
 * @var unknown
 */
DEFINE("YDWX_CARD_CODE_TYPE_ONLY_BARCODE","CODE_TYPE_ONLY_BARCODE");
/**
 * 表示固定日期区间
 * @var unknown
 */
DEFINE("YDWX_DATE_TYPE_FIX_TIME_RANGE","DATE_TYPE_FIX_TIME_RANGE");
/**
 * 表示永久有效
 * @var unknown
 */
DEFINE("YDWX_DATE_TYPE_PERMANENT","DATE_TYPE_PERMANENT");
/**
 * 表示固定时长（自领取后按天算)
 * @var unknown
 */
DEFINE("YDWX_DATE_TYPE_FIX_TERM","DATE_TYPE_FIX_TERM");

/**
 * API核销卡券
 * @var unknown
 */
DEFINE("YDWX_CARD_CONSUME_FROM_API","FROM_API");
/**
 * 公众平台核销
 * @var unknown
 */
DEFINE("YDWX_CARD_CONSUME_FROM_MP", "FROM_MP");
/**
 * 卡券商户助手核销（FROM_MOBILE_HELPER）（核销员微信号）
 * @var unknown
 */
DEFINE("YDWX_CARD_CONSUME_FROM_MOBILE_HELPER","FROM_MOBILE_HELPER");
DEFINE("YDWX_JSAPI_ONMENUSHARETIMELINE","onMenuShareTimeline");
DEFINE("YDWX_JSAPI_ONMENUSHAREAPPMESSAGE","onMenuShareAppMessage");
DEFINE("YDWX_JSAPI_ONMENUSHAREQQ","onMenuShareQQ");
DEFINE("YDWX_JSAPI_ONMENUSHAREWEIBO","onMenuShareWeibo");
DEFINE("YDWX_JSAPI_ONMENUSHAREQZONE","onMenuShareQZone");
DEFINE("YDWX_JSAPI_STARTRECORD","startRecord");
DEFINE("YDWX_JSAPI_STOPRECORD","stopRecord");
DEFINE("YDWX_JSAPI_ONVOICERECORDEND","onVoiceRecordEnd");
DEFINE("YDWX_JSAPI_PLAYVOICE","playVoice");
DEFINE("YDWX_JSAPI_PAUSEVOICE","pauseVoice");
DEFINE("YDWX_JSAPI_STOPVOICE","stopVoice");
DEFINE("YDWX_JSAPI_ONVOICEPLAYEND","onVoicePlayEnd");
DEFINE("YDWX_JSAPI_UPLOADVOICE","uploadVoice");
DEFINE("YDWX_JSAPI_DOWNLOADVOICE","downloadVoice");
DEFINE("YDWX_JSAPI_CHOOSEIMAGE","chooseImage");
DEFINE("YDWX_JSAPI_PREVIEWIMAGE","previewImage");
DEFINE("YDWX_JSAPI_UPLOADIMAGE","uploadImage");
DEFINE("YDWX_JSAPI_DOWNLOADIMAGE","downloadImage");
DEFINE("YDWX_JSAPI_TRANSLATEVOICE","translateVoice");
DEFINE("YDWX_JSAPI_GETNETWORKTYPE","getNetworkType");
DEFINE("YDWX_JSAPI_OPENLOCATION","openLocation");
DEFINE("YDWX_JSAPI_GETLOCATION","getLocation");
DEFINE("YDWX_JSAPI_HIDEOPTIONMENU","hideOptionMenu");
DEFINE("YDWX_JSAPI_SHOWOPTIONMENU","showOptionMenu");
DEFINE("YDWX_JSAPI_HIDEMENUITEMS","hideMenuItems");
DEFINE("YDWX_JSAPI_SHOWMENUITEMS","showMenuItems");
DEFINE("YDWX_JSAPI_HIDEALLNONBASEMENUITEM","hideAllNonBaseMenuItem");
DEFINE("YDWX_JSAPI_SHOWALLNONBASEMENUITEM","showAllNonBaseMenuItem");
DEFINE("YDWX_JSAPI_CLOSEWINDOW","closeWindow");
DEFINE("YDWX_JSAPI_SCANQRCODE","scanQRCode");
DEFINE("YDWX_JSAPI_CHOOSEWXPAY","chooseWXPay");
DEFINE("YDWX_JSAPI_OPENPRODUCTSPECIFICVIEW","openProductSpecificView");
DEFINE("YDWX_JSAPI_ADDCARD","addCard");
DEFINE("YDWX_JSAPI_CHOOSECARD","chooseCard");
DEFINE("YDWX_JSAPI_OPENCARD","openCard");
DEFINE("YDWX_JSAPI_STARTSEARCHBEACONS","startSearchBeacons");
DEFINE("YDWX_JSAPI_STOPSEARCHBEACONS","stopSearchBeacons");
DEFINE("YDWX_JSAPI_ONSEARCHBEACONS","onSearchBeacons");
/**
 *  附近
 * @var unknown
 */
DEFINE("YDWX_CARD_SCENE_NEAR_BY","SCENE_NEAR_BY");
/**
 * 二维码
 * @var unknown
 */
DEFINE("YDWX_CARD_SCENE_QRCODE","SCENE_QRCODE");
/**
 * 公众号文章
 * @var unknown
 */
DEFINE("YDWX_CARD_SCENE_ARTICLE","SCENE_ARTICLE");
/**
 * h5页面
 * @var unknown
 */
DEFINE("YDWX_CARD_SCENE_H5","SCENE_H5");
/**
 * 自定义菜单
 * @var unknown
 */
DEFINE("YDWX_CARD_SCENE_MENU","SCENE_MENU");
/**
 * 自动回复
 * @var unknown
 */
DEFINE("YDWX_CARD_SCENE_IVR","SCENE_IVR");
/**
 * 卡券自定义cell
 * @var unknown
 */
DEFINE("YDWX_CARD_SCENE_CARD_CUSTOM_CELL","SCENE_CARD_CUSTOM_CELL");
//正常
DEFINE("YDWX_CARD_USER_STATUS_NORMAL","NORMAL");
//已核销
DEFINE("YDWX_CARD_USER_STATUS_CONSUMED","CONSUMED");
//已过期
DEFINE("YDWX_CARD_USER_STATUS_EXPIRE","EXPIRE");
//转赠中
DEFINE("YDWX_CARD_USER_STATUS_GIFTING","GIFTING");
DEFINE("YDWX_CARD_USER_STATUS_GIFT_SUCC","GIFT_SUCC");
//转赠超时
DEFINE("YDWX_CARD_USER_STATUS_GIFT_TIMEOUT","GIFT_TIMEOUT");
//已删除
DEFINE("YDWX_CARD_USER_STATUS_DELETE","DELETE");
//已失效
DEFINE("YDWX_CARD_USER_STATUS_UNAVAILABLE","UNAVAILABLE");
//code未被添加或被转赠领取的情况则统一报错
DEFINE("YDWX_CARD_USER_STATUS_INVALID_CODE","invalid serial code");
/**
 * 待审核
 */
DEFINE("YDWX_CARD_STATUS_NOT_VERIFY",       "CARD_STATUS_NOT_VERIFY");
/**
 * 审核失败
 */
DEFINE("YDWX_CARD_STATUS_VERIFY_FAIL",      "CARD_STATUS_VERIFY_FAIL");
/**
 * 通过审核
 */
DEFINE("YDWX_CARD_STATUS_VERIFY_OK",        "CARD_STATUS_VERIFY_OK");
/**
 * 卡券被商户删除
 */
DEFINE("YDWX_CARD_STATUS_USER_DELETE",      "CARD_STATUS_USER_DELETE");
/**
 * 在公众平台投放过的卡券
 */
DEFINE("YDWX_CARD_STATUS_USER_DISPATCH",    "CARD_STATUS_USER_DISPATCH"); 
DEFINE("YDWX_CARD_STATUS_DISPATCH",         "CARD_STATUS_DISPATCH");
/**
 * 子商户审核中
 * @var string
 */
DEFINE("YDWX_CARD_MERCHANT_CHECKING",         "CHECKING");
/**
 * 子商户审核通过
 * @var string
 */
DEFINE("YDWX_CARD_MERCHANT_APPROVED",         "APPROVED");
/**
 * 子商户被驳回
 * @var string
 */
DEFINE("YDWX_CARD_MERCHANT_REJECTED",         "REJECTED");
/**
 * 子商户过期
 * @var string
 */
DEFINE("YDWX_CARD_MERCHANT_EXPIRED",         "EXPIRED");

/**
 * 审核通过
 * @var unknown
 */
DEFINE("YDWX_CARD_CHECK_AGENT_QUALIFICATION_RESULT_PASS",             "RESULT_PASS");
/**
 * 审核不通过
 * @var unknown
 */
DEFINE("YDWX_CARD_CHECK_AGENT_QUALIFICATION_RESULT_NOT_PASS",         "RESULT_NOT_PASS");
/**
 * 审核中
 * @var unknown
 */
DEFINE("YDWX_CARD_CHECK_AGENT_QUALIFICATION_RESULT_CHECKING",         "RESULT_CHECKING");
/**
 * 无提审记录
 * @var unknown
 */
DEFINE("YDWX_CARD_CHECK_AGENT_QUALIFICATION_RESULT_NOTHING_TO_CHECK", "RESULT_NOTHING_TO_CHECK");

/**
 * 普通红包
 * @var unknown
 */
DEFINE("YDWX_PACKET_TYPE_NORMAL", "NORMAL");
/**
 * 裂变红包(可分享红包给好友，无关注公众号能力)
 * @var unknown
 */
DEFINE("YDWX_PACKET_TYPE_GROUP",  "GROUP");
/**
 * 风控设置,正常情况
 * @var unknown
 */
DEFINE("YDWX_PACKET_RISK_CNTL_NORMAL",  "NORMAL");
/**
 * 风控设置,忽略防刷限制，强制发放；
 * @var unknown
 */
DEFINE("YDWX_PACKET_RISK_CNTL_IGN_FREQ_LMT",  "IGN_FREQ_LMT");
/**
 * 风控设置,忽略单用户日限额限制，强制发放
 * @var unknown
 */
DEFINE("YDWX_PACKET_RISK_CNTL_IGN_DAY_LMT",  "IGN_DAY_LMT");
/**
 * 风控设置,忽略防刷和单用户日限额限制，强制发放
 * @var unknown
 */
DEFINE("YDWX_PACKET_RISK_CNTL_IGN_FREQ_DAY_LMT",  "IGN_FREQ_DAY_LMT");
/**
 * 红包金额设置方式，只对裂变红包生效。ALL_RAND—全部随机
 * @var unknown
 */
DEFINE("YDWX_PACKET_AMT_TYPE_ALL_RAND",  "ALL_RAND");


define("YDWX_WEIXIN_BASE_URL",           "https://api.weixin.qq.com/cgi-bin/");
define("YDWX_WEIXIN_BASE_URL2",          "https://api.weixin.qq.com/");
define("YDWX_WEIXIN_WEB_BASE_URL",       "https://api.weixin.qq.com/sns/");
define("YDWX_WEIXIN_NO_SSL_URL",         "http://api.weixin.qq.com/cgi-bin/");
define("YDWX_WEIXIN_QY_BASE_URL",        "https://qyapi.weixin.qq.com/cgi-bin/");
define("YDWX_WEIXIN_PAY_URL",            "https://api.mch.weixin.qq.com/");

?>