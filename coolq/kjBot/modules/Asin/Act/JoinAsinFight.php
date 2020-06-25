<?php

namespace kjBotModule\Asin\Act;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\Framework\DataStorage;
use kjBot\SDK\CQCode;

/**
 * 参加刺客模拟赛活动
 */
class JoinAsinFight extends Module
{
	
	public function process(array $args, $event){
        $msg = '';
        $asinFightData = DataStorage::GetData('asinFightData.json');
        $asinFightData = $asinFightData ? json_decode($asinFightData,true) : array();
        if (!isset($asinFightData['status']) || $asinFightData['status'] === 0 || $asinFightData['status'] === 1) return $event->sendBack('活动未开始');
        if (!isset($asinFightData['status']) || $asinFightData['status'] === 3 || $asinFightData['status'] === 4) return $event->sendBack('本次活动进行中，请耐心等待活动结束');
        $User_id = $event->getId();
        if (!isset($asinFightData['data'])) $asinFightData['data'] = array();
        if (isset($asinFightData['data'][$User_id])) return $event->sendBack(CQCode::At($User_id).' 您已参加本次活动，无需重复参加');
        
        $msg .= CQCode::At($User_id);

        $dAttr = new \Domain\UserAttr();
        $userAttr = $dAttr->getUserAttrWithFight($User_id);
        if (!$userAttr) {
            $msg .= ' 参加失败：您没有加入刺客组织';
        } else {
            $msg .= ' 参加了本次活动，';
            // $msg .= '姓名：'.$userAttr['nickname']."\n";
            // $msg .= '力量：'.$userAttr['str']."\n";
            // $msg .= '敏捷：'.$userAttr['dex']."\n";
            // $msg .= '体质：'.$userAttr['con']."\n";
            // $msg .= '智力：'.$userAttr['ine']."\n";
            // $msg .= '感知：'.$userAttr['wis']."\n";
            // $msg .= '魅力：'.$userAttr['cha']."\n";
            // $msg .= '自由属性点：'.$userAttr['free'];

            $userAttr['groupId'] = $this->getGroupId($event);
            $asinFightData['data'][$User_id] = $userAttr;
            $asinFightData['memberNum'] = isset($asinFightData['memberNum']) ? $asinFightData['memberNum'] +1 : 1;
            DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
            $msgList = [
                "好运光环笼罩着TA",
                "经过系统评定，本次胜利概率为 ".mt_rand(1,99).'%',
                "有望获得胜利",
                "不出意外的话，应该是获胜不了的",
                "听说TA又想去看情侣秀恩爱了",
                "还没开始就失败了（骗你的啦）",
                "获得了强大的BUFF",
                "获得了“子不语牛逼”的BUFF加成"
            ];
            $msg .= $msgList[mt_rand(0,count($msgList)-1)];
        }
        return $event->sendBack($msg);
    }

    private function getGroupId($event) {
        $arr = array(
            '719994813'=>'重构',
            '666427165'=>'1',
            '758507034'=>'4',
            '371975682'=>'5'
        );
        $groupId = $event->groupId;
        $id = isset($arr[$groupId]) ? $arr[$groupId] : 0;
        return ($id);
    }
}