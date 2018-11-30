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
namespace app\admin\controller;
use app\api\controller\Base;
class Index extends Base
{

	#
	public function _initialize(){

		parent::_initialize();

	}
 

 	#加载首页框架
 	#
    public function index(){
    	//$param = vensTrim(input('param.')); 	
      
        return $this->fetch();
    }

    public function indexpage(){
    	 return $this->fetch();
    }


}
