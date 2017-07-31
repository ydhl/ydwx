<?php

$cwd = dirname ( __FILE__ );
require_once $cwd.'/ydwx/libs/ydwxhook.php';
YDWXHook::include_files($cwd."/ydwx/libs");

#
#
# 根据你公众号的情况填写一下信息
#
#

//你hook钩子函数文件放置的目录
define("YDWX_HOOK_DIR",             $cwd."/ydwxhooks");

//你网站的地址,以/结尾，通过YDWX_SITE_URL."ydwx/index.php"；需要能正确访问
define("YDWX_SITE_URL",             "http://dcseller.applinzi.com/ydwx");

//微信网站定义，用于微信登录网站
define("YDWX_WEIXIN_WEB_APP_ID",         "");
define("YDWX_WEIXIN_WEB_APP_SECRET",     "");

//如果你想作为微信第三方托管平台
define("YDWX_WEIXIN_COMPONENT_APP_ID",         "");
define("YDWX_WEIXIN_COMPONENT_APP_SECRET",     "");
define("YDWX_WEIXIN_COMPONENT_ENCODING_AES_KEY","");
define("YDWX_WEIXIN_COMPONENT_TOKEN",          "");

//微信公众号定义
define("YDWX_WEIXIN_APP_ID",             "");// 
define("YDWX_WEIXIN_APP_SECRET",         "");//
define("YDWX_WEIXIN_ENCODING_AES_KEY",   "");//
define("YDWX_WEIXIN_TOKEN",              "");//

//微信移动应用信息
define("YDWX_WEIXIN_MOBILE_APP_ID",      "");//
define("YDWX_WEIXIN_MOBILE_APP_SECRET",  "");//


//微信支付商户定义
define("YDWX_WEIXIN_MCH_ID",             "");
define("YDWX_WEIXIN_MCH_KEY",            "");

/**
 * 证书pem格式（apiclient_cert.pem）路径，建议放在非web访问路径中
 * @var unknown
 */
define("YDWX_WEIXIN_APICLIENT_CERT",     "");
/**
 * 证书密钥pem格式（apiclient_key.pem），建议放在非web访问路径中
 */
define("YDWX_WEIXIN_APICLIENT_KEY",      "");
/**
 * CA证书（rootca.pem），建议放在非web访问路径中
 */
define("YDWX_WEIXIN_ROOTCA",      "");

//微信移动app申请的支付信息
define("YDWX_WEIXIN_MOBILE_MCH_ID",      "");
define("YDWX_WEIXIN_MOBILE_MCH_KEY",     "");
/**
 * 证书pem格式（apiclient_cert.pem）路径，建议放在非web访问路径中
 * @var unknown
 */
define("YDWX_WEIXIN_MOBILE_APICLIENT_CERT",     "");
/**
 * 证书密钥pem格式（apiclient_key.pem），建议放在非web访问路径中
 */
define("YDWX_WEIXIN_MOBILE_APICLIENT_KEY",      "");
/**
 * CA证书（rootca.pem），建议放在非web访问路径中
 */
define("YDWX_WEIXIN_MOBILE_ROOTCA",      "");

//企业号的cropid
define("YDWX_WEIXIN_CROP_ID",     "");
define("YDWX_WEIXIN_CROP_SECRET", "");

//微信支付商户定义, 默认跟上面配置的不同公众号一样
define("YDWX_WEIXIN_QY_MCH_ID",             YDWX_WEIXIN_MCH_ID);
define("YDWX_WEIXIN_QY_MCH_KEY",            YDWX_WEIXIN_MCH_KEY);
define("YDWX_WEIXIN_QY_APICLIENT_CERT",     YDWX_WEIXIN_APICLIENT_CERT);
define("YDWX_WEIXIN_QY_APICLIENT_KEY",      YDWX_WEIXIN_APICLIENT_KEY);
define("YDWX_WEIXIN_QY_ROOTCA",     		YDWX_WEIXIN_ROOTCA);
#
#
# 填写结束
#
#


YDWXHook::include_files($cwd."/ydwx/models");
YDWXHook::include_files($cwd."/ydwx/functions");//包含功能函数库

#加载你自己的hook目录
YDWXHook::include_files(YDWX_HOOK_DIR);
?>