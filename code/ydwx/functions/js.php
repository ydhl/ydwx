<?php
/**
 * 控制分享菜单的js, 在分享时自定义分享的标题、描述和图片
 * 该函数会输出js处理代码,但需要先引入<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
 * 
 * @param $jsapi_ticket jsticket，通过/ydhwx/refresh.php会定时自动刷新得到
 * @param $curr_page_uri 当前页面的地址
 * @param $share_title 分享标题
 * @param $share_desc   分享描述
 * @param $share_image 分享现实的图片地址
 * @param $link_target  点击分享后的目标地址 
 * 
 * @return string js
 */
function ydwx_sharehandle_script($share_title,$share_desc,$share_image, $link_target){
    $jsapi_ticket = YDWXHook::do_hook(YDWXHook::GET_JSAPI_TICKET);
    $curr_page_uri= YDWX_SITE_URL.ltrim($_SERVER['REQUEST_URI'], "/");
    ob_start();
?>
    
    <script type="text/javascript">
    
        <?php
        $time       = time();
        $nonceStr   = uniqid();
        $signStr    = sha1("jsapi_ticket={$jsapi_ticket}&noncestr={$nonceStr}&timestamp={$time}&url=".$curr_page_uri);
        ?>
        wx.config({
            debug: false,
            appId: '<?php echo YDWX_WEIXIN_APP_ID?>', // 必填，公众号的唯一标识
            timestamp:'<?php echo $time?>' , // 必填，生成签名的时间戳
            nonceStr: '<?php echo $nonceStr?>', // 必填，生成签名的随机串
            signature: '<?php echo $signStr?>',// 必填，签名，见附录1
            jsApiList: ['onMenuShareAppMessage','onMenuShareTimeline','onMenuShareQQ','onMenuShareWeibo','showOptionMenu'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });
        wx.error(function(res){
            alert(JSON.stringify(res));
        });
        var shareMessage = {
                title: "<?php  echo $share_title?>",
                desc: "<?php   echo $share_desc?>",
                link: "<?php   echo $link_target ?>",
                imgUrl: "<?php echo $share_image?>"
            };
        var shareTimeMessage = {};
        $.extend(shareTimeMessage, shareMessage);
        delete shareTimeMessage["desc"];
        
        wx.ready(function(){
            wx.showOptionMenu();
            wx.onMenuShareAppMessage(shareMessage);
            wx.onMenuShareTimeline(shareTimeMessage);
            wx.onMenuShareQQ(shareMessage);
            wx.onMenuShareWeibo(shareMessage);
        });
    </script>
<?php 
    return ob_get_clean();
}

function ydwx_weboath_script($qrcode_container_id, $state, $style="black", $href){
?>
<script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
<script type="text/javascript">
 var obj = new WxLogin({
      id:"<?php echo $qrcode_container_id?>", 
      appid: "<?php YDWX_WEIXIN_WEB_APP_ID?>", 
      scope: "snsapi_login", 
      redirect_uri: "<?php echo YDWX_SITE_URL."ydwx/webauth.php"?>",
      state: "<?php echo $state?>",
      style: "<?php echo $style?>",
      href: "<?php echo $href?>"
    });
 </script>
<?php 
}