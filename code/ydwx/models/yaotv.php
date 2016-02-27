<?php
/**
 * 上传图片返回结果
 * @author leeboo
 *
 */
class YDWXYaoTVImgUploadResponse extends YDWXResponse{
    /**
     * 上传图片的地址
     */
    public $url;
    public function build($msg){
        parent::build($msg);
        if($this->data){
            $this->url = $this->data['url'];
        }
    }
}
/**
 * 同步节目单请求结果
 * @author leeboo
 *
 */
class YDWXYaoTVProgramSyncResponse extends YDWXResponse{
    /**
     * 因为节目变更导致节目下所属的活动被失效的节目ID列表。需要关注这些变更的节目ID，如果仍需要配置活动的话，需要对返回的节目重新设置活动
     * @var array
     */
    public $dirty_program_id;
    public function build($msg){
        parent::build($msg);
        if($this->data){
            $this->dirty_program_id = $this->data['dirty_program_id'];
        }
    }
}
/**
 * 同步活动返回结果
 * @author leeboo
 *
 */
class YDWXYaoTVActivitySyncResponse extends YDWXResponse{
    
}
/**
 * 以天为单位查询节目单和配置的活动。只能拉一天的数据。不分页。
 * @author leeboo
 *
 */
class YDWXYaoTVProgramQueryResponse extends YDWXResponse{
    /**
     * 微信后台存储的节目单的版本
     * @var long
     */
    public $version;
    /**
     * 节目列表,YDWXYaoTVProgramDetail 数组
     * @var array
     */
    public $programs;
    public function build($msg){
        parent::build($msg);
        if($this->data){
            $this->version  = $this->data['version'];
            $this->programs = array();
            foreach ($this->data['programs'] as $program){
                $obj = new YDWXYaoTVProgramDetail();
                
                foreach ($program as $key=>$value){
                    if($key=="desc"){
                        $obj->detail = $value;
                    }else if($key=="activities"){
                        $obj->activities = array();
                        foreach((array)$value as $act){
                            $actobj = new YDWXYaoTVActivityDetail();
                            foreach ($act as $actname => $actvalue){
                                $actobj->$actname = $actvalue;
                            }
                            $obj->activities[] = $actobj;
                        }
                    }else{
                        $obj->$key = $value;
                    }
                    
                }
                $this->programs[] = $obj;
            }
        }
    }
}
/**
 * 活动基本数据
 * @author leeboo
 *
 */
class YDWXYaoTVActivityBase extends YDWXRequest{
    /**
     * 节目的相对开始时间，单位是秒
     * @var long
     */
    public $begin_offset;
    /**
     * 节目的相对结束时间，单位是秒
     * @var long
     */
    public $end_offset;
    /**
     * 活动所使用的素材ID。
     * @var string
     */
    public $res_id;
    /**
     * 节目的ID，如果是修改活动，则传入原活动id。新增，则不需要传
     * @var long
     */
    public $id;
    public  function valid(){
    
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['begin_offset'] = intval($this->begin_offset);
        $args['end_offset']   = intval($this->end_offset);
        if($this->id){
            $args['id']   = intval($this->id);
        }
        return $args;
    }
}

/**
 * 活动详细数组
 * @author leeboo
 *
 */
class YDWXYaoTVActivityDetail extends YDWXYaoTVActivityBase{
    /**
     * 素材名称
     * @var string
     */
    public $res_name;
    /**
     * 素材的类型，目前支持URL
     * @var string
     */
    public $res_type;
    /**
     * 素材的内容
     * @var string
     */
    public $res_detail;
    /**
     * 实际摇一摇所使用的页面URL
     * @var string
     */
    public $res_url;
}
/**
 * 节目单基础数据
 * @author leeboo
 *
 */
class YDWXYaoTVProgramBase extends YDWXRequest{
    /**
     * 节目单ID，必须保持全局唯一，长度不超过100个字。
     * @var unknown
     */
    public $id;
    /**
     * 节目名称，长度不超过100个字
     * @var string
     */
    public $name;
    /**
     * 节目开始时间，单位是秒
     * @var long
     */
    public $begin_stamp;
    /**
     * 节目结束时间，单位是秒
     * @var long
     */
    public $end_stamp;
    /**
     * 节目详情说明，长度不超过1000个字。
     * @var string
     */
    public $detail;
    public  function valid(){
        
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['begin_stamp'] = intval($this->begin_stamp);
        $args['end_stamp']   = intval($this->end_stamp);
        return $args;
    }
}

/**
 * 节目详细数据
 * @author leeboo
 *
 */
class YDWXYaoTVProgramDetail extends YDWXYaoTVProgramBase{
    /**
     * 微信后台存储的活动信息的版本
     * @var long
     */
    public  $act_version;
    /**
     * 对应该节目的预约id
     * @var long
     */
    public $reserveId;
    /**
     * YDWXYaoTVActivityDetail数组
     * @var array
     */
    public $activities;
}

/**
 *  同步节目单请求
 * @author leeboo
 */
