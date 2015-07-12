<?php
//微信公众号定义
define("WEIXIN_APP_ID",             "");
define("WEIXIN_APP_SECRET",         "");
define("WEIXIN_ENCODING_AES_KEY",   "");
define("WEIXIN_TOKEN",              "");

//微信支付商户定义
define("WEIXIN_MCH_ID",             "");
define("WEIXIN_MCH_KEY",            "");

//微信网站定义
define("WEIXIN_WEB_APP_ID",         "");
define("WEIXIN_WEB_APP_SECRET",     "");
/**
 * 企业号的cropid
 * @var unknown
 */
define("WEIXIN_CROP_ID",     "");
define("WEIXIN_CROP_SECRET", "");
/**
 * 企业应用的id
 * @var unknown
 */
define("WEIXIN_CROP_AGENT_ID",    "");
/**
 * 订阅号
 * @var unknown
 */
define("WEIXIN_ACCOUNT_SUBSCRIBE",  0);
/**
 * 历史老帐号升级后的订阅号
 * @var unknown
 */
define("WEIXIN_ACCOUNT_UPGRADE_SUBSCRIBE",    1);
/**
 * 服务号
 * @var unknown
 */
define("WEIXIN_ACCOUNT_SERVICE",    2);
/**
 * 企业号
 * @var unknown
 */
define("WEIXIN_ACCOUNT_CROP",    3);
define("WEIXIN_ACCOUNT_TYPE",       WEIXIN_ACCOUNT_CROP);//公众号类型
/**
 * 是否认证
 * @var unknown
 */
define("WEIXIN_IS_AUTHED",          true);

define("WEIXIN_BASE_URL",           "https://api.weixin.qq.com/cgi-bin/");
define("WEIXIN_WEB_BASE_URL",       "https://api.weixin.qq.com/sns/");
define("WEIXIN_BASE_URL2",          "http://api.weixin.qq.com/cgi-bin/");
define("WEIXIN_NOTIFY_URL",         "your weixin pay notify url");
/**
 * 企业号调用接口地址
 * @var unknown
 */
define("WEIXIN_QY_BASE_URL",        "https://qyapi.weixin.qq.com/cgi-bin/");

$cwd = dirname ( __FILE__ );

include_once "$cwd/http.php";
include_once "$cwd/wxBizMsgCrypt.php";
include_once "$cwd/model.php";
include_once "$cwd/ydhooks.php";

YDHook::include_files($cwd."/../functions");//包含功能函数库
YDHook::include_files($cwd."/../../ydwxhooks");//hooks目录
?>