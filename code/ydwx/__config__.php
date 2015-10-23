<?php

$cwd = dirname ( __FILE__ );
require_once $cwd.'/libs/ydwxhook.php';
YDWXHook::include_files($cwd."/libs");

#
#
# 根据你公众号的情况填写一下信息
#
#

//你hook钩子函数文件放置的目录
define("YDWX_HOOK_DIR",             $cwd."/../ydwxhooks");

//你网站的地址,以/结尾，通过YDWX_SITE_URL."ydwx/index.php"；需要能正确访问
define("YDWX_SITE_URL",             "");

//微信网站定义，用于微信登录网站
define("YDWX_WEIXIN_WEB_APP_ID",         "");
define("YDWX_WEIXIN_WEB_APP_SECRET",     "");

//如果你想作为微信第三方托管平台
define("YDWX_WEIXIN_COMPONENT_APP_ID",         "");
define("YDWX_WEIXIN_COMPONENT_APP_SECRET",     "");
define("YDWX_WEIXIN_COMPONENT_ENCODING_AES_KEY","");
define("YDWX_WEIXIN_COMPONENT_TOKEN",          "");

//如果配置了这个YDWX_WEIXIN_COMPONENT_APP_ID，则表示你使用ydwx来做为微信第三方托管平台；
//下面的配置【可能】都无效，这时你自己的公众号也做为托管公众号来管理；下面的配置在某些情况下只作为平台的公众号配置
//所代理的公众号的这些信息需要开发者自己存数据库管理

//微信公众号定义
define("YDWX_WEIXIN_APP_ID",             "");//
define("YDWX_WEIXIN_APP_SECRET",         "");//
define("YDWX_WEIXIN_ENCODING_AES_KEY",   "");//
define("YDWX_WEIXIN_TOKEN",              "");//

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


//企业号的cropid
define("YDWX_WEIXIN_CROP_ID",     "");
define("YDWX_WEIXIN_CROP_SECRET", "");

//企业应用的id
define("YDWX_WEIXIN_CROP_AGENT_ID",    "");

define("YDWX_WEIXIN_ACCOUNT_TYPE",       YDWX_WEIXIN_ACCOUNT_TYPE_SERVICE);//公众号类型

//公众号是否认证
define("YDWX_WEIXIN_IS_AUTHED",          true);

#
#
# 填写结束
#
#


YDWXHook::include_files($cwd."/models");
YDWXHook::include_files($cwd."/functions");//包含功能函数库

#加载你自己的hook目录
YDWXHook::include_files(YDWX_HOOK_DIR);
?>