class YDWXYaoTVProgramSyncRequest extends YDWXRequest{
    /**
     * 日期，格式为YYYYMMdd
     * @var long
     */
    public $date;
    /**
     * 标识节目数据的版本，用于实现最终一致性。新的数据的版本号需要大于等于之前的版本号，否则不予更新。如不需要，可以不传。
     * @var long
     */
    public $version;
    /**
     * YDWXYaoTVProgramBase 数组
     * @var array
     */
    public $programs;
    public function valid(){
        
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['date'] = intval($args['date']);
        if($this->version){
            $args['version'] = intval($args['version']);
        }
        foreach ($this->programs as $program){
            $args['programs'][] = $program->toArray();
        }
        return $args;
    }
}

/**
 * 同步活动请求
 * @author leeboo
 *
 */
class YDWXYaoTVActivitySyncRequest extends YDWXRequest{
    /**
     * 节目ID
     * @var string
     */
    public $program_id;
    /**
     * 节目互动描述，默认为空。
     * @var string
     */
    public $describe;
    /**
     * 节目互动标识，0无标识，1红包标识，2卡券标识，3卡券&红包标识，默认为0。
     * @var long
     */
    public $flag;
    /**
     * 标识活动数据的版本，用于实现最终一致性。新的活动的版本号需要大于等于之前的版本号，否则不予更新。如不需要，可以不传。
     * @var long
     */
    public $version;
    /**
     * YDWXYaoTVActivityBase 数组
     * @var array
     */
    public $activities;
    
    public function valid(){
        
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['flag']    = intval($this->flag);
        if($this->version){
            $args['version'] = intval($this->version);
        }
        foreach ($this->activities as $activity){
            $args['activities'][] = $activity->toArray();
        }
        return $args;
    }
}

/**
 * 上传ZIP包素材返回结果
 * @author leeboo
 *
 */
class YDWXYaoTVZipAddResponse extends YDWXResponse{
    /**
     * 上传图片的地址
     */
    public $url;
    /**
     * 活动所使用的素材ID
     * @var string
     */
    public $id;
    public function build($msg){
        parent::build($msg);
        if($this->data){
            $this->url = $this->data['url'];
            $this->id  = $this->data['id'];
        }
    }
}

/**
 * zip 素材
 * @author leeboo
 *
 */
class YDWXYaoTVZip extends YDWXRequest{
    /**
     * 素材id
     * @var string
     */
    public $id;
    /**
     * 素材名称，默认为文件名，如果需要修改就传
     * @var string
     */
    public $name;
    public function valid(){
        
    }
}

/**
 * 查看素材结果
 * @author leeboo
 *
 */
class YDWXYaoTVResourceResponse extends YDWXResponse{
    /** 未提交 */
    const STATUS_UNSUBMIT = "UnSubmit";
    /** 入库中 */
    const STATUS_WAITING  = "Waiting";
    /** 入库成功 */
    const STATUS_SUCCESS  = "Success";	
    /** 入库失败 */
    const STATUS_FAILURE  = "Failure";
    
    /**
     * 素材id
     * @var string
     */
    public $id;
    /**
     * 素材名称，默认为文件名，如果需要修改就传
     * @var string
     */
    public $name;
    /**
     * 素材的类型。目前支持URL
     * @var unknown
     */
    public $type;
    /**
     * 素材的内容
     * @var unknown
     */
    public $detail;
    /**
     * 实际摇一摇所使用的页面URL
     * @var unknown
     */
    public $url;
    
    /**
     * 素材状态
     * @var unknown
     */
    public $status;
    /**
     * 入库失败的原因
     */
    public $reason;
    
    /**
     * 申请时间。单位是秒
     * @var unknown
     */
    public $create_time;
    /**
     * 入库时间。单位是秒，没有入库的就没有
     * @var unknown
     */
    public $checked_time;
    public function build($msg){
        parent::build($msg);
        if($this->data){
            foreach($this->data as $name=>$value){
                $this->$name = $value;
            }
        }
    }
}

class YDWXYaoTVResourceQueryRequest extends YDWXRequest{
    /**
     * 见YDWXYaoTVResourceQueryResponse::STATUS_XX
     */
    public $status;
    /**
     * 申请时间范围开始，闭区间
     * @var long
     */
    public $create_time_begin;
    /**
     * 申请时间范围结束，开区间
     * @var long
     */
    public $create_time_end;
    /**
     * 搜索页面名称的关键词，特殊字符要URLEncode
     * @var string
     */
    public $key_word;
    /**
     * 页面大小，(0,100]，默认20
     * @var long
     */
    public $page_size;
    /**
     * 页码，从1开始，默认为1
     * @var long
     */
    public $page_index;
    
    public function valid(){
        
    }
}
class YDWXYaoTVResourceQueryResponse extends YDWXResponse{
    /**
     * 页面大小
     * @var long
     */
    public $page_size;
    /**
     * 当前页码
     * @var long
     */
    public $page_index;
    /**
     * 总的记录条数
     * @var long
     */
    public $all_records_count;
    /**
     * 总页数
     * @var long
     */
    public $page_count;
    /**
     * 本页实际的素材数量
     */
    public $size;
    
    /**
     * YDWXYaoTVResourceResponse array
     * @var array
     */
    public $resources;
    
