<?php
use app\admin\Post_Model;
use app\common\Option_Model;
use app\sp\Service_Provider_Model;
use app\admin\Category_Model;
use yangzie\YZE_Hook;
$oldcwd = getcwd();
chdir ( dirname ( __FILE__ ) . '/../' );
require_once 'init.php';
chdir ( $oldcwd );

YDWXHook::add_hook(YDWXHook::EVENT_CLICK, function( YDWXEventClick $event){
    $sp = reset(Service_Provider_Model::find_by_attrs(array("appid"=>$event->APPID)));
    if( ! $sp) return;

    $menus = Category_Model::getMenus($sp->get_key(), Category_Model::CATEGORY_TYPE_WX_MENU);

    //找出对应的菜单
    $click_menu = array();
    foreach ($menus as $menu) {
        if (strcasecmp($menu["id"] , $event->EventKey)==0) {
            $click_menu = $menu;
            break;            
        } 
        foreach ((array)$menu["subs"] as $submenu) {
            if (strcasecmp($submenu["id"] , $event->EventKey)==0) {
                $click_menu = $submenu;
                break;            
            } 
        }
        if ($click_menu) break;
    }
    
    $meta_data = json_decode(html_entity_decode($click_menu['meta_data']), true);
    
    if ( ! $click_menu || ! $meta_data[Category_Model::META_POSTS]) return;

    //发送对应的回复消息
    $post_models = Post_Model::find_by_ids($meta_data[Category_Model::META_POSTS], array('id','title','excerpt','cover'));
    if (empty($post_models)) return;
    
    $news = array();
    foreach ($post_models as $post) {
        $YDWXNewsMsg = new YDWXNewsMsg();
        $YDWXNewsMsg->title = $post->get("title");
        $YDWXNewsMsg->description = $post->get("excerpt");
        $YDWXNewsMsg->picurl = get_file_url($post->get("cover"));
        $YDWXNewsMsg->url = SITE_URI . "site/s".$sp->id."a".$post->get("id");
        
        $news[] = $YDWXNewsMsg;
    }
    
    $answerMsg = YDWXAnswerMsg::buildNewsMsg($news, $event);
    ydwx_answer_msg($answerMsg);  
          
});