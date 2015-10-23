<?php
class YDWXPoiAddRequest extends YDWXRequest{
    /**
     * 商户自己的id，用于后续审核通过收到poi_id 的通知时，做对应关系。请商户自己保证唯一识别性
     * @var unknown
     */
    public $sid;
    /**
     * 修改时传入
     * @var unknown
     */
    public $poi_id;
    /**
     * 门店名称（仅为商户名，如：国美、麦当劳，不应包含地区、地址、分店名等信息，错误示例：北京国美）
     * @var unknown
     */
    public $business_name;
    /**
     * 分店名称（不应包含地区信息，不应与门店名有重复，错误示例：北京王府井店）
     * @var unknown
     */
    public $branch_name;
    public $province;
    public $city;
    public $district;
    public $address;
    public $telephone;
    /**
     * 门店的类型（不同级分类用“,”隔开，如：美食，川菜，火锅。详细分类参见附件：微信门店类目表）
     * @var array
     */
    public $categories;
    /**
     * 坐标类型，1 为火星坐标（目前只能选1）
     * @var unknown
     */
    public $offset_type;
    /**
     * 门店所在地理位置的经度
     * @var unknown
     */
    public $longitude;
    /**
     * 门店所在地理位置的纬度（经纬度均为火星坐标，最好选用腾讯地图标记的坐标）
     * @var unknown
     */
    public $latitude;
    /**
     * 图片列表，url组成的数组，可以有多张图片，尺寸为
     * 640*340px。必须为ydwx_poi_uploadimage接口生成的url。
     * 图片内容不允许与门店不相关，不允许为二维码、员工合照（或模特肖像）、营业执照、
     * 无门店正门的街景、地图截图、公交地铁站牌、菜单截图等
     * @var array
     */
    public $photo_list;
    /**
     * 推荐品，餐厅可为推荐菜；酒店为推荐套房；景点为推荐游玩景点等，针对自己行业的推荐内容
     * @var unknown
     */
    public $recommend;
    /**
     * 特色服务，如免费wifi，免费停车，送货上门等商户能提供的特色功能或服务
     * @var unknown
     */
    public $special;
    /**
     * 商户简介，主要介绍商户信息等
     * @var unknown
     */
    public $introduction;
    /**
     * 营业时间，24 小时制表示，用“-”连接，如 8:00-20:00	
     * @var unknown
     */
    public $open_time;
    /**
     * 人均价格，大于0 的整数
     * @var unknown
     */
    public $avg_price;
    public function valid(){
        
    }
    protected function formatArgs(){
        $args = YDWXRequest::ignoreNull(parent::formatArgs());
        $array = array();
        $args['categories'] = (array)$this->categories;
        $photos = array();
        
        foreach ((array)$this->photo_list as $photo){
            $photos[] = array("photo_url"=>$photo);
        }
        
        $args['photo_list'] = $photos;
        $array['business']['base_info'] = $args;
        return $array;
    }
}

/**
 * 查询poi结果
 * @author leeboo
 *
 */
class YDWXPoiGetResponse extends YDWXResponse{
    /**
     * 
     * @var YDWXPoiAddRequest
     */
    public $poi;
    /**
     * 门店是否可用状态。1 表示系统错误、2 表示审核中、3 审核通过、4 审核驳回。当该字段为1、2、4 状态时，poi_id 为空
     * @var unknown
     */
    public $available_state;
    /**
     * 扩展字段是否正在更新中。1 表示扩展字段正在更新中，尚未生效，不允许再次更新； 0 表示扩展字段没有在更新中或更新已生效，可以再次更新
     * @var unknown
     */
    public $update_status;
    
    public function build($msg){
        parent::build($msg);
        $this->poi = new YDWXPoiAddRequest();
        $this->available_state = $this->business['base_info']['available_state'];
        $this->update_status   = $this->business['base_info']['update_status'];
        foreach ($this->business['base_info'] as $name=>$value){
            $this->poi->$name = $value;
        }
        
    }
}
/**
 * 查询poi列表结果集
 * @author leeboo
 *
 */
class YDWXPoiGetListResponse extends YDWXResponse{
    /**
     *
     */
    public $total_count;
    /**
     * @var multitype::YDWXPoiGetResponse
     */
    public $pois;

    public function build($msg){
        parent::build($msg);
        foreach ($this->business_list as $list){
            $poi = new YDWXPoiGetResponse();
            $poi->available_state = $list['base_info']['available_state'];
            $poi->update_status   = $list['base_info']['update_status'];
            $poi->poi = new YDWXPoiAddRequest();
            foreach ($list['base_info'] as $name=>$value){
                $poi->poi->$name = $value;
            }
            $this->pois[] = $poi;
        }
    }
}