    public function build($msg){
        parent::build($msg);
        if($this->data){
            $this->resources = array();
            foreach($this->data as $name=>$value){
                if($name="records"){
                    $obj = new YDWXYaoTVResourceResponse();
                    foreach ($value as $n=>$v){
                        $obj->$n = $v;
                    }
                    $this->resources[] = $obj;
                }else{
                    $this->$name = $value;
                }
            }
        }
    }
}

class YDWXYaoTVSeries{
    /**
     * 节目系列ID
     * @var string
     */
    public $seriesId;
    /**
     * 
     * @var long
     */
    public $tvId;
    /**
     * 节目系列名称
     * @var string
     */
    public $name;
    /**
     * 节目系列第一个节目的开始时间，单位是秒
     * @var long
     */
    public $startTime;
    /**
     * 节目系列下节目数量
     * @var long
     */
    public $programCount;
    /**
     * 
     * @var long
     */
    public $createTime;
    /**
     * @var long
     */
    public $lastModifyTime;
}

class YDWXYaoTVSeriesQueryResponse extends YDWXResponse{
    /**
     * 节目系列数量
     * @var long
     */
    public $recordCount;
    /**
     * 当前页码
     * @var long
     */
    public $pageIndex;
    /**
     * 总页数
     * @var long
     */
    public $pageCount;
    /**
     * YDWXYaoTVSeries
     * @var array
     */
    public $records;
    
    public function build($msg){
        parent::build($msg);
        if($this->data){
            $this->records = array();
            foreach ($this->data as $name=>$value){
                if($name=="records"){
                    foreach ($value  as $record){
                        $obj = new YDWXYaoTVSeries();
                        foreach ($record as $n=>$v){
                            $obj->$n = $v;
                        }
                        $this->records[] = $obj;
                    }
                }else{
                    $this->$name = $value;
                }
            }
        }
    }
}

/**
 * 投放组件的奖品
 * @author leeboo
 *
 */
class YDWXYaoTvPrize extends YDWXRequest{
    /**
     * Editing编辑状态
     * @var unknown
     */
    const STATUS_EDITING = "Editing";
    /**
     * 完成状态,完成后无法修改或删除
     * @var unknown
     */
    const STATUS_FINISHED = "Finished";
    /**
     * 奖品名称，不能超过100个字符
     * @var string
     */
    public $name;
    /**
     * 新增奖品id
     * @var long
     */
    public $id;
    /**
     * 奖品的状态有：Editing编辑状态，Finished完成状态等两种状态。
     * @var unknown
     */
    public $status;
    /**
     * 奖品类型，话费券填写TeleCard, 其他不用填写
     * @var unknown
     */
    public $type;
    /**
     * 话费券id，通过"读取话费券信息"接口获得
     * @var unknown
     */
    public $telecardId;
    
    public function valid(){
        
    }
    
}

/**
 * 新增投放组件的奖品
 * @author leeboo
 *
 */
class YDWXYaoTvPrizeAddRequest extends YDWXRequest{
    /**
     * YDWXYaoTvPrize 数组列表，一次新增不超过10个
     * @var array
     */
    public $list;
    
    public function valid(){
        
    }
}

/**
 * 新增投放组件的奖品返回结果
 * @author leeboo
 *
 */
class YDWXYaoTvPrizeAddResponse extends YDWXResponse{
    /**
     * 返回YDWXYaoTvPrize记录列表
     * @var unknown
     */
    public $records;
    
    public function build($msg){
        parent::build($msg);
        if($this->data){
            $this->records = array();
            foreach ($this->data['records'] as $data){
                $obj = new YDWXYaoTvPrize();
                foreach ($data as $name=>$value){
                    $obj->$name = $value;
                }
                $this->records[] = $obj;
            }
        }
    }
}

class YDWXYaotvPrizeDetail extends YDWXYaoTvPrize{
    /**
     * 总奖品码数量
     * @var long
     */
    public $codeCount;
    /**
     * 未中奖奖品码数量
     * @var long
     */
    public $codeTotal;
    /**
     * 时间戳，创建时间
     * @var long
     */
    public $createTime;
    /**
     * 时间戳，最后修改时间
     * @var long
     */
    public $lastModifyTime;
    public $type;
}

class YDWXYaotvPrizeGetResponse extends YDWXResponse{
    /**
     * 总页数
     * @var long
     */
    public $pageCount;
    /**
     * 当前页，从1开始
     * @var long
     */
    public $pageIndex;
    /**
     * 总记录数
     * @var long
     */
    public $recordCount;
    /**
     * YDWXYaotvPrizeDetail 数组
     * @var array
     */
    public $records;
    
    public function build($msg){
        parent::build($msg);
        if($this->data){
            foreach ($this->data as $name=>$value){
                
                if($name=="records"){
                    $this->records = array();
                    foreach ($value as $record){
                        $obj = new YDWXYaotvPrizeDetail();
                        foreach($record as $n=>$v){
                            $obj->$n = $v;
                        }
                        $this->records[] = $obj;
                    }
                }else{
                    $this->$name = $value;
                }
            }
        }
    }
}

