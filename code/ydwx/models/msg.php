<?php
/**
 * 模板消息发送后返回结构
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/17/304c1885ea66dbedf7dc170d84999a9d.html#.E5.8F.91.E9.80.81.E6.A8.A1.E6.9D.BF.E6.B6.88.E6.81.AF
 */
class YDWXTemplateResponse extends YDWXResponse{
    public $msgid;
}
/**
 * 群发消息发送后返回结构
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/15/5380a4e6f02f2ffdc7981a8ed7a40753.html#.E6.A0.B9.E6.8D.AEOpenID.E5.88.97.E8.A1.A8.E7.BE.A4.E5.8F.91.E3.80.90.E8.AE.A2.E9.98.85.E5.8F.B7.E4.B8.8D.E5.8F.AF.E7.94.A8.EF.BC.8C.E6.9C.8D.E5.8A.A1.E5.8F.B7.E8.AE.A4.E8.AF.81.E5.90.8E.E5.8F.AF.E7.94.A8.E3.80.91
 */
class YDWXMassResponse extends YDWXResponse{
    /**
     * 消息发送任务的ID
     * @var unknown
     */
    public $msg_id;
    /**
     * 消息的数据ID，，该字段只有在群发图文消息时，才会出现。可以用于在图文分析数据接口中，
     * 获取到对应的图文消息的数据，是图文分析数据接口中的msgid字段中的前半部分，
     * 详见图文分析数据接口中的msgid字段的介绍。
     * @var unknown
     */
    public $msg_data_id;
}

/**
 * 公众号模板消息参数
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/17/304c1885ea66dbedf7dc170d84999a9d.html
 */
class YDWXTemplateRequest extends YDWXRequest{
    /**
     * 接收者openid
     * @var unknown
     */
    public $touser;
    /**
     * 模板id
     * @var unknown
     */
    public $template_id;
    /**
     * 点击进入地址
     * @var unknown
     */
    public $url;
    public $topcolor;
    /**
     * 根据$template_id的数据格式设置, 如：
     * {
     *  "消息关键字": {
     *      "value":"内容",
     *      "color":"字体颜色，如#ff0000"
     *  },
     * }
     * @var array
     */
    public $data;
    public function valid(){
        if( ! $this->touser){
            throw new YDWXException("接受者不能为空");
        }
    }
}
/**
 * 图文消息参数对象
 * @author leeboo
 *
 */
class YDWXNewsMsg extends YDWXRequest{
    public $title;
    public $description;
    public $url;
    public $picurl;
    public function valid(){
    
    }
}

/**
 * 图文消息参数对象,与YDWXNewsMsg的区别是消息存储在微信上
 * @author leeboo
 *
 */
class YDWXMpNewsMsg extends YDWXRequest{
    /**
     * 图文消息的标题
     * @var unknown
     */
    public $title;
    /**
     * 图文消息缩略图的media_id, 可以在上传多媒体文件接口中获得。
     * 此处thumb_media_id即上传接口ydwx_media_upload返回的media_id
     * @var unknown
     */
    public $thumb_media_id;
    /**
     * 图文消息的作者
     * @var unknown
     */
    public $author;
    /**
     * 图文消息点击“阅读原文”之后的页面链接
     * @var unknown
     */
    public $content_source_url;
    /**
     * 图文消息的内容，支持html标签
     * @var unknown
     */
    public $content;
    /**
     * 图文消息的描述
     * @var unknown
     */
    public $digest;
    /**
     * 是否显示封面，1为显示，0为不显示
     * @var unknown
     */
    public $show_cover_pic;
    public function valid(){
        
    }
}


/**
 * 视频消息对象
 * @author leeboo
 *
 */
class YDWXVideoMsg extends YDWXRequest{
    public $title;
    public $description;
    /**
     * 可通过ydwx_media_upload()上传图片后获得media id
     * @var unknown
     */
    public $media_id;
    public function valid(){
    
    }
}

/**
 * 微信消息构造接口
 * 
 * @author leeboo
 *
 */
interface YDWXMsgBuilder{
    /**
     * 构建文本消息
     * @param unknown $text
     * @return YDWXMsgBuilder
     */
    public static function buildTextMsg($text);
    /**
     * 构建图片消息
     *
     * @param unknown $media_id 可通过ydwx_media_upload()上传图片后获得media id
     * @return YDWXMsgBuilder
    */
    public static function buildImageMsg($media_id);

