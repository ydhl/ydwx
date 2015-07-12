需要配置自动运行脚本
crontab -e
0 */2 * * * php /var/www/html/ydwx/refresh.php
*/10 * * * * php /var/www/html/app/reminder.php
crontab -l

分别为没2个小时刷新微信token
每10分钟检查标签提醒

微信配置
1. 填写token认证地址 /ydwx/index.php
2. 绑定js安全域名
3. 配置提醒模板和赠送模板并把相关的id配置到config.php中REMINDER_TPL_ID，AWARD_TPL_ID
4. 配置支付的正常路径app和测试路径testapp
5. 配置oauth授权回调域名
6. 把微信相关的回调卸载ydwxhooks中，具体有哪些回调查看这里ydwx/code/ydwx/libs/ydhooks.php

配置ydwx/libs/wx.php

上传是注意config/ydwx/libs/wx.php不要被覆盖