class YDWXYaoTvPrizeCode extends YDWXRequest{
    /**
     * 奖品码自定义唯一字符串，不能超过100个字符，由接口使用者定义，每个奖品下的奖品码不能重复
     * @var string
     */
    public $uniqueKey;
    /**
     * 奖品码所属的奖品id
     * @var long
     */
    public $prizeId;
    /**
     * 奖品码详细描述，不能超过300个字符
     * @var string
     */
    public $detail;
    
    /**
     * 奖品id
     * @var long
     */
    public $id;
    
    public function valid(){
        
    }
}

class YDWXYaotvPrizeCodeDetail extends YDWXYaoTvPrizeCode{
    /**
     * 投放完成后，如果有用户抽中该奖品码，将返回中奖用户的微信OpenId（与配置投放组件的摇电视帐号对应）
     * @var string
     */
    public $userOpenId;
    /**
     * 时间戳，创建时间
     * @var long
     */
    public $createTime;
    /**
     * 时间戳，最后修改时间
     * @var long
     */
    public $lastModifyTime;
}

class YDWXYaoTvPrizeCodeAddRequest extends YDWXRequest{
    /**
     * YDWXYaoTvPrizeCode 数组
     * @var array
     */
    public $list;
    public function valid(){
        
    }
}

class YDWXYaoTvPrizeCodeGetResponse extends YDWXResponse{
    /**
     * 总页数
     * @var long
     */
    public $pageCount;
    /**
     * 当前页，从1开始
     * @var long
     */
    public $pageIndex;
    /**
     * 总记录数
     * @var long
     */
    public $recordCount;
    /**
     * YDWXYaotvPrizeCodeDetail 数组
     * @var array
     */
    public $records;
    
    public function build($msg){
        parent::build($msg);
        if($this->data){
            foreach ($this->data as $name=>$value){
    
                if($name=="records"){
                    $this->records = array();
                    foreach ($value as $record){
                        $obj = new YDWXYaotvPrizeCodeDetail();
                        foreach($record as $n=>$v){
                            $obj->$n = $v;
                        }
                        $this->records[] = $obj;
                    }
                }else{
                    $this->$name = $value;
                }
            }
        }
    }
}
class YDWXYaoTvPrizeGroupRule extends YDWXRequest{
    /**
     * 投放规则1：用户中奖最大次数 用户中奖最大次数投放规则：用户中奖最大次数
     * @var unknown
     */
    public $ruleCountInfo_count;
    /**
     * 投放规则2：用户地理位置信息LBS 用户地理位置信息LBS投放规则：奖品组投放地区限制对应的“地理位置信息编码”，通过“获取地理位置信息编码”得到
     * @var string
     */
    public $ruleTypeLBSInfo_lbs;
    public function valid(){
        
    }
    protected function formatArgs(){
        $args = array();
        if($this->ruleCountInfo_count)$args['ruleCountInfo']['count'] = $this->ruleCountInfo_count;
        if($this->ruleTypeLBSInfo_lbs)$args['ruleTypeLBSInfo']['lbs'] = $this->ruleTypeLBSInfo_lbs;
        return $args;
    }
}
class YDWXYaoTvPrizeGroupAddRequest extends YDWXRequest{
    /**
     * 编辑状态
     * @var unknown
     */
    const STATUS_EDITING = "Editing";
    /**
     * 完成状态 完成后无法修改或删除
     * @var unknown
     */
    const STATUS_FINISHED = "Finished";
    /**
     * 奖品组名称，不能超过100个字符
     * @var string
     */
    public $name;
    
    /**
     * 奖品组id
     * @var long
     */
    public $id;
    
    /**
     * 奖品组状态：Editing编辑状态，Finished完成状态，默认Editing编辑状态，完成后无法修改或删除
     * @var unknown
     */
    public $status;
    
    /**
     * 奖品组占批次的权重，多个奖品组的权重无需相加为100
     * @var unsigned long
     */
    public $weight;
    
    /**
     * 绑定到该奖品组所有奖品id，不能超过100个，当配置奖品组状态为Finished完成状态时，至少绑定一个奖品id
     * @var array
     */
    public $prizeIds;
    /**
     * 投放规则 话费奖品组无该属性
     * @var array
     */
    public $ruleCountInfo;
    /**
     * 奖品组类型：话费奖品组填写TeleCard
     * @var unknown
     */
    public $type;
    
    public function valid(){
        
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $ids  = array_map(function ($item){
            return array("id"=>intval($item));
        }, $this->prizeIds);
        $args['prizeIds'] = $ids;
        $rules = array();
        foreach ($this->ruleCountInfo as $rule){
            $rules[] = $rule->toArray();
        }
        $args['rules']    = $rules;
        unset($args['ruleCountInfo']);
        return $args;
    }
}


class YDWXYaoTvPrizeGroup{
    public $id;
    public $name;
}

class YDWXYaoTvPrizeGroupAddResponse extends YDWXResponse{
    /**
     * YDWXYaoTvPrizeGroup
     * @var array
     */
    public $records;
    public function build($msg){
        parent::build($msg);
        $this->records = array();
        if($this->data['records']){
            foreach ($this->data['records'] as $record){
                $obj = new YDWXYaoTvPrizeGroup();
                foreach ($record as $name => $value){
                    $obj->$name = $value;
                }
                $this->records[] = $obj;
            }
        }
    }
}

