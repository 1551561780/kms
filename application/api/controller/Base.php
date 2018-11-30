<?php
namespace app\api\controller;
   
use think\Controller; 
use think\Request;  
use think\Db;
use think\Hook;  
use think\Lang; 
use think\Auth;
use think\Config;
use app\admin\model\system\Config as ConfigModel;
use app\admin\model\auth\Menu;


class Base extends Controller
{
 
 

	  public function _initialize(){

        global $C;
   
/*
        //获取配置信息 
		$C =  cache('db_configw_data'); 
        if(!$C){
            $config_wz =  new ConfigModel();  
            $C =  $config_wz->configName(); 
            cache('db_configw_data',$C);
        }
		config($C);
        $this->assign('C',$C); 
 
		

      	define('CONTROLLER_NAME',Request::instance()->controller());//控制器模型
        define('MODULE_NAME',Request::instance()->module());//控制器
        define('ACTION_NAME',Request::instance()->action());//方法
      
 
    //权限操作
 	 	$auth = new \com\Auth();   
        $module     = strtolower(request()->module());
        $controller = strtolower(request()->controller());
        $action     = strtolower(request()->action());

 	 	$url = $module."/".$controller."/".$action;
 	 //session 在 admin 作用域下
 	   $sessionwz =  new \com\Sessionwz();
        if (!$sessionwz->get('isLogin',APP_CATALOG)) { 
             $this->error('请登录', 'login/index'); 
             exit;
         }

  
		//跳过检测以及主页权限
        if($sessionwz->get('adminid', APP_CATALOG)!=1){
            if(!in_array($url, ['admin/index/index','admin/index/indexpage','admin/auth.admin/edit'])){//'admin/index/indexpage','admin/upload/upload','admin/index/uploadface' 
                if(!$auth->check($url,$sessionwz->get('adminid',APP_CATALOG))){
                    $this->error('抱歉，您没有操作权限','index/indexpage');
                }
            }

        }*/

 

	  	//后台菜单
	 
        //$this->assign('adminmenu',menus()); 

        //加载当前的空之器
        $controllername = strtolower($this->request->controller());
 
        // 语言检测
        $lang = strip_tags(Lang::detect());
  
        // 配置信息
        $config = [
            'language'   => $lang,
        ];
        // 配置信息后
        Hook::listen("config_init", $config);
        //var_dump($controllername);  
        //加载基础配置
        // Lang::load(APP_PATH . $this->request->module() . '/lang/' . Lang::detect() . '/common' .EXT);
        // Lang::load(APP_PATH . $this->request->module() . '/lang/' . Lang::detect() . '/menu' .EXT);
        // Lang::load(APP_PATH . $this->request->module() . '/lang/' . Lang::detect() . '/role' .EXT);

        //加载当前控制器语言包
        $this->loadlang($controllername);
 	 	//

  
	  }

 /**
     * 加载语言文件
     * @param string $name
     */
    protected function loadlang($name)
    {
       
         Lang::load(APP_PATH . $this->request->module() . '/lang/' . Lang::detect() . '/' . str_replace('.', '/', $name) .EXT);
    }


    #空操作
	 public function _empty($name){
    	return $this->error('该方法不存在！');
        //return $this->showCity($name);
     }
	  /**
 * 整理菜单树方法
 * @param $param
 * @return array
 */
public function prepareMenu($param)
{
    $parent = []; //父类
    $child = [];  //子类

    foreach($param as $key=>$vo){

        if($vo['menupid'] == 0){
            $vo['menuurl'] =  $vo['menuname']?$vo['menuname']:'#';
            $vo['menuurl'] = url($vo['menuurl']);
            $parent[] = $vo;
        }else{
            $vo['menuurl'] = url($vo['menuname']); //跳转地址
            $child[] = $vo;
        }
    }

    foreach($parent as $key=>$vo){
        foreach($child as $k=>$v){

            if($v['menupid'] == $vo['menuid']){
                $parent[$key]['child'][] = $v;
            }
        }
    }
    unset($child);
    return $parent;
}

}