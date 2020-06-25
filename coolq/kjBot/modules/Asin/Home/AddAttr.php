<?php

namespace kjBotModule\Asin\Home;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 查看用户属性
 */
class AddAttr extends Module
{
	
	public function process(array $args, $event){
        $msg = '';
        if (!isset($args[1])) q('加点失败：未知属性名（可用属性名为 力量/敏捷/体质/智力/感知/魅力）');
        if ($args[1] == '自由属性点') checkAuth($event);
        if (!isset($args[2])) q('加点失败：未指定点数');
        $args[2] = intval($args[2]);
        if ($args[2] < 0) checkAuth($event);
        if (isset($args[3])) checkAuth($event);
		$atqq = isset($args[3]) ? parseQQ($args[3]) : null;
		$User_id = $event->getId();
		$qq = $atqq ? $atqq : $User_id;
		if($event instanceof GroupMessageEvent){
			$msg .= CQCode::At($User_id)."\n";
        }
        // $obj = array('mod' => 'home_userattr', 'action'=>'addUserAttr', 'qq'=>$qq);
        $obj = array();
        switch ($args[1]) {
            case '力量':
                $obj['str'] = $args[2];
                break;
            case '敏捷':
                $obj['dex'] = $args[2];
                break;
            case '体质':
                $obj['con'] = $args[2];
                break;
            case '智力':
                $obj['ine'] = $args[2];
                break;
            case '感知':
                $obj['wis'] = $args[2];
                break;
            case '魅力':
                $obj['cha'] = $args[2];
                break;
            case '自由属性点':
                $obj['free'] = $args[2];
                break;
            default:
                q('加点失败：未知属性名（可用属性名为 力量/敏捷/体质/智力/感知/魅力）');
                break;
        }
        // $data = param_post('http://asin.ygame.cc/api.php',$obj);
        $dAttr = new \Domain\UserAttr();
        $addAttr = $dAttr->addAttr($qq, $obj);
        if ($addAttr === -1) {
            $msg .= '加点失败：自由属性点不足';
        } elseif ($addAttr === -2) {
            $msg .= '加点失败：您没有加入刺客组织';
        } elseif (!$addAttr) {
            $msg .= '加点失败';
        } else {
            $msg .= "增加 {$args[1]} {$args[2]} 点成功，当前属性点为：\n";
            $userAttr = $dAttr->getUserAttr($qq);
            $msg .= '力量：'.$userAttr['str']."\n";
            $msg .= '敏捷：'.$userAttr['dex']."\n";
            $msg .= '体质：'.$userAttr['con']."\n";
            $msg .= '智力：'.$userAttr['ine']."\n";
            $msg .= '感知：'.$userAttr['wis']."\n";
            $msg .= '魅力：'.$userAttr['cha']."\n";
            $msg .= '自由属性点：'.$userAttr['free'];
        }
        return $event->sendBack($msg);
    }
}