class YDWXYaoTvPrizeGroupGetResponse extends YDWXResponse{
    /**
     * 总页数
     * @var long
     */
    public $pageCount;
    /**
     * 当前页，从1开始
     * @var long
     */
    public $pageIndex;
    /**
     * 总记录数
     * @var long
     */
    public $recordCount;
    /**
     * YDWXYaoTvPrizeGroupAddRequest
     * @var array
     */
    public $records;
    
    public function build($msg){
        parent::build($msg);
        if($this->data){
            foreach ($this->data as $name=>$value){
    
                if($name=="records"){
                    $this->records = array();
                    foreach ($value as $record){
                        $obj = new YDWXYaoTvPrizeGroupAddRequest();
                        foreach($record as $n=>$v){
                            $obj->$n = $v;
                        }
                        $ids = array_map(function ($item){
                            return $item['id'];
                        }, $obj->prizeIds);
                        
                        $this->prizeIds = $ids;
                        
                        $rules =  array();
                        foreach ($obj->rules as $rule){
                            $r = new YDWXYaoTvPrizeGroupRule();
                            $r->ruleCountInfo_count =  @$rule['ruleCountInfo']['count'];
                            $r->ruleTypeLBSInfo_lbs =  @$rule['ruleTypeLBSInfo']['lbs'];
                            $rules[] = $r;
                        }
                        $obj->ruleCountInfo = $rules;
                        $this->records[] = $obj;
                    }
                }else{
                    $this->$name = $value;
                }
            }
        }
    
    }
}

class YDWXYaoTvPrizeBatchAddRequest extends YDWXRequest{
    /**
     * 编辑状态
     * @var unknown
     */
    const STATUS_EDITING = "Editing";
    
    /**
     * 完成状态 完成后无法修改或删除
     * @var unknown
     */
    const STATUS_FINISHED = "Finished";
    /**
     * Syncing预处理状态
     * @var unknown
     */
    const STATUS_SYNCING = "Syncing";
    /**
     * Pause暂停状态
     * @var unknown
     */
    const STATUS_PAUSE   = "Pause";
    /**
     * Playing开启状态
     * @var unknown
     */
    const STATUS_PLAYING   = "Playing";
    /**
     * 自动投放模式
     * @var unknown
     */
    const TYPE_AUTOSTART = "AutoStart";
    /**
     * Closed停止状态
     * @var unknown
     */
    const STATUS_CLOSED = "Closed";
    /**
     * 手动投放模式
     * @var unknown
     */
    const TYPE_MANUAL    = "Manual";
    /**
     * 奖品组名称，不能超过100个字符
     * @var string
     */
    public $name;

    /**
     * 奖品组id
     * @var long
     */
    public $id;

    /**
     * 奖品组状态：Editing编辑状态，Finished完成状态，默认Editing编辑状态，完成后无法修改或删除
     * @var unknown
     */
    public $status;


    /**
     * 绑定到该投放批次所有奖品组id，不能超过100个，至少绑定一个奖品组id
     * @var array
     */
    public $groupIds;
    /**
     * 投放模式：AutoStart自动投放模式、Manual手动投放模式，默认自动投放模式
     * @var array
     */
    public $type;
    
    /**
     * 时间戳：自动投放模式的自动投放开始时间，时间戳必须大于当前时间，小于自动投放结束时间，自动投放模式时必填
     * @var long
     */
    public $autoPlayTime;
    /**
     * 时间戳：自动投放模式的自动投放结束时间，时间戳必须大于当前时间，大于自动投放开始时间，自动投放模式时必填
     * @var long
     */
    public $autoCloseTime;

    public function valid(){

    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $ids  = array_map(function ($item){
            return array("id"=>intval($item));
        }, $this->groupIds);
        $args['groupIds'] = $ids;
        
        return $args;
    }
}

class YDWXYaoTvPrizeBatch{
    public $id;
    public $name;
}

class YDWXYaoTvPrizeBatchAddResponse extends YDWXResponse{
    /**
     * YDWXYaoTvPrizeBatch
     * @var array
     */
    public $records;
    public function build($msg){
        parent::build($msg);
        $this->records = array();
        if($this->data['records']){
            foreach ($this->data['records'] as $record){
                $obj = new YDWXYaoTvPrizeBatch();
                foreach ($record as $name => $value){
                    $obj->$name = $value;
                }
                $this->records[] = $obj;
            }
        }
    }
}

class YDWXYaoTvPrizeBatchGetResponse extends YDWXResponse{
    /**
     * 总页数
     * @var long
     */
    public $pageCount;
    /**
     * 当前页，从1开始
     * @var long
     */
    public $pageIndex;
    /**
     * 总记录数
     * @var long
     */
    public $recordCount;
    /**
     * YDWXYaoTvPrizeBatchAddRequest
     * @var array
     */
    public $records;

