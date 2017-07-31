<?php
/**
 * 菜单模型，封装通过API创建的
 * @author leeboo
 *
 */
class YDWXMenu{
    const TYPE_CLICK = "click";
    const TYPE_VIEW  = "view";
    const TYPE_SCANCODE_PUSH = "scancode_push";
    const TYPE_SCANCODE_WAITMSG = "scancode_waitmsg";
    const TYPE_PIC_SYSPHOTO = "pic_sysphoto";
    const TYPE_PIC_PHOTO_OR_ALBUM = "pic_photo_or_album";
    const TYPE_PIC_WEIXIN = "pic_weixin";
    const TYPE_LOCATION_SELECT = "location_select";
    
    public $name;
    public $key;
    public $type;
    public $url;
    public $sub_button = array();
    /**
     * 
     * @param array $msg [name=>"标题名称","type"=>YDWXMenu::TYPEXXX,"key"=>"菜单标识key","url"=跳转url,"sub_button"=[二级菜单]]
     * @return YDWXMenu
     */
    public static function build(array $msg){
        $obj = new YDWXMenu();
       
        $obj->name = $msg['name'];
        $obj->type = $msg['type'];
        $obj->key  = $msg['key'];
        $obj->url  = $msg['url'];
        
        $obj->sub_button = array();
        
        foreach ($msg['sub_button'] as $subbtn){
            $obj->sub_button[] = YDWXMenu::build($subbtn);
        }
        return $obj;
    }
    
    public function toArray(){
        $array = array();
        if ($this->type) $array['type'] = $this->type;
        if ($this->name) $array['name'] = $this->name;
        if ($this->key)  $array['key'] = $this->key;
        if ($this->url)  $array['url'] = $this->url;
        if ($this->sub_button) {
            foreach ($this->sub_button as $button){
                $array['sub_button'][] = $button->toArray();
            }
        }
        return $array;
    }
}

/**
 * 微信后台创建的菜单内容
 * @author leeboo
 *
 */
class YDWXSelfMenu extends YDWXMenu{
   /**
    * 跳转网页
    * @var unknown
    */
    const TYPE_VIEW  = "view";
    /**
     * 返回文本
     * @var unknown
     */
    const TYPE_TEXT  = "text";
    /**
     * 返回图片
     * @var unknown
     */
    const TYPE_IMG   = "img";
    /**
     * 返回图片
     * @var unknown
     */
    const TYPE_PHOTO = "photo";
    /**
     * 返回视频
     * @var unknown
     */
    const TYPE_VIDEO = "video";
    /**
     * 返回音频
     * @var unknown
     */
    const TYPE_VOICE = "voice";
    /**
     * 图文消息数组，每项是YDWXSelfMenuNewsInfo对象
     * 
     * @var unknown
     */
    public $news_info = array();
    public static function build(array $msg){
        $obj = new YDWXSelfMenu();
         
        $obj->name = $msg['name'];
        $obj->type = @$msg['type'];
        $obj->key  = @$msg['value'];
        $obj->url  = @$msg['url'];
    
        $obj->sub_button = array();
    
        foreach (@$msg['sub_button']['list'] as $subbtn){
            $obj->sub_button[] = YDWXMenu::build($subbtn);
        }
        foreach (@$msg['news_info']['list'] as $news){
            $obj->news_info[] = new YDWXSelfMenuNewsInfo($news);
        }
        return $obj;
    }
    
    public function toArray(){
        $array = array();
        if ($this->type) $array['type'] = $this->type;
        if ($this->name) $array['name'] = $this->name;
        if ($this->key)  $array['key'] = $this->key;
        if ($this->url)  $array['url'] = $this->url;
        if ($this->sub_button) {
            foreach ($this->sub_button as $button){
                $array['sub_button']['list'][] = $button->toArray();
            }
        }
        if ($this->news_info) {
            foreach ($this->news_info as $button){
                $array['news_info']['list'][] = $button->toArray();
            }
        }
        return $array;
    }
}

class YDWXSelfMenuNewsInfo{
    /**
     * 作者
     */
    public $author;
    /**
     * 正文的URL
     */
    public $content_url;
    /**
     * 封面图片的URL
     * @var unknown
     */
    public $cover_url;
    /**
     * 摘要
     * @var unknown
     */
    public $digest;
    /**
     * 是否显示封面，0为不显示，1为显示
     * @var unknown
     */
    public $show_cover;
    /**
     * 原文的URL，若置空则无查看原文入口
     */
    public $source_url;
    /**
     * 图文消息的标题
     * @var unknown
     */
    public $title;
    public function __construct($arr){
        foreach ($arr as $n=>$v){
            $this->$n = $v;
        }
    }
    public function toArray(){
        return get_object_vars($this);
    }
}