    /**
     * 构建语音消息
     *
     * @param unknown $media_id 可通过ydwx_media_upload()上传图片后获得media id
     * @return YDWXMsgBuilder
    */
    public static function buildVoiceMsg($media_id);
    /**
     * 构建视频消息
     *
     * @param YDWXVideoMsg $arg
     * @return YDWXMsgBuilder
    */
    public static function buildVideoMsg(YDWXVideoMsg $arg);
    /**
     * 构建音乐消息
     * @param YDWXMusicMsg $text
     * @return YDWXMsgBuilder
    */
    public static function buildMusicMsg(YDWXMusicMsg $arg);
    /**
     * 构建图文消息
     * @param array $arg YDWXNewsMsg数组
     * @return YDWXMsgBuilder
    */
    public static function buildNewsMsg(array $arg);

    /**
     * 构建卡券消息
     * @param unknown $text
     * @return YDWXMsgBuilder
    */
    public static function buildWXCardMsg($card_id);
    /**
     * 根据图文id构建图文消息
     * @param unknown $media_id
    */
    public static function buildMPNewsMsgByID($media_id);
    /**
     * @param array $arg YDWXMpNewsMsg数组
     */
    public static function buildMPNewsMsg(array $var);
}
/**
 * 群发消息请求参数
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/15/5380a4e6f02f2ffdc7981a8ed7a40753.html#.E6.A0.B9.E6.8D.AEOpenID.E5.88.97.E8.A1.A8.E7.BE.A4.E5.8F.91.E3.80.90.E8.AE.A2.E9.98.85.E5.8F.B7.E4.B8.8D.E5.8F.AF.E7.94.A8.EF.BC.8C.E6.9C.8D.E5.8A.A1.E5.8F.B7.E8.AE.A4.E8.AF.81.E5.90.8E.E5.8F.AF.E7.94.A8.E3.80.91
 */
class YDWXMassRequest extends YDWXRequest implements YDWXMsgBuilder{
    /**
     * -公众号根据openid 列表发送时是接受者openid，最少两个；
     * -公众号根据分组发送时是分组id
     * -企业号根据成员列表发送时是成员ID列表（消息接收者，多个接收者用‘|’分隔，最多支持1000个）。特殊情况：指定为@all，
     * 则向关注该企业应用的全部成员发送
     * @var array
     */
    public $to;

    protected $msgtype;
    protected $news;
    protected $file;
    protected $text;
    protected $mpnews;
    protected $video;
    protected $voice;
    protected $image;
    protected $wxcard;


    public static function buildNewsMsg(array $args){
        throw new YDWXException("公众号图文群发请使用buildMPNewsMsgByID");
    }

    public static function buildMPNewsMsg(array $var){
        throw new YDWXException("公众号图文群发请使用buildMPNewsMsgByID");
    }

    public static function buildFileMsg($media_id){
        throw new YDWXException("公众号不支持群发文件");
    }

    public static function buildTextMsg($text){
        $cls = get_called_class();
        $msg = new $cls();
        $msg->msgtype  = "text";
        $msg->text    = array("content" => $text);
        return $msg;
    }
    
    public static function buildImageMsg($media_id){
        $cls = get_called_class();
        /**
         * @var YDWXMassRequest
         */
        $msg = new $cls();
        $msg->msgtype  = "image";
        $msg->image    = array("media_id" => $media_id);
        return $msg;
    }

    public static function buildVoiceMsg($media_id){
        $cls = get_called_class();
        $msg = new $cls();
        $msg->msgtype  = "voice";
        $msg->voice    = array("media_id" => $media_id);
        return $msg;
    }

    public static function buildVideoMsg(YDWXVideoMsg $arg){
        $cls = get_called_class();
        $msg = new $cls();
        $msg->msgtype  = "video";
        $msg->video    = $arg->toArray();
        return $msg;
    }

    public static function buildMusicMsg(YDWXMusicMsg $arg){
        $cls = get_called_class();
        $msg = new $cls();
        $msg->msgtype  = "music";
        $msg->music    = $arg->toArray();
        return $msg;
    }

