<?php
/**
 *       ～～         ～～        　～～
 *    ~~　　　　　~~　　　　　　~~
 * ~~～~~～~~　　~~~～~~～~~～　　~~~～~~～~~～ 
 * ·········   ·········Bury·······  ················
 * ·················································· 
 * @Author: wz <1551561780@qq.com>
 * @Date:   
 * @Explanation: Index 首页 框架  
 */ 

namespace app\index\controller;
use think\Db;
use think\Controller; 
use think\Request;   
use think\Hook;  
use think\Lang;

class Index extends Controller
{
 
    #加载首页框架
 	#
    public function index(){ 
    	//$param = vensTrim(input('param.')); 	
      
        return $this->fetch(); 
    }


    public function upload(Request $request){
    	 //实例化并获取系统变量 
        try{
            //获取图片对象
            $filetemp = $request->file('file');
            
            //存放服务器上地址 
            $serverFile = $filetemp->move(ROOT_PATH.'/public/uploads/');
            
            //访问地址
            $imageUrl = 'http://'.$_SERVER['HTTP_HOST'].str_replace(ROOT_PATH.'/public', '', $serverFile->getPathname());
            
            $ajaxJson['success'] = true;
            $ajaxJson['msg'] = $imageUrl;
        }catch(Exception $e){
            $ajaxJson['success'] = false;
        }
        
        
        return json_encode($ajaxJson);
    }
 
   
 
}

 

 
