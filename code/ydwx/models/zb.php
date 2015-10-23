<?php
/**
 * 查询审核状态结构
 * @see http://mp.weixin.qq.com/wiki/13/025f1d471dc999928340161c631c6635.html
 * @author leeboo
 *
 */
class YDWXZBStatus extends YDWXResponse {
    /**
     * 提交申请的时间戳
     */
    public $apply_time = null;

    /**
     * 审核状态。0：审核未通过、1：审核中、2：审核已通过；审核会在三个工作日内完成
     */
    public $audit_status = null;

    /**
     * 审核备注，包括审核不通过的原因
     */
    public $audit_comment = null;

    /**
     * 确定审核结果的时间戳；若状态为审核中，则该时间值为0
     */
    public $audit_time = null;
    public function build($msg){
        parent::build($msg);
        if($this->isSuccess()){
            $this->apply_time   = $this->data['apply_time'];
            $this->audit_status = $this->data['audit_status'];
            $this->audit_comment= $this->data['audit_comment'];
            $this->audit_time   = $this->data['audit_time'];
        }
    }
}

class YDWXZBDeviceRegisterResponse extends YDWXResponse{
   
    /**
     * 申请的批次ID，可用在“查询设备列表”接口按批次查询本次申请成功的设备ID。
     */
    public $apply_id = null;

    /**
     * 审核状态。0：审核未通过、1：审核中、2：审核已通过；若单次申请的设备ID数量小于等于500个，系统会进行快速审核；若单次申请的设备ID数量大于500个，会在三个工作日内完成审核；此时返回值全部为1(审核中)
     */
    public $audit_status = null;

    /**
     * 审核备注，对审核状态的文字说明
     */
    public $audit_comment = '';

    public function build($msg){
        parent::build($msg);
        if($this->isSuccess()){
            $this->apply_id      = $this->data['apply_id'];
            $this->audit_status  = $this->data['audit_status'];
            $this->audit_comment = $this->data['audit_comment'];
        }
    }
}
class YDWXZBDeviceSearchResponse extends YDWXResponse{
    /**
     * YDWXZBDevice 数组
     * @var array
     */
    public $devices;
    public $total_count;
    
    public function build($msg){
        parent::build($msg);
        if($this->isSuccess()){
            $this->total_count   = $this->data['total_count'];
            foreach ($this->data['devices'] as $dv){
                $d = new YDWXZBDevice();
                $d->comment     = $dv['comment'];
                $d->device_id   = $dv['device_id'];
                $d->major       = $dv['major'];
                $d->minor       = $dv['minor'];
                $d->status      = $dv['status'];
                $d->last_active_time = $dv['last_active_time'];
                $d->poi_id      = $dv['poi_id'];
                $d->uuid        = $dv['uuid'];
                $this->devices[] = $d;
            }
        }
    }
}
/**
 * 查询的页面结果结构
 * @author leeboo
 *
 */
class YDWXZBPageSearchResponse extends YDWXResponse{
    public $pages;
    public $total_count;

    public function build($msg){
        parent::build($msg);
        if($this->isSuccess()){
            $this->total_count   = $this->data['total_count'];
            foreach ($this->data['devices'] as $d){
                $d = new YDWXZBPage();
                $d->comment     = $d['comment'];
                $d->description = $d['description'];
                $d->icon_url    = $d['icon_url'];
                $d->page_id     = $d['page_id'];
                $d->title       = $d['title'];
                $this->pages[] = $d;
            }
        }
    }
}
/**
 * 查询的设备与页面结果结构
 * @author leeboo
 *
 */
class YDWXZBRelationSearchResponse extends YDWXResponse{
    public $relations;
    public $total_count;

    public function build($msg){
        parent::build($msg);
        if($this->isSuccess()){
            $this->total_count   = $this->data['total_count'];
            foreach ($this->data['devices'] as $d){
                $d = new YDWXZBDeviceRelation();
                $d->device_id   = $d['device_id'];
                $d->major       = $d['major'];
                $d->minor       = $d['minor'];
                $d->page_id     = $d['page_id'];
                $d->uuid        = $d['uuid'];
                $this->relations[] = $d;
            }
        }
    }
}

/**
 * 摇周边的设备及用户信息，包括UUID、major、minor，以及距离、openID等信息。
 * @see http://mp.weixin.qq.com/wiki/3/34904a5db3d0ec7bb5306335b8da1faf.html
 * @author leeboo
 *
 */