    public function build($msg){
        parent::build($msg);
        if($this->data){
            foreach ($this->data as $name=>$value){

                if($name=="records"){
                    $this->records = array();
                    foreach ($value as $record){
                        $obj = new YDWXYaoTvPrizeBatchAddRequest();
                        foreach($record as $n=>$v){
                            $obj->$n = $v;
                        }
                        $ids = array_map(function ($item){
                            return $item['id'];
                        }, $obj->groupIds);

                        $this->groupIds = $ids;
                        $this->records[] = $obj;
                    }
                }else{
                    $this->$name = $value;
                }
            }
        }

    }
}

class YDWXYaoTvLBSData{
    /**
     * 全国、省份、城市Id
     * @var long
     */
    public $cityId;
    /**
     * 全国、省份、城市名称
     * @var string
     */
    public $cityName;
}
class YDWXYaoTVLotteryResult{
    /**
     * 奖品码详细描述
     * @var unknown
     */
    public $prize_detail;
    /**
     * 奖品码的key
     * @var unknown
     */
    public $prize_unique_key;
    /**
     * 奖品的类型
     * @var long
     */
    public $prize_type;
}
class YDWXYaoTVLotteryResponse extends YDWXResponse{
    /**
     * 本次中奖的信息
     * @var YDWXYaoTVLotteryResult
     */
    public $cur_result;
    /**
     * 之前的中奖信息列表 YDWXYaoTVLotteryResult数组
     * @var array
     */
    public $last_results;
    public function build($msg){
        parent::build($msg);
        $this->last_results = array();
        if($this->data){
            foreach ($this->data['last_results'] as $result){
                $obj = new YDWXYaoTVLotteryResult();
                foreach ($result as $name => $value){
                    $obj->$name = $value;
                }
                $this->last_results[] = $obj;
            }
            
            $obj = new YDWXYaoTVLotteryResult();
            foreach ($this->data['cur_result'] as $name => $value){
                $obj->$name = $value;
            }
            $this->cur_result = $obj;
        }
    }
}

class YDWXYaoTVIntercardBuildResponse extends YDWXResponse{
    /**
     * 卡券id，和输入参数值相同
     * @var unknown
     */
    public $card_id;
    /**
     * 卡券title，也就是奖品的名称，和输入参数值相同
     * @var unknown
     */
    public $name;
    /**
     * 奖品id
     * @var long
     */
    public $prize_id;
    /**
     * 奖品码的总库存
     * @var long
     */
    public $quantity;
    
    public function build($msg){
        parent::build($msg);
        if($this->data){
            foreach ($this->data as $name => $value){
                $this->$name = $value;
            }
        }
    }
}

class YDWXYaoTvPrizeTelecard extends YDWXRequest{
    const STATUS_FINISHED = "Finished";
    const STATUS_USING = "Using";
    /**
     * 话费券id
     * @var long
     */
    public $id;
    /**
     * 话费券名称
     * @var unknown
     */
    public $name;
    /**
     * 话费券状态
     * @var unknown
     */
    public $status;
    /**
     * 总话费券数量
     * @var long
     */
    public $cardTotal;
    
    public function valid(){
        
    }
}

class YDWXYaoTvPrizeTelecardGetResponse extends YDWXResponse{
    /**
     * 总页数
     * @var long
     */
    public $pageCount;
    /**
     * 当前页，从1开始
     * @var long
     */
    public $pageIndex;
    /**
     * 总记录数
     * @var long
     */
    public $recordCount;
    /**
     * YDWXYaoTvPrizeTelecardAddRequest
     * @var array
     */
    public $records;

    public function build($msg){
        parent::build($msg);
        if($this->data){
            foreach ($this->data as $name=>$value){

                if($name=="records"){
                    $this->records = array();
                    foreach ($value as $record){
                        $obj = new YDWXYaoTvPrizeTelecard();
                        foreach($record as $n=>$v){
                            $obj->$n = $v;
                        }
                        $this->records[] = $obj;
                    }
                }else{
                    $this->$name = $value;
                }
            }
        }

    }
}

class YDWXYaoTVAwardPrize extends YDWXRequest{
    /**
     * 现金红包类
     * @var unknown
     */
    const PRIZE_TYPE_PRIZECASH = "PrizeCash";
    /**
     * 实物奖品类
     * @var unknown
     */
    const PRIZE_TYPE_PRIZEREALTHING = "PrizeRealThing";
    /**
     * 体验机会类
     * @var unknown
     */
    const PRIZE_TYPE_PRIZEEXPERIENCE = "PrizeExperience";
    /**
     * 卡券类
     * @var unknown
     */
    const PRIZE_TYPE_PRIZECOUPONS = "PrizeCoupons";
    
    const STATUS_PRIZECHECKSUCC = "PrizeCheckSucc";
    /**
     * 奖品名称，必填
     * @var unknown
     */
    public $name;
    /**
     * 奖品类型，包括现金红包类（PrizeCash）、实物奖品类（PrizeRealThing）、体验机会类（PrizeExperience）、卡券类（PrizeCoupons），必填
     * @var unknown
     */
    public $prize_type;
    /**
     * 奖品跳转url，可选
     * @var unknown
     */
    public $url;
    
    /**
     * 奖品id
     * @var unknown
     */
    public $prize_id;
    
