<?php

function request_post($url = '', $param = '') {
    if (empty($url) || empty($param)) {
        return false;
    }
    
    $postUrl = $url;
    $curlPost = $param;
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    
    return $data;
}

$url = 'http://asin.ygame.cc/coolq/kjBot/public/index.php';

$data = array('post_type' => "message",'message_type'=>'group','sub_type'=>'normal','message'=>'开始刺客大乱斗','group_id'=>758507034,'user_id'=>1063614727);
$data = json_encode($data);

// curl_post_https($url,$data);
request_post($url,$data);