class YDWXZBShakeInfoResponse extends YDWXResponse{
    /**
     * 摇周边页面唯一ID
     * @var unknown
     */
    public $page_id;
    /**
     * Beacon信号与手机的距离，单位为米
     * @var unknown
     */
    public $distance;
    public $major;
    public $minor;
    public $uuid;
    /**
     * 商户AppID下用户的唯一标识
     * @var unknown
     */
    public $openid;
    /**
     * 门店ID，有的话则返回，反之不会在JSON格式内
     * @var unknown
     */
    public $poi_id;
    
    public function build($msg){
        parent::build($msg);
        if($this->isSuccess()){
            $this->page_id   = $this->data['page_id'];
            $this->openid    = $this->data['openid'];
            $this->poi_id    = $this->data['poi_id'];
            $this->distance  = $this->data['beacon_info']['distance'];
            $this->major     = $this->data['beacon_info']['major'];
            $this->minor     = $this->data['beacon_info']['minor'];
            $this->uuid      = $this->data['beacon_info']['uuid'];
        }
    }
}

/**
 * 申请开通功能请求
 * 
 * @link http://mp.weixin.qq.com/wiki/13/025f1d471dc999928340161c631c6635.html
 * @author leeboo
 *
 */
class YDWXZBRegisterResponse extends YDWXRequest{
    /**
     * 联系人姓名
     */
    public $name = null;

    /**
     * 联系人电话
     */
    public $phone_number = null;

    /**
     * 联系人邮箱
     */
    public $email = null;

    /**
     * 平台定义的行业代号，具体请查YDWX_INDUSTRY_XX常量
     */
    public $industry_id = null;

    /**
     * 相关资质文件的图片url，图片需先上传至微信侧服务器，用“素材管理-上传图片素材”接口上传图片，
     * 返回的图片URL再配置在此处；
     * 当不需要资质文件时，数组内可以不填写url
     * 如果文件以@开始，则表示本地文件
     */
    public $qualification_cert_urls = array();

    /**
     * 申请理由
    */
    public $apply_reason = null;
    public function valid(){
        
    }
}

/**
 * 注册设备
 * @author leeboo
 *
 */
class YDWXZBDeviceRegister extends YDWXRequest{

    /**
     * 申请的设备ID的数量，单次新增设备超过500个，需走人工审核流程
     */
    public $quantity = null;

    /**
     * 申请理由，不超过100个字
     */
    public $apply_reason = null;

    /**
     * 备注，不超过15个汉字或30个英文字母
     */
    public $comment = null;

    /**
     * 设备关联的门店ID，关联门店后，在门店1KM的范围内有优先摇出信息的机会。
     * 门店相关信息具体可查看门店相关的接口文档
     */
    public $poi_id = null;

    public function valid(){
        
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['quantity'] = intval($args['quantity']);
        if(@$args['poi_id']){
            $args['poi_id'] = intval($args['poi_id']);
        }
        return $args;
    }
}

class YDWXZBDeviceBase extends YDWXRequest{
    /**
     * 设备编号, 当作为参数传递时，优先使用
     * @var Integer
     */
    public $device_id = null;
    
    public $major = null;
    
    public $minor = null;
    
    public $uuid = null;
    
    public function valid(){
    
    }
    public function formatArgs(){
        $args    = parent::formatArgs();
        if($args['device_id'])  $args['device_id']  = intval($args['device_id']);
        if($args['minor'])      $args['minor']      = intval($args['minor']);
        if($args['major'])      $args['major']      = intval($args['major']);
        return array("device_identifier"=>$args);
    }
}
class YDWXZBDeviceRelation extends YDWXZBDeviceBase{

    /**
     * 查询设备与页面关系时有值
     * @var unknown
     */
    public $page_id = null;
}
/**
 * 设备结构
 * @author leeboo
 *
 */
class YDWXZBDevice extends YDWXZBDeviceBase{
    /**
     * 设备的备注信息
     * @var String
     */
    public $comment = null;

    /**
     * 激活状态，0：未激活，1：已激活
     * @var unknown
     */
    public $status = null;

    /**
     * 设备最近一次被摇到的日期（最早只能获取前一天的数据）；新申请的设备该字段值为0
     * @var Integer
     */
    public $last_active_time = null;

    /**
     * 设备关联的门店ID，关联门店后，在门店1KM的范围内有优先摇出信息的机会。
     * 门店相关信息具体可查看门店相关的接口文档
     * @var Integer
     */
    public $poi_id = null;


}
/**
 * 页面相关请求数据
 * @author leeboo
 *
 */
class YDWXZBPage extends YDWXRequest{
    public $page_id;
    /**
     * 在摇一摇页面展示的副标题，不超过7个字
     * @var unknown
     */
    public $description;
    /**
     * 在摇一摇页面展示的图片。图片需先上传至微信侧服务器，用“素材管理-上传图片素材”接口上传图片，返回的图片URL再配置在此处
     * @var unknown
     */
    public $icon_url;
    /**
     * 跳转链接
     * @var unknown
     */
    public $page_url;
    /**
     * 页面的备注信息，不超过15个字
     * @var unknown
     */
    public $comment;
    /**
     * 在摇一摇页面展示的主标题，不超过6个字
     * @var unknown
     */
    public $title;
    
