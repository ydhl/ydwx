<?php
/**
 * 在摇出来的页面中当用户抽奖时后
 * get 请求该接口查看中奖结果
 */

/**
 * 抽奖ID。必填
 * @var
 */
$lottery_id = $_GET['lottery_id'];

$http = new YDHttp();
$info = $http->get("http://yao.qq.com/lottery/getuserprizeresult?lottery_id={$lottery_id}");
$rst = new YDWXYaoTvDrawResult($info);
return json_encode($rst);
    