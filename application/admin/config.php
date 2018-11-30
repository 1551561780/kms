<?php

//define('WZ_DATABASE_PREFIX', config('database.prefix'));//
//define('WZ_CACHE', '/runtime/admin/');//本地缓存目录
 
//配置文件
return [ 
    // //模板参数替换
     'view_replace_str'       => array( 
		 '__H+__' =>  '/public/static/admin/hplus', 
		 '__Layui__' =>  '/public/static/common/layui', 
		 '__Layer__' =>  '/public/static/common/layer', 
		 //'__Hecharts__' =>  '/public/static/echarts',   
	 ),

	//'配置项'=>'配置值'
	'LANG_SWITCH_ON'     =>     true,    //开启语言包功能        
	'LANG_AUTO_DETECT'   =>     true, // 自动侦测语言
	'DEFAULT_LANG'       =>     'zh-cn', // 默认语言        
	'LANG_LIST'          =>    'en-us,zh-cn,zh-tw', //必须写可允许的语言列表
	'VAR_LANGUAGE'       => 'l', // 默认语言切换变量
 
   
    
];