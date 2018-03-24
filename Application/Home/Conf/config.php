<?php
return array(
	//'配置项'=>'配置值'
    'HTML_CACHE_ON'     =>    false, // 开启静态缓存
    'HTML_CACHE_TIME'   =>    60,   // 全局静态缓存有效期（秒）
    'HTML_FILE_SUFFIX'  =>    '.shtml', // 设置静态缓存文件后缀
    //这个模块中那些页面生成静态页,不配置所有界面都生成静态页面
    'HTML_CACHE_RULES'  =>     array(  // 定义静态缓存规则
    // 定义格式1 数组方式（控制器：方法）
        'index:index'    =>     array('index', '86400'),//第一个参数是静态页面名字，自定义，第二个参数是时间
        'index:goods'    =>     array('goods-{id}', '86400'),//第一个参数是静态页面名字，自定义，第二个参数是时间

    )
);