    public function valid(){
    
    }
    
    protected function formatArgs(){
        $args = parent::formatArgs();
        if( @ $args['page_id']){
            $args['page_id'] = intval($args['page_id']);
        }
        return $args;
    }
}

/**
 * 要到的设备结构
 * @author leeboo
 *
 */
class YDWXZBChosenBeacon{
    public $uuid;
    public $major;
    public $minor;
    public $distance;
}

/**
 * 摇一摇结果统计
 * @author leeboo
 *
 */
class YDWXZBStatistic{
    /**
     * 点击摇周边消息的次数
     * @var unknown
     */
    public $click_pv;
    /**
     * 点击摇周边消息的人数
     * @var unknown
     */
    public $click_uv;
    /**
     * 当天0点对应的时间戳
     * @var unknown
     */
    public $ftime;
    /**
     * 摇周边的次数
     * @var unknown
     */
    public $shake_pv;
    /**
     * 摇周边的人数
     * @var unknown
     */
    public $shake_uv;
}

/**
 * 每台设备的摇一摇统计
 * @author leeboo
 *
 */
class YDWXZBDeviceStatistic extends YDWXZBDevice{
    /**
     * 点击摇周边消息的次数
     * @var unknown
     */
    public $click_pv;
    /**
     * 点击摇周边消息的人数
     * @var unknown
     */
    public $click_uv;
    /**
     * 摇周边的次数
     * @var unknown
     */
    public $shake_pv;
    /**
     * 摇周边的人数
     * @var unknown
     */
    public $shake_uv;
}

/**
 * 页面摇一摇统计结果
 * @author leeboo
 *
 */
class YDWXZBPageStatistic{
    public $page_id;
    /**
     * 点击摇周边消息的次数
     * @var unknown
     */
    public $click_pv;
    /**
     * 点击摇周边消息的人数
     * @var unknown
     */
    public $click_uv;
    /**
     * 摇周边的次数
     * @var unknown
     */
    public $shake_pv;
    /**
     * 摇周边的人数
     * @var unknown
     */
    public $shake_uv;
}
/**
 * 按设备查询摇一摇统计结果集
 * @author leeboo
 *
 */
class YDWXZBDeviceStatisticResult extends YDWXResponse{
    /**
     * YDWXZBDeviceStatistic 数组
     * @var array
     */
    public $devices = array();
    /**
     * 所查询的日期时间戳
     * @var unknown
     */
    public $date;
    /**
     * 设备总数
     * @var unknown
     */
    public $total_count;
    /**
     * 所查询的结果页序号；返回结果按摇周边人数降序排序，每50条记录为一页
     * @var unknown
     */
    public $page_index;
    public function build($msg){
        parent::build($msg);
        
        $this->devices = array();
        foreach ($this->data['devices'] as $info){
            $stistic = new YDWXZBDeviceStatistic();
            $stistic->click_pv = $info['click_pv'];
            $stistic->click_uv = $info['click_uv'];
            $stistic->shake_pv = $info['shake_pv'];
            $stistic->shake_uv = $info['shake_uv'];
            $stistic->device_id= $info['device_id'];
            $stistic->uuid     = $info['uuid'];
            $stistic->major    = $info['major'];
            $stistic->minor    = $info['minor'];
            $this->devices[] = $stistic;
        }
    }
}

/**
 * 按页面查询摇一摇结果集
 * @author leeboo
 *
 */
class YDWXZBPageStatisticResult extends YDWXResponse{
    /**
     * YDWXZBPageStatistic 数组
     * @var array
     */
    public $pages = array();
    /**
     * 所查询的日期时间戳
     * @var unknown
    */
    public $date;
    /**
     * 设备总数
     * @var unknown
     */
    public $total_count;
    /**
     * 所查询的结果页序号；返回结果按摇周边人数降序排序，每50条记录为一页
     * @var unknown
     */
    public $page_index;
    public function build($msg){
        parent::build($msg);
    
        $this->pages = array();
        foreach ($this->data['pages'] as $info){
            $stistic = new YDWXZBPageStatistic();
            $stistic->click_pv = $info['click_pv'];
            $stistic->click_uv = $info['click_uv'];
            $stistic->shake_pv = $info['shake_pv'];
            $stistic->shake_uv = $info['shake_uv'];
            $stistic->page_id  = $info['page_id'];
            $this->pages[] = $stistic;
        }
    }
}