<?php

/** 大富翁入口文件 */

namespace kjBotModule\Monopoly;

use kjBot\Framework\Module;
use kjBot\Framework\Message;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use kjbotModule\Monopoly\Data;

/**
 * 帮助文档
 */
class Main extends Module {

	public function process(array $args, $event) {
        if (!isset($args[1])) q('请输入指令');
        $obj = [
            '开始' => "$this->init"
        ];
        return $obj[$args[1]]($event);
    }

    public function init($event) {
        $state = Data::getDataByKey('state', 0);
        if ($state > 0) q('游戏已开始');
        Data::setDataByKey('state', 1);
        return $event->sendBack('大富翁开启成功，玩家可加入游戏');
    }

}