    /**
     * 奖品审核理由, 添加、修改时不填
     * @var unknown
     */
    public $reason;
    /**
     * 奖品审核状态, 添加、修改时不填
     * @var unknown
     */
    public $status;
    /**
     * 奖品创建时间, 添加、修改时不填
     * @var long
     */
    public $create_time;
    public function valid(){
        
    }
}

class YDWXYaoTVAwardWinner extends YDWXRequest{
    public $openid;
    /**
     * 奖品id
     * @var array
     */
    public $prize_ids;
    
    public function valid(){
        
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['prize_ids'] = array_map(function($item){
            return intval($item);
        }, $this->prize_ids);
        return $args;
    }
}

class YDWXYaoTVAddAwardTemplate extends YDWXRequest{
    /**
     * 中奖模板节目id，必填
     * @var unknown
     */
    public $program_id;
    /**
     * 中奖模版发送时间，必填
     * @var long
     */
    public $send_time;
    /**
     * 中奖模板图标，可选
     * @var unknown
     */
    public $template_icon;
    /**
     * 中奖模板背景图片，可选
     * @var unknown
     */
    public $template_background;
    /**
     * 中奖模板备注，可选
     * @var unknown
     */
    public $template_text;
    /**
     * YDWXYaoTVAwardWinner 数组
     * @var array
     */
    public $winners;
    public function valid(){
        
    }
    
}

class YDWXYaoTVGetAwardTemplate extends YDWXResponse{
    /**
     * 中奖模板节目id，必填
     * @var unknown
     */
    public $program_id;
    /**
     * 中奖模版发送时间，必填
     * @var long
     */
    public $send_time;
    /**
     * 	long	中奖模板发送数量
     * @var unknown
     */
    public $send_count;
    /**
     * 中奖模板发送状态
     * @var unknown
     */
    public $status;
    /**
     * 中奖模板图标，可选
     * @var unknown
     */
    public $template_icon;
    /**
     * 中奖模板背景图片，可选
     * @var unknown
     */
    public $template_background;
    /**
     * 中奖模板id
     * @var long
     */
    public $template_id;

    public function build($msg){
        parent::build($msg);
        if($this->data){
            foreach ($this->data as $name=>$value){
                $this->$name = $value;
            }
        }
    }
}

class YDWXYaoTVGetAwardwinnerResponse extends YDWXResponse{
    /**
     * 中奖用户的openid
     * @var unknown
     */
    public $openid;
    /**
     * 中奖用户的奖品id数组
     * @var array
     */
    public $prize_ids;
    /**
     * 中奖用户的发送状态
     * @var unknown
     */
    public $status;
    /**
     * 中奖用户的中奖模板id
     * @var long
     */
    public $template_id;
    
    public function build($msg){
        parent::build($msg);
        if($this->data){
            foreach ($this->data as $name=>$value){
                $this->$name = $value;
            }
        }
    }
}

class YDWXYaoTvProgramReserveAddRequest extends YDWXRequest{
    /**
     * 预约id，在api“查看节目活动表”里有返回该字段
     * reserveId 和 seriesId 两者必须选择一种填写。reserveId 用于设置单期节目的预约消息，seriesId 用于设置节目系列的预约消息
     * @var long
     */
    public $reserveId;
    /**
     * 节目系列id，在api“查询节目系列”里有返回该字段
     * reserveId 和 seriesId 两者必须选择一种填写。reserveId 用于设置单期节目的预约消息，seriesId 用于设置节目系列的预约消息
     * @var long
     */
    public $seriesId;
    /**
     * 消息列表图标url, 必填
     * @var string
     */
    public $msgIconUrl;
    /**
     * 查看详情的url(qq域),必填
     * @var string
     */
    public $detailUrl;
    
    public function valid(){
        
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        if($this->reserveId){
            $args['reserveId'] = intval($this->reserveId);
        }
        if($this->seriesId){
            $args['seriesId'] = intval($this->seriesId);
        }
        return $args;
    }
}

class YDWXYaoTvProgramReserveResponse extends  YDWXResponse{
    /**
     * 预约ID
     * @var long
     */
    public $reserveId;
    /**
     * 创建时间
     * @var long
     */
    public $createTime;
    /**
     * 查看详情的url
     * @var unknown
     */
    public $detailUrl;
    /**
     * 消息列表图标url
     * @var unknown
     */
    public $msgIconUrl;
    /**
     * 审核的状态，0待审核，1审核通过，2审核不通过
     * @var long
     */
    public $status;
    /**
     *  审核不通过的理由
     * @var string
     */
    public $reason;
    
    public function build($msg){
        parent::build($msg);
        if($this->data){
            foreach ($this->data as $name=>$value){
                $this->$name = $value;
            }
        }
    }
}

/**
 * 
 * @author leeboo
 *
 */
