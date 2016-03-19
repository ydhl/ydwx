<?php
function ydwx_jsapi_include(){
?>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
<?php
}
/**
 * 所有微信 jsapi调用前都需要先调用该方法
 * @param array $jsApiList 见YDWX_JSAPI_XX常量
 * @param $appid 作为第三方平台，配置某个授权公众号
 * @param $type 见YDWX_WEIXIN_TYPE_XX
 */
function ydwx_jsapi_config(array $jsApiList, $appid=null, $type=YDWX_WEIXIN_TYPE_NORMAL){
    $curr_page_uri= YDWX_SITE_URL.ltrim($_SERVER['REQUEST_URI'], "/");
    if($type==YDWX_WEIXIN_TYPE_AGENT){
        $jsapi_ticket = YDWXHook::do_hook(YDWXHook::GET_HOST_JSAPI_TICKET, $appid);
    }else if($type==YDWX_WEIXIN_TYPE_CROP){
    	$appid = YDWX_WEIXIN_CROP_ID;
    	$jsapi_ticket = YDWXHook::do_hook(YDWXHook::GET_JSAPI_TICKET);
    }else{
        $appid = YDWX_WEIXIN_APP_ID;
        $jsapi_ticket = YDWXHook::do_hook(YDWXHook::GET_JSAPI_TICKET);
    }
    
    ob_start();
    ?>
    <?php echo ydwx_jsapi_include();?>
    <script type="text/javascript">
        <?php
        $time       = time();
        $nonceStr   = uniqid();
        $signStr    = sha1("jsapi_ticket={$jsapi_ticket}&noncestr={$nonceStr}&timestamp={$time}&url=".$curr_page_uri);
        ?>
        wx.config({
            debug: false,
            appId: '<?php echo $appid?>', // 必填，公众号的唯一标识
            timestamp:'<?php echo $time?>' , // 必填，生成签名的时间戳
            nonceStr: '<?php echo $nonceStr?>', // 必填，生成签名的随机串
            signature: '<?php echo $signStr?>',// 必填，签名，见附录1
            jsApiList: <?php echo json_encode($jsApiList)?> // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });
        wx.error(function(res){
            alert("<?php echo "jsapi_ticket={$jsapi_ticket}&noncestr={$nonceStr}&timestamp={$time}&url=".$curr_page_uri;?>");
            alert(JSON.stringify(res));
        });
    </script>
    <?php 
        return ob_get_clean();
}
/**
 * 控制分享菜单的js, 在分享时自定义分享的标题、描述和图片
 * 该函数会输出js处理代码,但需要先ydwx_jsapi_config(array(
 *  YDWX_JSAPI_SHOWOPTIONMENU, YDWX_JSAPI_ONMENUSHAREAPPMESSAGE, YDWX_JSAPI_ONMENUSHAREQQ,YDWX_JSAPI_ONMENUSHAREWEIBO, YDWX_JSAPI_ONMENUSHARETIMELINE))
 * 
 * @param $jsapi_ticket jsticket，通过/ydhwx/refresh.php会定时自动刷新得到
 * @param $curr_page_uri 当前页面的地址
 * @param $share_title 分享标题
 * @param $share_desc   分享描述
 * @param $share_image 分享现实的图片地址
 * @param $link_target  点击分享后的目标地址 
 * @param $jscallback  分享成功后回调的js函数名
 * 
 * @return string js
 */
function ydwx_custom_share_script($share_title,$share_desc,$share_image, $link_target, $jscallback=null){
    ob_start();
?>
    
    <script type="text/javascript">
        var shareMessage = {
                title: "<?php  echo $share_title?>",
                desc: "<?php   echo $share_desc?>",
                link: "<?php   echo $link_target ?>",
                imgUrl: "<?php echo $share_image?>",
                success: function () { 
                    <?php echo $jscallback ?  "{$jscallback}();" : ""?>
                }
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

function ydwx_weboath_script($qrcode_container_id, $state, $style="black", $href=""){
if( ! YDWX_WEIXIN_WEB_APP_ID)return ;
?>
<script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
<script type="text/javascript">
 var obj = new WxLogin({
      id:"<?php echo $qrcode_container_id?>", 
      appid: "<?php echo YDWX_WEIXIN_WEB_APP_ID?>", 
      scope: "snsapi_login", 
      redirect_uri: "<?php echo YDWX_SITE_URL."ydwx/webauth.php"?>",
      state: "<?php echo $state?>",
      style: "<?php echo $style?>",
      href: "<?php echo $href?>"
    });
 </script>
<?php 
}

/**
 * 输出调起微信扫一扫js脚本，输出后立即执行，也可以放在一个js function中在调用
 * @param $needResult boolean  true 返回扫码结果
 * @param $jscallback js回调方法名， $needResult 为 true 时会回调jscallback，并把结果传入
 */
function ydwx_scanQRCode_script($needResult, $jscallback){
    ob_start();
?>
    wx.scanQRCode({
        needResult: <?php echo $needResult ? 1 : 0?>, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
        scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
        success: function (res) {
            var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
            <?php echo $jscallback ? "{$jscallback}(result)" : ""?>;
        }
    });
<?php 
    return ob_get_clean();
}

/**
 * 调用微信进行定位
 * 
 * @param $type 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
 * @param $jscallback 定位成功后回调的js函数名，传入参数
 *  latitude 纬度，浮点数，范围为90 ~ -90
 *  longitude 经度，浮点数，范围为180 ~ -180。
 *  speed 速度，以米/每秒计
 *  accuracy 位置精度
 */
function ydwx_getLocation_script($jscallback,$type="wgs84"){
    ob_start();
?>
    wx.getLocation({
        type: '<?php echo $type?>', 
        success: function (res) {
            <?php echo "$jscallback(res.latitude, res.longitude, res.speed, res.accuracy);"?>
        }
    });
<?php 
    return ob_get_clean();
}

/**
 * 
 * @param unknown $jscallback 图片选择后回调的js函数名，传入参数ids，选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
 * @param number $count 能选择的图片数量，默认9
 * @param string $sizeType 可以指定是原图还是压缩图，all:默认二者都有，original：原图 ；compressed：压缩图
 * @param string $sourceType 可以指定来源是相册还是相机，默认二者都有,all:默认二者都有，album：相册 ；camera：相机
 * @return string
 */
function ydwx_chooseImage_script($jscallback, $count=9,$sizeType="all",$sourceType="all"){
ob_start();
?>
    wx.chooseImage({
        count: <?php echo $count?>,
        sizeType: [<?php echo $sizeType=="all" ? "'original', 'compressed'" : "'{$sizeType}'"?>], 
        sourceType: [<?php echo $sourceType=="all" ? "'album', 'camera'" : "'{$sourceType}'"?>], 
        success: function (res) {
            <?php echo "{$jscallback}(res.localIds);"?>
        }
    });
<?php 
    return ob_get_clean();
}

/**
 * 输出上传图片到微信服务器js方法ydwx_uploadImage，调用方法
 * ydwx_uploadImage(localid,jscallback)
 * localid:需要上传的图片的本地ID，由chooseImage接口获得
 * jscallback:上传成功回调，参数为serverId，上传成功后微信服务器返回的id
 * 此处获得的 serverId 即 media_id，可通过ydwx_media_get方法下载到自己的服务器端
 * 
 * @return string
 */
function ydwx_uploadImage_script(){
ob_start();
?>
function ydwx_uploadImage(localid, jscallback){
    setTimeout(function(){
        wx.uploadImage({
            localId: localid,
            isShowProgressTips: 1,
            success: function (res) {
                var serverId = res.serverId;
                jscallback(localid, serverId);
            }
        });
    },500);
}
<?php 
return ob_get_clean();
}
/**
 * 调用微信阅览接口，传入需要预览的图片地址数组
 * @return string
 */
function ydwx_previewImage_script(){
    ob_start();
    ?>
    function ydwx_previewImage(urls){
        wx.previewImage({
            current: window.document.href,
            urls: urls
        });
    }
<?php 
return ob_get_clean();
}