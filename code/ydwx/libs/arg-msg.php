<?php
/**
 * 企业号模板消息参数
 * @author leeboo
 *
 */
class YDWXTemplate extends YDWXArg{
    /**
     * 接收者openid
     * @var unknown
     */
    public $touser;
    public $template_id;
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
}

/**
 * 图文消息参数对象
 * @author leeboo
 *
 */
class YDWXNewArg extends YDWXArg{
    public $title;
    public $description;
    public $url;
    public $picurl;
}
/**
 * 图文消息参数对象,与YDWXNewArg的区别是消息存储在微信上
 * @author leeboo
 *
 */
class YDWXMpNewArg extends YDWXArg{
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
}
/**
 * 视频参数对象
 * @author leeboo
 *
 */
class YDWXVideoArg extends YDWXArg{
    public $title;
    public $description;
    /**
     * 可通过ydwx_media_upload()上传图片后获得media id
     * @var unknown
     */
    public $media_id;
}
/**
 * 音乐参数对象
 * @author leeboo
 *
 */
class YDWXMusicArg extends YDWXArg{
    public $title;
    public $description;
    public $music_url;
    public $hq_music_url;
    public $thumb_media_id;
}
interface YDWXMsgArg{
    /**
     * 构建文本消息
     * @param unknown $text
     * @return YDWXMsgArg
     */
    public static function buildTextMsg($text);
    /**
     * 构建图片消息
     *
     * @param unknown $media_id 可通过ydwx_media_upload()上传图片后获得media id
     * @return YDWXMsgArg
     */
    public static function buildImageMsg($media_id);

    /**
     * 构建语音消息
     *
     * @param unknown $media_id 可通过ydwx_media_upload()上传图片后获得media id
     * @return YDWXMsgArg
     */
    public static function buildVoiceMsg($media_id);
    /**
     * 构建视频消息
     *
     * @param YDWXVideoArg $arg
     * @return YDWXMsgArg
    */
    public static function buildVideoMsg(YDWXVideoArg $arg);
    /**
     * 构建音乐消息
     * @param unknown $text
     * @return YDWXMsgArg
     */
    public static function buildMusicMsg(YDWXMusicArg $arg);
    /**
     * 构建图文消息
     * @param unknown $text
     * @return YDWXMsgArg
     */
    public static function buildNewsMsg(YDWXNewArg $arg);
    
    /**
     * 构建卡券消息
     * @param unknown $text
     * @return YDWXMsgArg
     */
    public static function buildWXCard($card_id);
    /**
     * 根据图文id构建图文消息
     * @param unknown $media_id
     */
    public static function buildMPNewsMsgByID($media_id);
}

/**
 * 被动回复消息
 * @author leeboo
 *
 */
class YDWXAnswerMsg extends YDWXArg implements YDWXMsgArg{
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
    
    public static function buildNewsMsg(YDWXNewArg $var){
        $msg = new YDWXAnswerMsg();
        $msg->MsgType  = "news";
        $msg->Articles = array();
        
        $num = min(func_num_args(), 10);
        $msg->ArticleCount = $num;
        
        for($i=0; $i< $num; $i++){
            /**
             * @var YDWXNewArg
             */
            $arg = func_get_arg($i);
            $items = array();
            $item['Title']       = $arg->title;
            $item['Description'] = $arg->description;
            $item['PicUrl']      = $arg->picurl;
            $item['Url']         = $arg->url;
            $msg->Articles[$i]['item']      = $item;
        }
        return $msg;
    }
    
    public static function buildMPNewsMsg(YDWXMpNewArg $var){
        throw new YDWXException("公众号不支持回复MpNew, 请使用buildNewsMsg");
    }
    
    public static function buildFileMsg($media_id){
        throw new YDWXException("公众号不支持回复文件");
    }
    
    public static function buildTextMsg($text){
        $msg = new YDWXAnswerMsg();
        $msg->MsgType  = "text";
        $msg->Content  = $text;
        return $msg;
    }
    
    public static function buildImageMsg($media_id){
        $msg = new YDWXAnswerMsg();
        $msg->MsgType  = "image";
        $msg->Image    = array("MediaId" => $media_id);
        return $msg;
    }
    
    public static function buildVoiceMsg($media_id){
        $msg = new YDWXAnswerMsg();
        $msg->MsgType  = "voice";
        $msg->Voice    = array("MediaId" => $media_id);
        return $msg;
    }
    
    public static function buildVideoMsg(YDWXVideoArg $arg){
        $msg = new YDWXAnswerMsg();
        $msg->MsgType    = "video";
        $args = array();
        $args['MediaId'] = $arg->media_id;
        $args['Title']   = $arg->title;
        $args['Description'] = $arg->description;
        sort($args);
        $msg->Video      = $args;
        return $msg;
    }
    
    public static function buildMusicMsg(YDWXMusicArg $arg){
        $msg = new YDWXAnswerMsg();
        $msg->MsgType  = "music";
        $args = array();
        $args['MusicUrl']       = $arg->music_url;
        $args['Title']          = $arg->title;
        $args['Description']    = $arg->description;
        $args['HQMusicUrl']     = $arg->hq_music_url;
        $args['ThumbMediaId']   = $arg->thumb_media_id;
        sort($args);
        $msg->Music    = $args;
        return $msg;
    }
    
    public static function buildWXCard($card_id){
        throw new YDWXException("公众号不支持回复卡券");
    }
    
    public static function buildMPNewsMsgByID($media_id){
        throw new YDWXException("公众号不支持回复MpNew, 请使用buildNewsMsg");
    }
    
    public function valid(){
        
    }
}
/**
 * 公众号群发消息
 * @author leeboo
 *
 */
