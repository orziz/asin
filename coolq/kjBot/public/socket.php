<?php

require_once '../../../source/class/class_core.php';
set_time_limit(0); //限制执行时间  0为不限制
$ip = '127.0.0.1';
$port = 7001;//端口
/**
socket通信整个过程 
socket_create  //创建一个套接字
socket_bind  //给套接字绑定 ip 和端口
socket_listen //监听套接字上的连接
socket_accept //接受一个socket连接
socket_read //接收客户端 发送的数据
socket_write //将数据写到 socket 缓存 向客户端发送
socket_close   //关闭套接字资源
*/

echo '启动socket';
if(($sock = socket_create(AF_INET,SOCK_STREAM,SOL_TCP)) < 0) {
    echo "socket_create() 失败的原因是:".socket_strerror($sock)."\n";
    Log::Debug("socket_create() 失败的原因是:".socket_strerror($sock));
}
// if(($ret = socket_bind($sock,$ip,$port)) < 0) {
//     echo "socket_bind() 失败的原因是:".socket_strerror($ret)."\n";
//     Log::Debug("socket_bind() 失败的原因是:".socket_strerror($ret));
// }
// if(($ret = socket_listen($sock,4)) < 0) {
//     echo "socket_listen() 失败的原因是:".socket_strerror($ret)."\n";
//     Log::Debug("socket_listen() 失败的原因是:".socket_strerror($ret));
// }
$ref = socket_connect($sock, $ip, $port);
if ($ref < 0) {
	echo '链接失败';
}
do {
    if (($msgsock = socket_accept($sock)) < 0) {
        echo "socket_accept() failed: reason: " . socket_strerror($msgsock) . "\n";
    	Log::Debug("socket_accept() failed: reason: " . socket_strerror($msgsock));
    } else {
         
        Log::Debug('收到消息');
        //发到客户端
        $msg ="测试成功！\n";
        socket_write($msgsock, $msg, strlen($msg));
         
        echo "测试成功了啊\n";
        $buf = socket_read($msgsock,8192);
         
         
        $talkback = "收到的信息:$buf\n";
        echo $talkback;
    }
    //echo $buf;
    // socket_close($msgsock);
    // 
    usleep(5000);
} while (true);
// socket_close($sock);