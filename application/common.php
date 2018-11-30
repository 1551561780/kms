<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
#定义常量
define('VENS_ROOT',str_replace("\\", '/', substr(dirname(__FILE__), 0, -7)));
define('NOW_TIME',time());
 #引入函数库
 require VENS_ROOT.'/../vens/function/vens.php'; 