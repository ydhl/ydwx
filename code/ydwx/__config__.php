<?php

$cwd = dirname ( __FILE__ );


/***
 * @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
 * 根据你公众号的情况填写
 * @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
 */
define("YDWX_HOOK_DIR",             $cwd."/../ydwxhooks");
/**
 * 你网站的地址,以/结尾，通过YDWX_SITE_URL."ydwx/index.php"；需要能正确访问
 */
define("YDWX_SITE_URL",             "");

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

define("WEIXIN_ACCOUNT_TYPE",       WEIXIN_ACCOUNT_CROP);//公众号类型
/**
 * 公众号是否认证
 * @var unknown
 */
define("WEIXIN_IS_AUTHED",          true);


define("WEIXIN_NOTIFY_URL",         "ydwx/pay-notify.php的地址");//填写ydwx/pay-notify.php在你网站上的地址

/***
 * -----------------
 * 下面的代码不要修改
 * ----------------
 */

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
define("WEIXIN_BASE_URL",           "https://api.weixin.qq.com/cgi-bin/");
define("WEIXIN_WEB_BASE_URL",       "https://api.weixin.qq.com/sns/");
define("WEIXIN_BASE_URL2",          "http://api.weixin.qq.com/cgi-bin/");

/**
 * 企业号调用接口地址
 * @var unknown
 */
define("WEIXIN_QY_BASE_URL",        "https://qyapi.weixin.qq.com/cgi-bin/");

require_once $cwd.'/libs/ydwxhook.php';
YDWXHook::include_files($cwd."/libs");
YDWXHook::include_files($cwd."/models");
YDWXHook::include_files($cwd."/functions");//包含功能函数库
YDWXHook::include_files(YDWX_HOOK_DIR);//hooks目录
?>