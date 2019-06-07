# ydwx 开发框架 

## 如何使用

1. 拷贝code中的内容到你的项目，ydwx中包含了库代码和一些直接访问入口，这些访问入口主要提供给微信回调用的，ydwxhooks是你要关系的需要注册的回调
2. 根据你的公众号情况，修改__config__.php中的配置，里面的配置项有完整的注释
3. 对你感兴趣的微信事件注册hook处理函数，并把这些函数文件放在code/ydwxhooks中，里面的文件任意组织，ydwx会自动包含他们
4. 剩下的只需要到微信上配置一下就可以了。
5. 配置access token自动刷新，在你的服务器上配置定时刷新，如cron，
    refresh.php 刷新普通公众号
    refresh-crop.php 刷新企业微信
    refresh-agent.php 刷新第三方公众平台

*ydwx的目的是把微信的交互细节帮你搞定，你只需要填写相关申请到的key然后注册你关心的事件hook就可以了*

## 进一步了解 https://github.com/ydhl/ydwx/wiki

欢迎大家把遇到的问题在issue提出来