    public static function buildWXCardMsg($card_id){
        $cls = get_called_class();
        $msg = new $cls();
        $msg->msgtype  = "wxcard";
        $msg->wxcard   = array("card_id" => $card_id);
        return $msg;
    }

    public static function buildMPNewsMsgByID($media_id){
        $cls = get_called_class();
        $msg = new $cls();
        $msg->msgtype  = "mpnews";
        $msg->mpnews    = array("media_id" => $media_id);
        return $msg;
    }

    public function valid(){
        $this->to =  (array)$this->to;
        if(count($this->to)<=2) throw new YDWXException("群发接口接收者最少2个");
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['touser'] = $args['to'];
        unset($args['to']);
        return $args;
    }
}
class YDWXMassCustomRequest extends YDWXMassRequest{
    public $kf_account;
    public static function buildNewsMsg(array $msgs){
        $msg = new YDWXMassCustomRequest();
        $msg->msgtype   = "news";
        $num = min(count($msgs), 10);
        for($i=0; $i< $num; $i++){
            $arg = $msgs[$i];
            $msg->news['articles'][]      = $arg->toArray();
        }
        return $msg;
    }
    
    public static function buildMPNewsMsg(array $var){
        throw new YDWXException("客服消息不支持MPNews");
    }
    
    public static function buildFileMsg($media_id){
        throw new YDWXException("客服消息不支持发文件");
    }
    
    public static function buildVideoMsg(YDWXVideoMsg $arg){
        $msg = new YDWXMassCustomRequest();
        $msg->msgtype  = "video";
        $msg->video    = $arg->toArray();
        return $msg;
    }
    
    public static function buildMusicMsg(YDWXMusicMsg $arg){
        $msg = new YDWXMassCustomRequest();
        $msg->msgtype  = "music";
        $_ = array();
        $_['title']         = $arg->title;
        $_['description']   = $arg->description;
        $_['musicurl']      = $arg->music_url;
        $_['hqmusicurl']    = $arg->hq_music_url;
        $_['thumb_media_id']= $arg->thumb_media_id;
        $msg->music    = $_;
        return $msg;
    }
    
    public static function buildWXCardMsg($card_id){
        $msg = new YDWXMassCustomRequest();
        $msg->msgtype  = "wxcard";
        $msg->wxcard   = array("card_id" => $media_id);
        return $msg;
    }
    
    public static function buildMPNewsMsgByID($media_id){
        throw new YDWXException("客服消息不支持MPNews");
    }
    
    public function valid(){
        if( ! $this->to ) throw new YDWXException("客服消息缺少接受者");
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['touser'] = $this->to;
        if($this->kf_account){
            $args['customservice']['kf_account'] = $this->kf_account;
        }
        unset($args['to']);
        return $args;
    }
}
/**
 * 群发消息阅览请求参数
 * @author leeboo
 *
 */
class YDWXMassPreviewRequest extends YDWXMassRequest{
    /**
     * 自定微信号进行阅览，如果设置该值，则忽略to参数
     * @var unknown
     */
    public $towxname;
    public function valid(){
        if( $this->to || $this->towxname ) throw new YDWXException("客服消息缺少接受者");
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        if( ! $args['towxname']){
            $args['touser'] = $args['to'];            
        }
        unset($args['to']);
        return $args;
    }
}
/**
 * 根据分组群发消息
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/15/5380a4e6f02f2ffdc7981a8ed7a40753.html#.E6.A0.B9.E6.8D.AE.E5.88.86.E7.BB.84.E8.BF.9B.E8.A1.8C.E7.BE.A4.E5.8F.91.E3.80.90.E8.AE.A2.E9.98.85.E5.8F.B7.E4.B8.8E.E6.9C.8D.E5.8A.A1.E5.8F.B7.E8.AE.A4.E8.AF.81.E5.90.8E.E5.9D.87.E5.8F.AF.E7.94.A8.E3.80.91
 */
class YDWXMassByGroupRequest extends YDWXMassRequest{
    /**
     * 是否发送给所有人,is_to_all为true则忽略to
     * @var array
     */
    public $is_to_all;
    
