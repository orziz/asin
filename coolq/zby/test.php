<?php

function getBiliDynamic($url) {
	return file_get_contents($url);
	// return file_get_contents($url);
}

$weiboDynamic = getBiliDynamic('https://m.weibo.cn/api/container/getIndex?uid=5696569764&luicode=10000011&lfid=100103type%3D1%26q%3D%E5%95%8A%E5%93%88%E5%A8%B1%E4%B9%90&type=uid&value=5696569764&containerid=1076035696569764');

# preg_match_all("/<script>(.*)<\/script>/iUs",$weboDynamic,$text);

// $weboDynamic = '<pre>' . $weboDynamic . '</pre>';

// $text = strpos($weboDynamic,'<div class=\"WB_text W_f14\" node-type=\"feed_list_content\" nick-name=\"啊哈娱乐AHA\">');

$weiboDynamic = json_decode($weiboDynamic, true);

$weiboBid = $weiboDynamic['data']['cards'][0]['mblog']['bid'];
$text = $weiboDynamic['data']['cards'][0]['mblog']['text'];

$dynamic = strip_tags($weiboDynamic['data']['cards'][0]['mblog']['text'],'<br>');
$msgArr = explode('<br />',$dynamic);

$text .= "\n此消息来自于新浪微博动态，原始动态地址：https://weibo.com/5696569764/" . $weiboBid;

var_dump($msgArr);