class YDWXMassMsgArg extends YDWXArg implements YDWXMsgArg{
    /**
     * 接受者openid，最少两个
     * @var unknown
     */
    public $touser; 
    
    private $msgtype;
    private $news;
    private $file;
    private $text;
    private $mpnews;
    private $video;
    private $voice;
    private $image;
    

    public static function buildNewsMsg(YDWXNewArg $var){
        throw new YDWXException("公众号图文群发请使用buildMPNewsMsgByID");
    }
    
    public static function buildMPNewsMsg(YDWXMpNewArg $var){
        throw new YDWXException("公众号图文群发请使用buildMPNewsMsgByID");
    }
    
    public static function buildFileMsg($media_id){
        throw new YDWXException("公众号不支持群发文件");
    }
    
    public static function buildTextMsg($text){
        $msg = new YDWXMassMsgArg();
        $msg->msgtype  = "text";
        $msg->text    = array("content" => $text);
        return $msg;
    }
    
    public static function buildImageMsg($media_id){
        $msg = new YDWXMassMsgArg();
        $msg->msgtype  = "image";
        $msg->image    = array("media_id" => $media_id);
        return $msg;
    }
    
    public static function buildVoiceMsg($media_id){
        $msg = new YDWXMassMsgArg();
        $msg->msgtype  = "voice";
        $msg->voice    = array("media_id" => $media_id);
        return $msg;
    }
    
    public static function buildVideoMsg(YDWXVideoArg $arg){
        $msg = new YDWXMassMsgArg();
        $msg->msgtype  = "video";
        $msg->video    = $arg->toArray();
        return $msg;
    }
    
    public static function buildMusicMsg(YDWXMusicArg $arg){
        $msg = new YDWXMassMsgArg();
        $msg->msgtype  = "music";
        $msg->music    = $arg->toArray();
        return $msg;
    }
    
    public static function buildWXCard($card_id){
        $msg = new YDWXMassMsgArg();
        $msg->msgtype  = "wxcard";
        $msg->wxcard   = array("card_id" => $media_id);
        return $msg;
    }
    
    public static function buildMPNewsMsgByID($media_id){
        $msg = new YDWXQyMsgArg();
        $msg->msgtype  = "mpnews";
        $msg->mpnews    = array("media_id" => $media_id);
        return $msg;
    }
    
    public function valid(){
        $this->touser =  (array)$this->touser;
        if(count($this->touser)<=2) throw new YDWXException("群发接口接收者最少2个");
    }
}
/**
 * 企业号发送的消息格式
 * @author leeboo
 *
 */
class YDWXQyMsgArg extends YDWXArg implements YDWXMsgArg{
    /**
     * 成员ID列表（消息接收者，多个接收者用‘|’分隔，最多支持1000个）。特殊情况：指定为@all，
     * 则向关注该企业应用的全部成员发送
     * @var unknown
     */
    public $touser = "@all";
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
    
    private $msgtype;
    private $news;
    private $file;
    private $text;
    private $mpnews;
    private $video;
    private $voice;
    private $image;
    
    public static function buildNewsMsg(YDWXNewArg $var){
        $msg = new YDWXQyMsgArg();
        $msg->msgtype   = "news";
        $num = min(func_num_args(), 10);
        for($i=0; $i< $num; $i++){
            $arg = func_get_arg($i);
            $msg->news['articles'][]      = $arg->toArray();
        }
        return $msg;
    }
    
    public static function buildMPNewsMsg(YDWXMpNewArg $var){
        $msg = new YDWXQyMsgArg();
        $msg->msgtype   = "mpnews";
        $num = min(func_num_args(), 10);
        for($i=0; $i< $num; $i++){
            $arg = func_get_arg($i);
            $msg->mpnews['articles'][]      = $arg->toArray();
        }
        return $msg;
    }

    public static function buildFileMsg($media_id){
        $msg = new YDWXQyMsgArg();
        $msg->msgtype  = "file";
        $msg->file    = array("media_id" => $media_id);
        return $msg;
    }
    
    public static function buildTextMsg($text){
        $msg = new YDWXQyMsgArg();
        $msg->msgtype  = "text";
        $msg->text    = array("content" => $text);
        return $msg;
    }
    
    public static function buildImageMsg($media_id){
        $msg = new YDWXQyMsgArg();
        $msg->msgtype  = "image";
        $msg->image    = array("media_id" => $media_id);
        return $msg;
    }
    
    public static function buildVoiceMsg($media_id){
        $msg = new YDWXQyMsgArg();
        $msg->msgtype  = "voice";
        $msg->voice    = array("media_id" => $media_id);
        return $msg;
    }
    
    public static function buildVideoMsg(YDWXVideoArg $arg){
        $msg = new YDWXQyMsgArg();
        $msg->msgtype  = "video";
        $msg->video    = $arg->toArray();
        return $msg;
    }
    
    public static function buildMusicMsg(YDWXMusicArg $arg){
        throw new YDWXException("企业号不支持发送音乐消息");
    }
    
    public static function buildWXCard($card_id){
        throw new YDWXException("企业号不支持发送音乐消息");
    }
    
    public static function buildMPNewsMsgByID($media_id){
        $msg = new YDWXQyMsgArg();
        $msg->msgtype  = "mpnews";
        $msg->mpnews    = array("media_id" => $media_id);
        return $msg;
    }
    public function valid(){
        if($this->touser && is_array($this->touser)){
            $this->touser =  join("|", $this->touser);
        }
        if($this->toparty && is_array($this->toparty)){
            $this->toparty = join("|", $this->toparty);
        }
        if($this->totag && is_array($this->totag)){
            $this->totag   = join("|", $this->totag);
        }
    }
}