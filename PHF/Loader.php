<?php

namespace PHF;

class Loader
{
    /* 路径映射 */
    public static $vendorMap = array(
        'APP' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR,
        'DB' => __DIR__ . DIRECTORY_SEPARATOR . 'DB',
        // 核心层
        'PHF' => __DIR__ . DIRECTORY_SEPARATOR,
        // 模型层
        'Model' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Model',
        // 模型层
        'Domain' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Domain',
        // api层
        'Api' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Api',
        'Module' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Module',
        // 插件层
        'Plugin' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Plugin',


        "kjBot" =>  __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'coolq' . DIRECTORY_SEPARATOR . 'kjBot',
        'kjBot\\SDK' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'coolq' . DIRECTORY_SEPARATOR . 'kjBot'. DIRECTORY_SEPARATOR . "SDK",
        'kjBot\\Framework' => __DIR__ . DIRECTORY_SEPARATOR,
        'kjBotModule' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'coolq' . DIRECTORY_SEPARATOR . 'kjBot'. DIRECTORY_SEPARATOR . 'modules',
        'kjBotPlugin' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'coolq' . DIRECTORY_SEPARATOR . 'kjBot'. DIRECTORY_SEPARATOR. 'plugins'
    );

    /**
     * 自动加载器
     */
    public static function autoload($class)
    {
        $file = self::findFile($class);
        if (file_exists($file)) {
            self::includeFile($file);
        }
    }

    /**
     * 解析文件路径
     */
    private static function findFile($class)
    {
        $vendor = substr($class, 0, strpos($class, '\\')); // 顶级命名空间
        if (in_array($vendor, array_keys(["kjBot", "kjBotPlugin"]))) {
            return;
        }
        $vendorDir = self::$vendorMap[$vendor]; // 文件基目录
        $filePath = substr($class, strlen($vendor)) . '.php'; // 文件相对路径
        return strtr($vendorDir . $filePath, '\\', DIRECTORY_SEPARATOR); // 文件标准路径
    }

    /**
     * 引入文件
     */
    private static function includeFile($file)
    {
        if (is_file($file)) {
            include $file;
        }
    }
}