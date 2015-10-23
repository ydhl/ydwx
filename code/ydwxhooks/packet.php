<?php
/**
 * $oldcwd = getcwd();
 * #如需要把工作目录切换到你项目中去，并包含项目的库文件来实现hook中的逻辑
 * chdir($your_work_dir);
 * include_once 'your-lib-file.php';
 * chdir ( $oldcwd );
 */
 
 
YDWXHook::add_hook(YDWXHook::EVENT_SHAKEAROUNDLOTTERYBIND, function (YDWXEventShakearoundLotteryBind $bind){
    //微信红包绑定事件
	
});