/**
 * ydwx 接口参数基类
 * 
 * @author leeboo
 *
 */
abstract class YDWXRequest{
    public $sign;
    public function __toString(){
        return $this->toString();
    }
    /**
     * 根据设置的属性及微信接口参数要求验证、构建数据，有问题抛出YDWXException
     * 这是在toString，toJSONString，toXMLString之前会调用的一步
     */
    public abstract function valid();
    
    public static function ignoreNull(array $args){
        $array = array();
        foreach($args as $name=>$value){
            if(is_array($value)){
                $array[$name] = YDWXRequest::ignoreNull($value);
            }else if( ! is_null($value)){
                $array[$name] = $value;
            }
        }
        return $array;
    }
    /**
     * 使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串，
     * 注意这里返回的字符串是urlencode格式的，在某些签名场合注意urldecode出原内容
     * @return string
     */
    public function toString(){
        $this->valid();
        $args = YDWXRequest::ignoreNull($this->sortArg());
        return http_build_query($args);
    }
    public function toJSONString(){
        $this->valid();
        $args = YDWXRequest::ignoreNull($this->sortArg());
        return ydwx_json_encode($args);
    }
    public function toArray(){
        $this->valid();
        return YDWXRequest::ignoreNull($this->sortArg());
    }
    public function toXMLString(){
        $this->valid();
        $args = YDWXRequest::ignoreNull($this->sortArg());
        
        $xml = "<xml>";
        foreach ($args as $name=>$value){
            if(is_array($value)){
                $xml .= "<{$name}>".$this->arrayToXml($value)."</{$name}>";
            }else{
                $xml .= "<{$name}><![CDATA[{$value}]]></{$name}>";
            }
        }
        return $xml."</xml>";
    }
    private function arrayToXml($array){
        $xml = "";
        foreach($array as $key => $value){
            if( ! is_numeric($key)){
                $xml .= "<{$key}>";
            }
            if( is_array($value)){
                $xml .= $this->arrayToXml($value);
            }else{
                $xml .= "<![CDATA[$value]]>"; 
            }
            if( ! is_numeric($key)){
                $xml .= "</{$key}>";
            }
        }
        return $xml;
    }
    /**
     * 构建自己的数据结构，默认实现是把所有的非null属性组成数组返回
     * @return multitype:
     */
    protected function formatArgs(){
        return YDWXRequest::ignoreNull(get_object_vars($this));
    }
    /**
     * 返回按字典排序后的属性数组,排序依据是key
     */
    public final function sortArg(){
        $args = $this->formatArgs();
        ksort($args);
        return $args;
    }
    /**
     * 根据微信的要求进行签名并设置sign属性
     * 缺省实现是微信支付的sign实现，见https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=4_3
     * 其它sign规则需要重载该方法
     */
    public function sign(){
        
    }
}

class YDWXException extends \Exception{
    public function YDWXException($message=null, $code=null, $previous=null){
        $zhMsg = ErrorCodeZH::common($code);
        YDWXHook::do_hook(YDWXHook::YDWX_LOG, $message.$code);
        parent::__construct($zhMsg ? $zhMsg."($message)" : $message, $code, $previous);
    }
}

/**
 * 微信消息封装基类,便于知道每种消息有什么内容
 */
class YDWXResponse{
    /**
     * 真值表示有错误
     * @var unknown
     */
    public $errcode;
    public $errmsg;
    public $rawData;
    
    
    public function __construct($msg=null){
        $this->rawData = $msg;
        if($msg){
            $this->build($msg);
        }
    }
    /**
     * @return 返回bool值，表示微信的业务处理成功
     */
    public function isSuccess(){
        return  ! $this->errcode;
    }
    /**
     * 解析消息,默认以json字符串进行解析；错误返回格式：{"errcode":,"errmsg":""}
     * 
     * @param string $msg
     */
    public function build($msg){
        $info = json_decode($msg, true);
        if($info){
            foreach ($info as $name => $value){
                $this->$name = $value;
            }
            if($this->errcode){
                $this->errmsg .= "(".$this->errcode.")";
            }
        }else{
            $this->errcode = -1;
            $this->errmsg  = "响应字符串格式不对（{$msg}）";
        }
    }
}