    public function valid(){
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['filter'] = array(
            "is_to_all"=>$this->is_to_all,
            "group_id"=>$this->to
        );
        unset($args['is_to_all']);
        unset($args['to']);
        return $args;
    }
    public static function buildVideoMsg(YDWXVideoMsg $arg){
        $msg = new YDWXMassByGroupRequest();
        $msg->msgtype  = "mpvideo";
        $msg->video    = $arg->toArray();
        return $msg;
    }
}

/**
 * 音乐消息对象
 * @author leeboo
 *
 */
class YDWXMusicMsg extends YDWXRequest{
    public $title;
    public $description;
    public $music_url;
    public $hq_music_url;
    public $thumb_media_id;
    public function valid(){
    
    }
}


/**
 * 被动回复消息，用于在微信通知后进行的应答，如收到用户发送的消息或者点击菜单事件
 * @author leeboo
 * @see http://mp.weixin.qq.com/wiki/14/89b871b5466b19b3efa4ada8e577d45e.html
 */
class YDWXAnswerMsg extends YDWXRequest implements YDWXMsgBuilder{
    public $ToUserName;
    public $FromUserName;
    public $CreateTime;
    public $MsgType;

    private $Content;
    private $Image;
    private $Voice;
    private $Video;
    private $Music;
    private $Articles;
    
    protected function formatArgs(){
        //override 以便得到private属性
        return get_object_vars($this);
    }
    public static function buildNewsMsg(array $msgs, YDWXEvent $source=null){
        $msg = new YDWXAnswerMsg();
        $msg->MsgType  = "news";
        $msg->Articles = array();

        $num = min(count($msgs), 10);
        $msg->ArticleCount = $num;

        for($i=0; $i< $num; $i++){
            /**
             * @var YDWXNewsMsg
             */
            $arg = $msgs[$i];
            $item = array();
            $item['Title']       = $arg->title;
            $item['Description'] = $arg->description;
            $item['PicUrl']      = $arg->picurl;
            $item['Url']         = $arg->url;
            $msg->Articles[$i]['item']      = $item;
        }
        if($source){
            $msg->FromUserName = $source->ToUserName;
            $msg->ToUserName   = $source->FromUserName;
            $msg->CreateTime   = time();
        }
        return $msg;
    }

    public static function buildMPNewsMsg(array $var, YDWXEvent $source=null){
        throw new YDWXException("自动应答不支持回复MpNew");
    }

    public static function buildFileMsg($media_id, YDWXEvent $source=null){
        throw new YDWXException("自动应答不支持回复文件");
    }

    public static function buildTextMsg($text, YDWXEvent $source=null){
        $msg = new YDWXAnswerMsg();
        $msg->MsgType  = "text";
        $msg->Content  = $text;
        if($source){
            $msg->FromUserName = $source->ToUserName;
            $msg->ToUserName   = $source->FromUserName;
            $msg->CreateTime   = time();
        }
        return $msg;
    }

    public static function buildImageMsg($media_id, YDWXEvent $source=null){
        $msg = new YDWXAnswerMsg();
        $msg->MsgType  = "image";
        $msg->Image    = array("MediaId" => $media_id);
        if($source){
            $msg->FromUserName = $source->ToUserName;
            $msg->ToUserName   = $source->FromUserName;
            $msg->CreateTime   = time();
        }
        return $msg;
    }

    public static function buildVoiceMsg($media_id, YDWXEvent $source=null){
        $msg = new YDWXAnswerMsg();
        $msg->MsgType  = "voice";
        $msg->Voice    = array("MediaId" => $media_id);
        if($source){
            $msg->FromUserName = $source->ToUserName;
            $msg->ToUserName   = $source->FromUserName;
            $msg->CreateTime   = time();
        }
        return $msg;
    }

    public static function buildVideoMsg(YDWXVideoMsg $arg, YDWXEvent $source=null){
        $msg = new YDWXAnswerMsg();
        $msg->MsgType    = "video";
        $args = array();
        $args['MediaId'] = $arg->media_id;
        $args['Title']   = $arg->title;
        $args['Description'] = $arg->description;
        ksort($args);
        $msg->Video      = $args;
        if($source){
            $msg->FromUserName = $source->ToUserName;
            $msg->ToUserName   = $source->FromUserName;
            $msg->CreateTime   = time();
        }
        return $msg;
    }

