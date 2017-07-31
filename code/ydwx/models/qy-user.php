<?php
trait YDWXQYUser{
    /**
     * 成员UserID。对应管理端的帐号，企业内必须唯一。不区分大小写，长度为1~64个字节
     * @var string
     */
    public $userid;
    /**
     * 成员名称。长度为1~64个字节
     * @var string
     */
    public $name;
    /**
     * 英文名。长度为1-64个字节。
     * @var string
     */
    public $english_name;
    /**
     * 手机号码。企业内必须唯一
     * @var string
     */
    public $mobile;
    /**
     * 成员所属部门id列表,不超过20个
     * @var array
     */
    public $department = array();
    /**
     * 部门内的排序值，默认为0。数量必须和department一致，数值越大排序越前面。有效的值范围是[0, 2^32)
     * @var array
     */
    public $order;
    /**
     * 职位信息。长度为0~64个字节
     * @var string
     */
    public $position;
    /**
     * 性别。1表示男性，2表示女性
     * @var string
     */
    public $gender;
    /**
     * 邮箱。长度为0~64个字节。企业内必须唯一
     * @var string
     */
    public $email;
    /**
     * 上级字段，标识是否为上级。1|0
     * @var integer
     */
    public $isleader;
    /**
     * 启用/禁用成员。1表示启用成员，0表示禁用成员
     * @var integer
     */
    public $enable;
    /**
     * 座机。长度0-64个字节。
     * @var string
     */
    public $telephone;
    /**
     * 自定义字段。自定义字段需要先在WEB管理端“我的企业” — “通讯录管理”添加，否则忽略未知属性的赋值
     * "extattr": {"attrs":[{"name":"爱好","value":"旅游"},{"name":"卡号","value":"1234567234"}]}
     * @var array
     */
    public $extattr;
}

/**
 * 创建企业用户请求参数
 * @author ydhlleeboo
 *
 */
class YDWXQYUserCreate extends YDWXRequest{
    use YDWXQYUser;
    /**
     * 成员头像的mediaid，通过多媒体接口上传图片获得的mediaid
     * @var string
     */
    public $avatar_mediaid;

    public  function valid(){
        
    }
}

/**
 * 企业用户读取返回数据
 * @author ydhlleeboo
 *
 */
class YDWXQYUserResponse extends YDWXResponse{
    use YDWXQYUser;
    /**
     * 头像url。注：如果要获取小图将url最后的”/0”改成”/100”即可
     * @var string
     */
    public $avatar;
    
    /**
     * 激活状态: 1=已激活，2=已禁用，4=未激活。已激活代表已激活企业微信或已关注微信插件。未激活代表既未激活企业微信又未关注微信插件。
     * @var integer
     */
    public $status;
}

/**
 * 返回的用户的简单信息
 * @author ydhlleeboo
 *
 */
class YDWXQYUserSimpleInfo{
    /**
     * 成员UserID。对应管理端的帐号
     * @var unknown
     */
    public $userid;
    /**
     * 成员名称
     * @var unknown
     */
    public $name;
    /**
     * 成员所属部门 id
     * @var array
     */
    public $department;
}


/**
 * 创建部门是，提交的参数
 * @author ydhlleeboo
 *
 */
class YDWXQYDepartCreate extends YDWXRequest{

    /**
     * 部门名称。长度限制为1~64个字节，字符不能包括\:?”<>｜
     * @var string
     */
    public $name;
    
    /**
     * 父部门id
     * @var unknown
     */
    public $parentid;
    
    /**
     * 在父部门中的次序值。order值大的排序靠前。有效的值范围是[0, 2^32)
     * @var unknown
     */
    public $order;
    
    /**
     * 部门id，整型。指定时必须大于1，否则自动生成
     * @var unknown
     */
    public $id;
    
    public  function valid(){
        
    }
}