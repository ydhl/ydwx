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
DEFINE("YDWX_INDUSTRY_IT_COMMUNICATION_CARRIER ",    5);
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
DEFINE("YDWX_INDUSTRY_VEHICLE_CAR",    24);
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

define("YDWX_WEIXIN_BASE_URL",           "https://api.weixin.qq.com/cgi-bin/");
define("YDWX_WEIXIN_BASE_URL2",          "https://api.weixin.qq.com/");
define("YDWX_WEIXIN_WEB_BASE_URL",       "https://api.weixin.qq.com/sns/");
define("YDWX_YDWX_WEIXIN_BASE_URL2",     "http://api.weixin.qq.com/cgi-bin/");
define("YDWX_WEIXIN_QY_BASE_URL",        "https://qyapi.weixin.qq.com/cgi-bin/");
define("YDWX_WEIXIN_PAY_URL",            "https://api.mch.weixin.qq.com/");

?>