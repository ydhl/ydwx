# ydwx 开发框架 V0.1

## 如何使用

1. 拷贝code中的内容到你的项目，code中包含了库代码和一些直接访问入口，这些访问入口主要提供给微信回调用的
2. 根据你的公众号情况，修改code/ydwx/libs/wx.php中的配置，里面的配置项有完整的注释
3. 对你感兴趣的微信事件注册hook处理函数，并把这些函数文件放在code/ydwxhooks中，里面的文件任意组织，ydwx会自动包含他们
4. 剩下的只需要到微信上配置一下就可以了。

*ydwx的目的是把微信的交互细节帮你搞定，你只需要填写相关申请到的key然后注册你关心的事件hook就可以了*

## 其他

1. 调用微信的接口需要提供access_token, 但该token只有2小时的有效期，过期后需要重新刷新
    你可以采用自己服务器上的cron设置一下，没两小时刷新处理：
    0 */2 * * * php path-to/ydwx/refresh.php
    你也可以采用ydtimer这种网络定时器，让他来定时调用refresh.php, 你只需在它哪里设置ydwx/refresh.php在你服务器上的网络地址便可
2. 由于涉及到xml处理，你的服务器上php需要安装这些扩展
    1. php-xml
    2. php-mcrypt
    
## 配置微信

1. 填写token认证地址 /ydwx/index.php
2. 绑定js安全域名
3. 配置提醒模板和赠送模板并把相关的id配置到config.php中REMINDER_TPL_ID，AWARD_TPL_ID
4. 配置支付的正常路径app和测试路径testapp
5. 配置oauth授权回调域名


## 文件说明

- index.php       微信的回调入口
- auth.php        微信内浏览器的认证入口，包含服务号和企业号，服务号和企业号的登录有区别，详情见相关的hooks
- pay-notify.php  微信支付的回调入口，在js支付，扫码支付时都会回调这里，详情请看相关的hook说明
- refresh.php     自动刷新脚本，自动刷新access_token 和jsticket api
- webauth.php     浏览器上微信登陆入口

## hook说明

所有微信的事件将以hook的方式进行回调，所有hook文件都必须位于ydwxhooks中，hook的注册方法
YDHook::add_hook(WXHooks::XXXX, function($args){
    //your code write here
});

### hook函数参数

hook传入的参数可能是数组，字符串或是WXMsg，里面封装了所有的微信往返消息结构，它是个大杂烩，具体的hook要如何取，取什么看hook的说明
你值需要根据说明的key调用$msg->get(WXMsg::XXX)便可


WXHooks列表

## 0.1 版本特性

### 基本功能
1. 自动刷新access_token和jsapi_ticket
2. web网站微信登录
3. 微信内H5登录
4. 获取菜单、创建菜单、删除菜单

### 消息类
1. 发送模板消息
2. 通过微信id发送文本消息
3. 自动应答文本消息
4. 自动应答图片消息
5. 自动应答图文消息

### 用户类
1. 获取微信内H5访问用户信息
2. 获取网页微信登录用户信息

### 素材类
1. 上传文件
2. 下载临时文件

### js接口
1. 自定义分享标题，描述，图片

### 支付接口
1. 微信公众号支付
2. 网站上微信二维码扫描支付