    public static function buildMusicMsg(YDWXMusicMsg $arg, YDWXEvent $source=null){
        $msg = new YDWXAnswerMsg();
        $msg->MsgType  = "music";
        $args = array();
        $args['MusicUrl']       = $arg->music_url;
        $args['Title']          = $arg->title;
        $args['Description']    = $arg->description;
        $args['HQMusicUrl']     = $arg->hq_music_url;
        $args['ThumbMediaId']   = $arg->thumb_media_id;
        ksort($args);
        $msg->Music    = $args;
        if($source){
            $msg->FromUserName = $source->ToUserName;
            $msg->ToUserName   = $source->FromUserName;
            $msg->CreateTime   = time();
        }
        return $msg;
    }

    public static function buildWXCardMsg($card_id, YDWXEvent $source=null){
        throw new YDWXException("公众号不支持回复卡券");
    }

    public static function buildMPNewsMsgByID($media_id, YDWXEvent $source=null){
        throw new YDWXException("公众号不支持回复MpNew, 请使用buildNewsMsg");
    }

    public function valid(){

    }
}

/**
 * 企业号发送的消息格式
 * @author leeboo
 * @see http://qydev.weixin.qq.com/wiki/index.php?title=%E6%B6%88%E6%81%AF%E7%B1%BB%E5%9E%8B%E5%8F%8A%E6%95%B0%E6%8D%AE%E6%A0%BC%E5%BC%8F
 */
class YDWXQyMsgRequest extends YDWXMassRequest implements YDWXMsgBuilder{
    /**
     * 部门ID列表，多个接收者用‘|’分隔，最多支持100个。当touser为@all时忽略本参数
     * @var unknown
     */
    public $toparty;
    /**
     * 标签ID列表，多个接收者用‘|’分隔。当touser为@all时忽略本参数
     * @var unknown
     */
    public $totag;

    /**
     * 企业应用的id，整型。可在应用的设置页面查看
     * @var unknown
     */
    public $agentid;
    /**
     * 表示是否是保密消息，0表示否，1表示是，默认0
     * @var unknown
     */
    public $safe;

    public static function buildNewsMsg(array $vars){
        $msg = new YDWXQyMsgRequest();
        $msg->msgtype   = "news";
        $num = min(count($vars), 10);
        for($i=0; $i< $num; $i++){
            $arg = $vars[$i];
            $msg->news['articles'][]      = $arg->toArray();
        }
        return $msg;
    }

    public static function buildMPNewsMsg(array $var){
        $msg = new YDWXQyMsgRequest();
        $msg->msgtype   = "mpnews";
        $num = min(count($var), 10);
        for($i=0; $i< $num; $i++){
            $arg = $var[$i];
            $msg->mpnews['articles'][]      = $arg->toArray();
        }
        return $msg;
    }

    public static function buildFileMsg($media_id){
        $msg = new YDWXQyMsgRequest();
        $msg->msgtype  = "file";
        $msg->file    = array("media_id" => $media_id);
        return $msg;
    }


    public static function buildVideoMsg(YDWXVideoMsg $arg){
        $msg = new YDWXQyMsgRequest();
        $msg->msgtype  = "video";
        $msg->video    = $arg->toArray();
        return $msg;
    }

    public static function buildMusicMsg(YDWXMusicMsg $arg){
        throw new YDWXException("企业号不支持发送音乐消息");
    }

    public static function buildWXCardMsg($card_id){
        throw new YDWXException("企业号不支持发送音乐消息");
    }

    public static function buildMPNewsMsgByID($media_id){
        $msg = new YDWXQyMsgRequest();
        $msg->msgtype  = "mpnews";
        $msg->mpnews    = array("media_id" => $media_id);
        return $msg;
    }
    public function valid(){
        
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['touser'] = $args['to'];
        if($args['touser'] && is_array($args['touser'])){
            $args['touser'] =  join("|", $args['touser']);
        }
        if($args['toparty'] && is_array($args['toparty'])){
            $args['toparty'] = join("|", $args['toparty']);
        }
        if($args['totag'] && is_array($args['totag'])){
            $args['totag']   = join("|", $args['totag']);
        }
        unset($args['to']);
        return $args;
    }
}