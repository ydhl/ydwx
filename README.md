# ydwx 开发框架 
V0.1.2

1. 增加企业付款及查询
2. 增加oauth获取access token和refresh token的方法[20160512]
3. 公众号对第三方托管平台授权事件：处理授权、取消授权、更新授权


V0.1.1

1. 增加ydwx_pay_deeplink
2. 修复相关pay respone的isPrepaySuccess判断错误
3. 修复获取支付二维码短地址时签名错误 YDWXPayShorturlRequest
4. 优化模式1支付通知的流程

## 如何使用

1. 拷贝code中的内容到你的项目，code中包含了库代码和一些直接访问入口，这些访问入口主要提供给微信回调用的
2. 根据你的公众号情况，修改code/ydwx/__config__.php中的配置，里面的配置项有完整的注释
3. 对你感兴趣的微信事件注册hook处理函数，并把这些函数文件放在code/ydwxhooks中，里面的文件任意组织，ydwx会自动包含他们
4. 剩下的只需要到微信上配置一下就可以了。

*ydwx的目的是把微信的交互细节帮你搞定，你只需要填写相关申请到的key然后注册你关心的事件hook就可以了*

## 进一步了解 https://github.com/ydhl/ydwx/wiki

## 版本特性

### 160319
1. 把企业号，服务号，第三方平台的各种处理都分开
	1. 事件通知，token认证：index.php index-crop.php index-agent.php
	2. 显式认证：auth.php auth-crop.php auth-agent.php
	3. 隐式认证：baseauth.php baseauth-crop.php baseauth-agent.php
	4. 令牌刷新：refresh.php refresh-crop.php refresh-agent.php
2. 去掉账号类型的配置和是否认证的配置
3. token,ticket等的刷新，获取hook分开定义

### 151023更新
1. 增加第三方平台支持，使用ydwx便可轻松开发微信第三方平台
2. 微信卡券：发卡，作为母商户代替子商户发卡，母商户子商户申请（子商户可没有公众号）
3. 客服接口
4. 红包接口，摇一摇红包，现金红包，裂变红包
5. 摇一摇周边
6. 门店管理

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

