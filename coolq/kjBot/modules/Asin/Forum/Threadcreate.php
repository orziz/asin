<?php

namespace kjBotModule\Asin\Forum;

use kjBot\Framework\Module;
use kjBot\Framework\Event\GroupMessageEvent;
use kjBot\SDK\CQCode;
use \Log;

/**
 * 查看用户信息
 */
class Threadcreate extends Module
{
	
	public function process(array $args, $event){
        checkAuth($event,'master');
        // ajax=1&fromBot=1&fid=1&subject=test&doctype=2&message=test
        // $msgData = array(
        //     'ajax' => 1,
        //     'fromBot' => 1,
        //     'fid' => 1,
        //     'subject' => $args[1],
        //     'doctype' => 2,
        //     'message' => implode("\n",explode('\n',$args['2']))
        // );
        $msgData = "ajax=1&fromBot=1&fid=1&subject=".$args[1]."&doctype=2&message=".implode("\n",explode('\n',$args['2']));
        Log::Debug('===<'.$msgData);
        $res = request_post('https://567.pohun.com/?thread-create.htm',$msgData);
        $res = json_decode($res,true);
        return $event->sendBack($res['message']);
    }
}