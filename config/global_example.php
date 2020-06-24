<?php

return array(

    // 数据库相关
    'db' => array(

        //数据库地址
        "host" => '',

        //数据库端口
        "port" => 3306,

        //数据库用户名
        'user' => '',

        //数据库密码
        'pwd' => '',

        //数据库库名
        'base' => '',

        //数据表前缀
        'tablepre' => '',

        //数据库编码
        'charset' => ''
    ),

    // jwt相关
    'jwt' => array(

        // jwt加密密钥
        'secret' => '',
        
        // jwt过期时长
        'time' => 60*60*24
    ),

    // 参数键分隔符，含请求参数及配置
    'paramKeySeparator' => '.',

    // 是否允许跨域
    'canCrossDomain' => true
);
