<?php
namespace kjBotPlugin\GroupCheck\AsinGroup;

use kjBot\Framework\Plugin;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjBot\Framework\TargetType;
use kjBot\Framework\DataStorage;
use \Log;

class _Meta_event extends Plugin {
    public $handleDepth = 2; //捕获到最底层的事件
    public $handleQueue = true; //声明是否要捕获消息队列

    public function beforePostMessage(&$queue){} //若声明不需要捕获消息队列可不实现本方法
    //此处以正常群聊消息举例
    public function meta_event_heartbeat($event) {
        $Queue[] = $this->asinFight($event);
        return $Queue;
    }

    private function asinFight($event) {
        $actName = '【刺客模拟赛】';
        $readyTime = 1;
        $asinFightData = DataStorage::GetData('asinFightData.json');
        $asinFightData = $asinFightData ? json_decode($asinFightData,true) : array();
        // 0：关闭；1：开启；2：准备；3：开始；4：过程中
        if (!isset($asinFightData['status']) || $asinFightData['status'] === 0) return NULL;
        if (isset($asinFightData['status']) && $asinFightData['status'] === 1) {
            $asinFightData['status'] = 2;
            $asinFightData['readyTime'] = 0;
            $asinFightData['data'] = array();
            DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
            return $event->sendTo(TargetType::Group,'719994813',"{$actName}将在 {$readyTime} 分钟后开启，请参加的刺客回复`参加刺客模拟赛`");
        } elseif (isset($asinFightData['status']) && $asinFightData['status'] === 2) {
            $asinFightData['readyTime'] = isset($asinFightData['readyTime']) ? $asinFightData['readyTime'] : 0;
            $asinFightData['readyTime'] = $asinFightData['readyTime'] + 1;
            if ($asinFightData['readyTime'] >= ($readyTime*6-1)) {
                $asinFightData['status'] = 3;
            }
            DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
            return NULL;
        } elseif (isset($asinFightData['status']) && $asinFightData['status'] === 3) {
            if (!isset($asinFightData['data']) || count($asinFightData['data']) < 2) {
                $asinFightData['status'] = 0;
                DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
                return $event->sendTo(TargetType::Group,'719994813',"本次{$actName}参赛人数不足，活动取消");
            }
            $asinFightData['status'] = 4;
            DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
            return NULL;
        } elseif (isset($asinFightData['status']) && $asinFightData['status'] === 4) {
            if (count($asinFightData['data']) <= 1) {
                $asinFightData['status'] = 0;
                DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
                $member = array_keys($asinFightData['data']);
                $user = $member[0];
                return $event->sendTo(TargetType::Group,'719994813',"本次{$actName}活动结束，胜利者为 ".CQCode::At($user)); 
            }
            // 从参赛人员中随机获取两名成员
            $fightData = array_rand($asinFightData['data'],2);
            // 获取参赛人员的id
            Log::Debug('--->'.json_encode($fightData));
            $fightMember = array_keys($fightData);
            Log::Debug('===>'.json_encode($fightMember));
            // 指定user1的id
            $user1 = $fightMember[0];
            // 指定user2的id
            $user2 = $fightMember[1];
            // 随机谁挨打
            $rand = mt_rand(1,100);
            if ($rand <= 50) {
                $atkUser = $user1;
                $hurtUser = $user2;
            } else {
                $atkUser = $user2;
                $hurtUser = $user1;
            }
            $hurt = min($fightData[$hurtUser]['bld'],mt_rand(1,50));
            $eventList = [
                "%s（%d）绕到 %s（%d）身后，给予沉重一击，造成 %d 点伤害",
            ];
            $msg = sprintf($eventList[mt_rand(0,count($eventList)-1)], CQCode::At($atkUser),$asinFightData['data'][$atkUser]['bld'],CQCode::At($hurtUser),$asinFightData['data'][$hurtUser]['bld'],$hurt);
            if ($hurt >= $fightData[$hurtUser]['bld']) {
                $msg .= "\n".CQCode::At($hurtUser)." 重伤淘汰，本次{$actName}排名为：".count($asinFightData['data']);
                unset($asinFightData['data'][$hurtUser]);
                DataStorage::SetData('asinFightData.json',json_encode($asinFightData));
            }
            return $event->sendTo(TargetType::Group,'719994813',$msg);
        }
        return NULL;
    }

}