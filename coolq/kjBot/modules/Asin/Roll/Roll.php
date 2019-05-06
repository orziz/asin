<?php

namespace kjBotModule\Asin\Roll;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 
 */
class Roll extends Module
{
	
	public function process(array $args, $event){
        $msg = '';
        $User_id = $event->getId();
        if ($event->fromGroup()) $msg .= CQCode::At($User_id)."\n";
        $data = param_post('http://asin.ygame.cc/api.php',array('mod'=>'home_userinfo','action'=>'getUserInfoByWeb','qq'=>$User_id));
        if ($data['errCode'] !== 200) {
            $msg .= $data['errMsg'];
        } else {
            $userInfo = $data['data'];
            // $msg .= json_encode($userInfo)."\n";
            $type = null;
            $typeName = null;
            if (isset($args[1])) {
                switch ($args[1]) {
                    case '力量':
                    case 'str':
                        $type = 'str';
                        $typeName = '力量';
                        break;
                    case '敏捷':
                    case 'dex':
                        $type = 'dex';
                        $typeName = '敏捷';
                        break;
                    case '体质':
                    case 'con':
                        $type = 'con';
                        $typeName = '体质';
                        break;
                    case '智力':
                    case 'ine':
                        $type = 'ine';
                        $typeName = '智力';
                        break;
                    case '感知':
                    case 'wis':
                        $type = 'dex';
                        $typeName = '感知';
                        break;
                    case '魅力':
                    case 'cha':
                        $type = 'dex';
                        $typeName = '魅力';
                        break;
                }
            }
            $rand = 0;
            if ($type) {
                $msg .= "您目前".$typeName."为 ".$userInfo[$type]."\n";
                $min = isset($args[3]) ? intval($args[2]) : 1;
                $max = isset($args[2]) ? isset($args[3]) ? intval($args[3]) : intval($args[2]) : 100;
                $max2 = floor($max/2+0.5);
                if ($max2 === 0 || $max == $max2) $rand = 0;
                else {
                    $rand1 = mt_rand($min, $max2);
                    $rand2 = floor(mt_rand(1,$userInfo[$type]/2)/(50/($max-$max2))+0.5);
                    $rand = $rand1+$rand2.'('.$rand1.'+'.$rand2.')';
                }
            } else {
                $min = isset($args[2]) ? intval($args[1]) : 1;
                $max = isset($args[1]) ? isset($args[2]) ? intval($args[2]) : intval($args[1]) : 100;
                $rand = mt_rand($min,$max);
            }
            $msg .= $min.'d'.$max.'：'. $rand;
        }
        return $event->sendBack($msg);
	}
}