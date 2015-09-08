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
            $this->apply_time   = $this->rawData['data']['apply_time'];
            $this->audit_status = $this->rawData['data']['audit_status'];
            $this->audit_comment= $this->rawData['data']['audit_comment'];
            $this->audit_time   = $this->rawData['data']['audit_time'];
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
            $this->apply_id      = $this->rawData['data']['apply_id'];
            $this->audit_status  = $this->rawData['data']['audit_status'];
            $this->audit_comment = $this->rawData['data']['audit_comment'];
        }
    }
}
class YDWXZBDeviceSearchResponse extends YDWXResponse{
    public $devices;
    public $total_count;
    
    public function build($msg){
        parent::build($msg);
        if($this->isSuccess()){
            $this->total_count   = $this->rawData['data']['total_count'];
            foreach ($this->rawData['data']['devices'] as $d){
                $d = new YDWXZBDevice();
                $d->comment     = $d['comment'];
                $d->device_id   = $d['device_id'];
                $d->major       = $d['major'];
                $d->minor       = $d['minor'];
                $d->status      = $d['status'];
                $d->last_active_time = $d['last_active_time'];
                $d->poi_id      = $d['poi_id'];
                $d->uuid        = $d['uuid'];
                $this->devices[] = $d;
            }
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
}

/**
 * 设备结构
 * @author leeboo
 *
 */
class YDWXZBDevice extends YDWXRequest{
    /**
     * 设备的备注信息
     * @var String
     */
    public $comment = null;

    /**
     * 设备编号
     * @var Integer
     */
    public $device_id = null;

    public $major = null;

    public $minor = null;

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


    public $uuid = null;

    public function valid(){
        
    }
    public function formatArgs(){
        $args    = $this->baseInfo();
        $poiid   = $this->poi_id;
        $comment = $this->comment;
        
        if($comment)return array("device_identifier"=>$args,"comment"=>$comment);
        
        return array("device_identifier"=>$args,"poi_id"=>$poiid);
    }
    
    public function baseInfo(){
        $args    = parent::formatArgs();
        unset($args['poi_id']);
        unset($args['comment']);
        unset($args['status']);
        unset($args['last_active_time']);
        
        return $args;
    }
}