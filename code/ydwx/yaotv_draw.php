<?php
/**
 * 在摇出来的页面中当用户抽奖时，
 * GET 请求该接口，该接口会对用户请求进行校验和过滤，当请求过于频繁时，会要求传入验证码。
 * 用户只有在摇电视的场景下才能中奖，分享到朋友圈和好友的页面中发起该请求，将不会中奖。
 * 每个用户在一个抽奖id下最多只能中一次奖。
 */

/**
 * 唯一表示当前用户的id，可以是openid或是第三方自定义的用户id。必填
 * @var unknown
 */
$userid = $_GET['userid'];

/**
 * 抽奖ID。必填
 * @var
 */
$lottery_id = $_GET['lottery_id'];

$noncestr = uniqid();
/**
 * 验证码，4位，默认不需要填写。如果调用此接口后返回需要验证码（返回码10008），则该接口会同时返回验证码url。开发者需要将验证码在页面中展示，并将用户填写的验证码传入进来
 * @var unknown
 */
$captcha = $_GET['captcha'];

/**
 * 通过addlottery接口设置的key
 * @var unknown
 */
$key = $_GET['key'];

$vars = $_POST;
unset($vars['key']);
$vars = YDWXRequest::ignoreNull($vars);
$vars = ksort($vars);
$str  = ydwx_json_encode($vars);
$sign = strtoupper(md5(urldecode($str)."&key=".$key));

$http = new YDHttp();
$info = $http->get("http://yao.qq.com/lottery/drawlottery?".http_build_query($vars)."&sign={$sign}");
$rst = new YDWXYaoTvDrawResult($info);
return json_encode($rst);
    