class YDWXYaoTvLotteryInfoAddRequest extends YDWXRequest{
    /**
     * 		抽奖名称，最长128字节
     * @var string
     */
    public $title;
    /**
     * 		抽奖描述，最长1024字节
     * @var string
     */
    public $desc;
    /**
     * 		抽奖开关。0关闭，1开启
     * @var int
     */
    public $onoff;
    /**
     * 		抽奖开始时间，unix时间戳，单位秒
     * @var long
     */
    public $begin_time;
    /**
     * 		抽奖失效时间，unix时间戳，单位秒
     * @var long
     */
    public $expire_time;
    /**
     * 		开发者或赞助商appid，需要与中奖者的openid对应
     * @var string
     */
    public $appid;
    /**
     * 		奖品总数
     * @var long
     */
    public $total;
    /**
     * 	int	默认填写0，表示是实物类型
     * @var int
     */
    public $type;
    /**
     * 		开发者自定义的key，用来生成抽奖接口的签名参数，长度32位。使用方式见抽奖接口
     * @var string
     */
    public $key;
    public function valid(){
    
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['total']          = floatval($args['total']);
        $args['type']           = floatval($args['type']);
        $args['begin_time']     = floatval($args['begin_time']);
        $args['expire_time']    = floatval($args['expire_time']);
        $args['onoff']          = intval($args['onoff']);
        return $args;
    }
}

class YDWXYaoPrizeExtInfo extends YDWXRequest{
    /**
     * 奖品名，最长128字节
     * @var unknown
     */
    public $prize_title;
    /**
     * 奖品额外信息，开发者可以自定义，会在中奖列表中返回。非必填，最长1024字节
     * @var string
     */
    public $prize_ext_info;
    
    public function valid(){
    
    }
}

class YDWXYaoPrizeWinnerInfo extends YDWXYaoPrizeExtInfo{
    /**
     * addlotteryinfo接口传入的appid
     * @var unknown
     */
    public $appid;
    /**
     * 中奖用户的openid，与addlotteryinfo接口传入的appid对应
     * @var unknown
     */
    public $openid;
    /**
     * 抽奖接口中传入的userid
     * @var unknown
     */
    public $userid;
    /**
     * 奖品名，最长128字节
     * @var unknown
     */
    public $prize_title;
    /**
     * 奖品额外信息，开发者可以自定义，会在中奖列表中返回。非必填，最长1024字节
     * @var string
     */
    public $prize_ext_info;

    public function valid(){

    }
}

class YDWXYaoTvSetPrizeBucketRequest extends YDWXRequest{
    /**
     * 抽奖id，来自addlotteryinfo返回的lottery_id
     * @var unknown
     */
    public $lottery_id;
    
    
    /**
     * 奖品列表，如果奖品较多，可以一次传入多个奖品，批量调用该接口设置奖品信息。每次请求传入的奖品个数上限为100
     * YDWXYaoPrizeExtInfo数组
     * @var multitype:YDWXYaoPrizeExtInfo
     */
    public $prize_info_list;
    
    public function valid(){
    
    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        return $args;
    }
}

class YDWXYaoTvDrawResult extends YDWXResponse{
    public $title;
    /**
     * 自定义数据
     * @var unknown
     */
    public $prize_ext_info;
    /**
     * 如果需要验证码会返回验证码的url, 界面上需要显示验证码输入框，
     * 并重新post请求yaotv_draw.php,并通过captcha提交上来验证码
     * @var unknown
     */
    public $captcha_url;
}


/**
 * 录入红包信息
 * @author leeboo
 *
 */
class YDWXYaoTVSetPrizeBucket4HBRequest extends YDWXRequest{
    /**
     * 红包抽奖id，来自addlotteryinfo返回的lottery_id
     * @var unknown
     */
    public $lottery_id;
    /**
     * 红包提供者的商户号，，需与预下单中的商户号mch_id一致
     * @var unknown
     */
    public $mchid;
    /**
     * 红包发放者公众号的appid
     * @var unknown
     */
    public $appid;
    /**
     * 红包ticket列表，如果红包数较多，
     * 可以一次传入多个红包，批量调用该接口设置红包信息。每次请求传入的红包个数上限为100
     * @var array
     */
    public $prize_info_list;
    public function valid(){

    }
    protected function formatArgs(){
        $args = parent::formatArgs();
        $args['prize_info_list'] = array_map(function($item){
            return array("ticket"=>$item);
        }, $args['prize_info_list']);
        return $args;
    }
}


/**
 * 录入红包结果
 * @author leeboo
 *
 */
class YDWXYaoTVSetPrizeBucket4HBResponse extends YDWXResponse{
    
    /**
     * 无效的ticket
     * @var unknown
     */
    public $subcode_invalid_ticket = array();
    /**
     * 重复的ticket
     * @var unknown
     */
    public $subcode_repeat_ticket = array();
    /**
     * 生成该ticket的appid与setprizebucket4hb和addlottery中的appid参数三者不一致
     * @var unknown
     */
    public $subcode_error_appid = array();

    public function build($msg){
        parent::build($msg);
        foreach ($this->ticket_err_list as $info){
            switch ($info['subcode']){
                case 1001: $this->subcode_invalid_ticket[] = $info['ticket'];break;
                case 1002: $this->subcode_repeat_ticket[] = $info['ticket'];break;
                case 1003: $this->subcode_error_appid[] = $info['ticket'];break;
            }
        }
    }
}