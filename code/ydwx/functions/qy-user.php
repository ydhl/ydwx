<?php
/**
 * 企业微信成员管理
 */


/**
 * 创建成员
 * 
 * 系统应用须拥有指定部门的管理权限。
 * 注意，每个部门下的节点不能超过3万个。建议保证创建department对应的部门和创建成员是串行化处理。
 * 企业号（未升级成企业微信账号）将不保存接口传过来的english_name、telephone、isleader，
 * order四个参数，请服务商自行保存
 * 
 * @param $accessToken 可通过hook YDWXHook::do_hook(YDWXHook::GET_QY_ACCESS_TOKEN) 获取
 * @param YDWXQYUserCreate $request
 * @throws YDWXException
 * @return bool
 */
function ydwx_qy_user_create(YDWXQYUserCreate $request, $accessToken){
    $http = new YDHttps();
    $response= $http->post(YDWX_WEIXIN_QY_BASE_URL."user/create?access_token={$accessToken}", $request->toJSONString());
    $response = new YDWXResponse($response);
    if($response->isSuccess()){
        return true;
    }
    throw new YDWXException($response->errmsg);
}

/**
 * 删除成员
 * 系统应用须拥有指定成员的管理权限。
 * 
 * @param unknown $accessToken 可通过hook YDWXHook::do_hook(YDWXHook::GET_QY_ACCESS_TOKEN) 获取
 * @param unknown $userId
 * @throws YDWXException
 * @return boolean
 */
function ydwx_qy_user_delete($accessToken, $userId){
    $http = new YDHttps();
    $response= $http->get(YDWX_WEIXIN_QY_BASE_URL."user/create?access_token={$accessToken}&userid={$userId}");
    $response = new YDWXResponse($response);
    if($response->isSuccess()){
        return true;
    }
    throw new YDWXException($response->errmsg);
}

/**
 * 读取成员
 * 在通讯录同步助手中此接口可以读取企业通讯录的所有成员信息，而企业自定义的应用可以读取该应用设置的可见范围内的成员信息。
 * 系统应用须拥有指定部门的管理权限。
 * 
 * @param unknown $accessToken
 * @param unknown $userId
 * @throws YDWXException
 * @return YDWXQYUserResponse
 */
function ydwx_qy_user_get($accessToken, $userId){
    $http = new YDHttps();
    $response= $http->get(YDWX_WEIXIN_QY_BASE_URL."user/get?access_token={$accessToken}&userid={$userId}");
    $response = new YDWXQYUserResponse($response);
    if($response->isSuccess()){
        return $response;
    }
    throw new YDWXException($response->errmsg);
}

/**
 * 更新成员
 * 系统应用须拥有指定部门、成员的管理权限。
 * 注意，每个部门下的节点不能超过3万个。企业号（未升级成企业微信账号）将不保存接口传过来的english_name、telephone、
 * isleader，order四个参数，请服务商自行保存
 * 
 * @param YDWXQYUserCreate $request
 * @param unknown $accessToken
 * @throws YDWXException
 * @return boolean
 */
function ydwx_qy_user_update(YDWXQYUserCreate $request, $accessToken){
    $http = new YDHttps();
    $response= $http->post(YDWX_WEIXIN_QY_BASE_URL."user/update?access_token={$accessToken}", $request->toJSONString());
    $response = new YDWXResponse($response);
    if($response->isSuccess()){
        return true;
    }
    throw new YDWXException($response->errmsg);
}

/**
 * 批量删除成员
 * 系统应用须拥有指定成员的管理权限。
 * 
 * @param unknown $accessToken
 * @param unknown $userids 成员UserID列表。对应管理端的帐号。（最多支持200个）
 * @throws YDWXException
 * @return boolean
 */
function ydwx_qy_user_batchdelete($accessToken, $userids){
    $args = array();
    $args["useridlist"]=(array)$userids;
    $http = new YDHttps();
    $response= $http->post(YDWX_WEIXIN_QY_BASE_URL."user/batchdelete?access_token={$accessToken}", json_encode($args));
    $response = new YDWXResponse($response);
    if($response->isSuccess()){
        return true;
    }
    throw new YDWXException($response->errmsg);
}

/**
 * 获取部门成员
 * 系统应用须拥有指定部门的查看权限。
 * 
 * @param unknown $accessToken
 * @param unknown $depart_id
 * @param number $fetch_childs 1/0：是否递归获取子部门下面的成员
 * @param number $simpleinfo 1返回简单信息YDWXQYUserSimpleInfo 0返回详细信息YDWXQYUserResponse
 * @throws YDWXException
 * @return array(YDWXQYUserSimpleInfo) | array(YDWXQYUserResponse)
 */
function ydwx_qy_users_of_depart($accessToken, $depart_id, $fetch_childs=0, $simpleinfo=1){
    $http = new YDHttps();
    $response= $http->get(YDWX_WEIXIN_QY_BASE_URL."user/".($simpleinfo ? "simplelist" :"list")."?access_token={$accessToken}&department_id={$depart_id}&fetch_child={$fetch_childs}");
    $response = new YDWXResponse($response);
    if($response->isSuccess()){
        $arr = array();
        foreach ($response->userlist as $userinfo){
            $user = $simpleinfo ? new YDWXQYUserSimpleInfo() : new YDWXQYUserResponse();
            foreach ($userinfo as $n=>$v){
                $user->$n = $v;
            }
            $arr[] = $user;
        }
        return $arr;
    }
    throw new YDWXException($response->errmsg);
}

