<?php
/**
 *
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/11/0e4b294685f817b95cbed85ba5e82b8f.html
 */
class YDWXAccessTokenResponse extends YDWXResponse{
    public $access_token;
    public $expires_in;
}

/**
 * 刷新授权公众号的令牌数据
 * @see https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1419318587&lang=zh_CN
 * @author leeboo
 *
 */
class YDWXAuthorizerTokenResponse extends YDWXResponse{
    public $authorizer_access_token;
    public $expires_in;
    public $authorizer_refresh_token;
}

/**
 *
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html#.E9.99.84.E5.BD.951-JS-SDK.E4.BD.BF.E7.94.A8.E6.9D.83.E9.99.90.E7.AD.BE.E5.90.8D.E7.AE.97.E6.B3.95
 */
class YDWXJsapiTicketResponse extends YDWXResponse{
    public $ticket;
    public $expires_in;
}