<?php
function yd_json_encode($array){
    return urldecode(json_encode(yd_url_encode($array)));
}

function yd_url_encode($array){
    $temp = array();
    foreach($array as $key=>$value){
        $temp[$key] = is_array($value) ? yd_url_encode($value) : urlencode($value);
    }
    return $temp;
}
function ydwx_error($message="", $code=null){
    return array('success'=> false, "data"=>null,"msg"=>$message);
}

function ydwx_success($data=null){
    return array('success'=> true, "data"=>$data,"msg"=>null);
}