<?php
function ydwx_json_encode($array){
    return urldecode(json_encode(ydwx_url_encode($array)));
}

function ydwx_url_encode($array){
    $temp = array();
    foreach($array as $key=>$value){
        $temp[$key] = is_array($value) ? ydwx_url_encode($value) : urlencode($value);
    }
    return $temp;
}
function ydwx_error($message="", $code=null){
    return array('success'=> false, "data"=>null,"msg"=>$message);
}

function ydwx_success($data=null){
    return array('success'=> true, "data"=>$data,"msg"=>null);
}