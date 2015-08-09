<?php

/**
 * 返回通过API设置的菜单
 * 
 * @param unknown $accessToken
 * @return array(Menu)
 */
function ydwx_menu_get($accessToken){
    $http = new YDHttp();
    $menus = json_decode($http->get(WEIXIN_BASE_URL."menu/get?access_token=".$accessToken), true);

    $array = array();
    if( ! @$menus['menu']['button'])return array();
    
    foreach ($menus['menu']['button'] as $menu){
        $array[] = Menu::build($menu);
    }
    return $array;
}

/**
 * 创建菜单
 * 
 * @param unknown $accessToken
 * @param unknown $menus YDWXMenu数组
 * @return boolean
 */
function ydwx_menu_create($accessToken, $menus){
//     if (WEIXIN_ACCOUNT_TYPE) TODO 认证号或者服务号才有订单
    $http = new YDHttp();
    
    $save_menus = array();
    foreach ($menus as $menu){
        $save_menus['button'][] = $menu->toArray();
    }

    $info = json_decode($http->post(WEIXIN_BASE_URL."menu/create?access_token=".$accessToken, 
            urldecode(json_encode($save_menus))), true);

    return ! $info['errcode'];
}

/**
 * 删除api创建的菜单
 * @param unknown $accessToken
 * @return boolean
 */
function ydwx_menu_delete($accessToken){
    $http = new YDHttp();
    $info = json_decode($http->get(WEIXIN_BASE_URL."menu/delete?access_token=".$accessToken), true);
    var_dump($info);
    return ! $info['errcode'];
    
}