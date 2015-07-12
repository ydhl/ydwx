<?php

include_once '../config.php';
include_once './libs/wx.php';


$array = array();

$menu1  = new Menu();
$menu1->name = "我的标签";
$menu1->type = Menu::TYPE_VIEW;
$menu1->url  = SITE_URI."app/myqrcodes.php";

$menu2  = new Menu();
$menu2->name = "购买标签";
$menu2->type = Menu::TYPE_VIEW;
$menu2->url  = SITE_URI."app/pay.php";

$menu3  = new Menu();
$menu3->name = "扫描标签";
$menu3->type = Menu::TYPE_SCANCODE_PUSH;
$menu3->key  = "scan_qrcode";

$access_token = lookup("value", "options", "name='access_token'");
//echo "access token",$access_token,"<br/>";
createMenus($access_token, array($menu1, $menu2, $menu3));
//removeMenus($access_token);
echo "getmenus:<br/>";
print_r(getMenus($access_token));

// $gift_number = lookup("value", "options", "name='new_user_gift_number'");
// createEmptyQrcode($gift_number);
?>