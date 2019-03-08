<?php
set_time_limit(0); //限制执行时间  0为不限制
$ip = '0.0.0.0';
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

system('echo 11111');
if(($sock = socket_create(AF_INET,SOCK_STREAM,SOL_TCP)) < 0) {
    echo "socket_create() 失败的原因是:".socket_strerror($sock)."\n";
}
if(($ret = socket_bind($sock,$ip,$port)) < 0) {
    echo "socket_bind() 失败的原因是:".socket_strerror($ret)."\n";
}
if(($ret = socket_listen($sock,4)) < 0) {
    echo "socket_listen() 失败的原因是:".socket_strerror($ret)."\n";
}
$count = 0;
do {
    if (($msgsock = socket_accept($sock)) < 0) {
        echo "socket_accept() failed: reason: " . socket_strerror($msgsock) . "\n";
        break;
    } else {
         
        //发到客户端
        $msg ="测试成功！\n";
        socket_write($msgsock, $msg, strlen($msg));
         
        echo "测试成功了啊\n";
        $buf = socket_read($msgsock,8192);
         
         
        $talkback = "收到的信息:$buf\n";
        echo $talkback;
         
        if(++$count >= 5){
            break;
        };
         
     
    }
    //echo $buf;
    // socket_close($msgsock);
} while (true);
// socket_close($sock);