<?php

namespace kjbotmodule\Ninepm;

use kjBot\SDK\CQCode;
use kjbotModule\Ninepm\Data;

class Action {

    public function init($event) {
        $state = Data::getDataByKey('state', 0);
        if ($state > 0) q('游戏已开始');
        Data::setDataByKey('state', 1);
        return $event->sendBack('21点开启成功，玩家可加入游戏，由 '.CQCode::At($event->getId()).' 坐庄');
    }

}