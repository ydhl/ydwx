<?php
class KFAccount{
    public $kf_account;
    public $kf_nick; 
    public $kf_id;
    public $kf_headimgurl;
}
/**
 * 客服发消息
 *
 * 当用户主动发消息给公众号的时候（包括发送信息、点击自定义菜单、订阅事件、扫描二维码事件、支付成功事件、用户维权），
 * 微信将会把消息数据推送给开发者，开发者在一段时间内（目前修改为48小时）可以调用客服消息接口，
 * 通过POST一个JSON数据包来发送消息给普通用户，在48小时内不限制发送次数。
 * 此接口主要用于客服等有人工消息处理环节的功能
 *
 * @param String $accessToken;
 * @param YDWXMassCustomRequest $arg
 * @return YDWXResponse rst
 * @see http://mp.weixin.qq.com/wiki/1/70a29afed17f56d537c833f89be979c9.html#.E5.AE.A2.E6.9C.8D.E6.8E.A5.E5.8F.A3-.E5.8F.91.E6.B6.88.E6.81.AF
 */
function ydwx_message_custom_send($accessToken, YDWXMassCustomRequest $arg){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."message/custom/send?access_token={$accessToken}",
    $arg->toJSONString());
    $rst = new YDWXResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg);

    return $rst;
}

/**
 * 通过本接口为公众号添加客服账号，每个公众号最多添加10个客服账号
 * 
 * @param unknown $accessToken
 * @param unknown $kfaccount test1@test 账号前缀@公众号微信号
 * @param unknown $nickname 客服1
 * @param unknown $password pswmd5
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_kfaccount_add($accessToken, $kfaccount, $nickname, $password){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."customservice/kfaccount/add?access_token={$accessToken}",
    ydwx_json_encode(array(
        "kf_account"=>$kfaccount,
        "nickname"  =>$nickname,
        "password"  =>$password,
    )));
    $rst = new YDWXResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg);

    return $rst;
}

/**
 * 通过本接口为公众号修改客服账号
 * 
 * @param unknown $accessToken 
 * @param unknown $kfaccount 账号前缀@公众号微信号
 * @param unknown $nickname
 * @param unknown $password
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_kfaccount_update($accessToken, $kfaccount, $nickname, $password){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."customservice/kfaccount/update?access_token={$accessToken}",
    ydwx_json_encode(array(
            "kf_account"=>$kfaccount,
            "nickname"  =>$nickname,
            "password"  =>$password,
    )));
    $rst = new YDWXResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg);

    return $rst;
}
/**
 * 为公众号删除客服帐号
 *
 * @param unknown $accessToken
 * @param unknown $kfaccount 账号前缀@公众号微信号
 * @param unknown $nickname
 * @param unknown $password
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_kfaccount_del($accessToken, $kfaccount, $nickname, $password){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."customservice/kfaccount/del?access_token={$accessToken}",
    ydwx_json_encode(array(
            "kf_account"=>$kfaccount,
            "nickname"  =>$nickname,
            "password"  =>$password,
    )));
    $rst = new YDWXResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg);

    return $rst;
}
/**
 * 上传图片作为客服人员的头像，头像图片文件必须是jpg格式，推荐使用640*640大小的图片以达到最佳效果
 * @param unknown $accessToken
 * @param unknown $kfaccount 账号前缀@公众号微信号
 * @param unknown $headimg 头像绝对路径
 * @throws YDWXException
 * @return YDWXResponse
 */
function ydwx_kfaccount_uploadheadimg($accessToken, $kfaccount, $headimg){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."customservice/kfaccount/uploadheadimg?access_token={$accessToken}&kf_account={$kfaccount}",
    array(
            "media"=>"@".$headimg
    ), true);
    $rst = new YDWXResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg);

    return $rst;
}

/**
 * 获取公众号中所设置的客服基本信息，包括客服工号、客服昵称、客服登录账号。
 * 
 * @param unknown $accessToken
 * @throws YDWXException
 * @return multitype:KFAccount
 */
function ydwx_kfaccount_getall($accessToken){
    $http = new YDHttp();
    $info = $http->post(YDWX_WEIXIN_BASE_URL."customservice/getkflist?access_token={$accessToken}");
    $rst = new YDWXResponse($info);
    if( ! $rst->isSuccess()) throw new YDWXException($rst->errmsg);
    $rsts = array();
    foreach($rst->kf_list as $list){
        $kf = new KFAccount();
        $kf->kf_account     = $list['kf_account'];
        $kf->kf_nick        = $list['kf_nick'];
        $kf->kf_id          = $list['kf_id'];
        $kf->kf_headimgurl  = $list['kf_headimgurl'];
        $rsts[] = $kf;
    }
    return $rsts ;
}