/**
 * Userid与Openid互换接口
 * 该接口使用场景为微信支付、微信红包和企业转账，企业号用户在使用微信支付的功能时，需要自行将企业号的userid转成openid。在使用微信红包功能时，需要将应用id和userid转成appid和openid才能使用。
 * 
 * 成员必须处于应用的可见范围内，并且管理组对应用有使用权限、对成员有查看权限。
 * 
 * @param unknown $accessToken
 * @param unknown $app_agent_id 整型，需要发送红包的应用ID，若只是使用微信支付和企业转账，则无需该参数
 * @param unknown $userid
 * @throws YDWXException
 * @return array("openid","appid");
 */
function ydwx_qy_user_get_openid($accessToken, $app_agent_id, $userid){
    $args = array();
    $args["userid"]  = $userid;
    $args["agentid"] = $app_agent_id;
    $http = new YDHttps();
    $response= $http->post(YDWX_WEIXIN_QY_BASE_URL."user/convert_to_openid?access_token={$accessToken}", json_encode($args));
    $response = new YDWXResponse($response);
    if($response->isSuccess()){
        return array("openid"=>$response->openid, "appid"=>$response->appid);
    }
    throw new YDWXException($response->errmsg);
}

/**
 * openid转换成userid接口
 * 该接口主要应用于使用微信支付、微信红包和企业转账之后的结果查询，开发者需要知道某个结果事件的openid对应企业号内成员的信息时，可以通过调用该接口进行转换查询。
 * 管理组需对openid对应的企业号成员有查看权限。
 * 
 * @param unknown $accessToken
 * @param unknown $openid
 * @throws YDWXException
 * @return string userid 该openid在企业中对应的成员userid
 */
function ydwx_qy_user_get_userid($accessToken, $openid){
    $args = array();
    $args["openid"]  = $openid;
    $http = new YDHttps();
    $response= $http->post(YDWX_WEIXIN_QY_BASE_URL."user/convert_to_userid?access_token={$accessToken}", json_encode($args));
    $response = new YDWXResponse($response);
    if($response->isSuccess()){
        return $response->userid;
    }
    throw new YDWXException($response->errmsg);
}

/**
 * 创建部门
 * 
 * 系统应用须拥有父部门的管理权限。
 * 注意，部门的最大层级为15层；部门总数不能超过3万个；每个部门下的节点不能超过3万个。建议保证创建的部门和对应部门成员是串行化处理。
 * 
 * @param YDWXQYDepartCreate $request
 * @param unknown $accessToken
 * @throws YDWXException
 * @return int 成功返回部门id
 */
function ydwx_qy_department_create(YDWXQYDepartCreate $request, $accessToken){
    $http = new YDHttps();
    $response= $http->post(YDWX_WEIXIN_QY_BASE_URL."department/create?access_token={$accessToken}", $request->toJSONString());
    $response = new YDWXResponse($response);
    if($response->isSuccess()){
        return $response->id;
    }
    throw new YDWXException($response->errmsg);
}

/**
 * 更新部门
 * 如果非必须的字段未指定，则不更新该字段
 * 
 * 系统应用须拥有指定部门的管理权限。注意，部门的最大层级为15层；部门总数不能超过3万个；每个部门下的节点不能超过3万个。
 * 
 * @param YDWXQYDepartCreate $request
 * @param unknown $accessToken
 * @throws YDWXException
 * @return boolean
 */
function ydwx_qy_department_update(YDWXQYDepartCreate $request, $accessToken){
    $http = new YDHttps();
    $response= $http->post(YDWX_WEIXIN_QY_BASE_URL."department/update?access_token={$accessToken}", $request->toJSONString());
    $response = new YDWXResponse($response);
    if($response->isSuccess()){
        return true;
    }
    throw new YDWXException($response->errmsg);
}

/**
 * 删除部门
 * 系统应用须拥有指定部门的管理权限。
 *
 * @param string $id 不能删除根部门；不能删除含有子部门、成员的部门
 * @param unknown $accessToken
 * @throws YDWXException
 * @return boolean
 */
function ydwx_qy_department_delete($id, $accessToken){
    $http = new YDHttps();
    $response= $http->get(YDWX_WEIXIN_QY_BASE_URL."department/delete?access_token={$accessToken}&id={$id}");
    $response = new YDWXResponse($response);
    if($response->isSuccess()){
        return true;
    }
    throw new YDWXException($response->errmsg);
}

/**
 * 获取部门列表
 * 部门id。获取指定部门及其下的子部门。 如果不填，默认获取全量组织架构
 * 
 * 系统应用须拥有指定部门的查看权限。
 *
 * @param string $id 不能删除根部门；不能删除含有子部门、成员的部门
 * @param unknown $accessToken
 * @throws YDWXException
 * @return array(YDWXQYDepartCreate)
 */
function ydwx_qy_departments($accessToken, $id=""){
    $http = new YDHttps();
    $response= $http->get(YDWX_WEIXIN_QY_BASE_URL."department/list?access_token={$accessToken}&id={$id}");
    $response = new YDWXResponse($response);
    if($response->isSuccess()){
        $arr = array();
        foreach ($response->department as $userinfo){
            $user = new YDWXQYDepartCreate();
            foreach ($userinfo as $n=>$v){
                $user->$n = $v;
            }
            $arr[] = $user;
        }
        return $arr;
    }
    throw new YDWXException($response->errmsg);
}