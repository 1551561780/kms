<?php
// +----------------------------------------------------------------------
// | Vens [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright©2014-2444 Vens Corp. All Rights Reserved
// +----------------------------------------------------------------------
// | Author: wz <1551561780@qq.com>
// +----------------------------------------------------------------------
// | Time: 2018-6-12
// +----------------------------------------------------------------------
// | Explanation:　登录控制器
// +----------------------------------------------------------------------
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Lang;
use app\admin\model\auth\Admin;
use app\admin\model\auth\Role;  
use app\admin\model\system\Config as ConfigModel;
use org\Verify; 


class Login extends Controller {


    public function _initialize(){
        global $C;
        parent::_initialize(); 

        //获取配置信息 
		/*$C =  cache('db_configw_data'); 
        if(!$C){
            $config_wz =  new ConfigModel();  
            $C =  $config_wz->configName(); 
            cache('db_configw_data',$C);
        }
		config($C); 
      // 语言检测
         $lang = strip_tags(Lang::detect()); 
         if($lang == 'zh-cn'){
              $lang1 = 'en-us';
         }else{
             $lang1 = 'zh-cn';
         }
         Lang::load(APP_PATH . $this->request->module() . '/lang/' . Lang::detect() . '/common' .EXT);
         $this->assign('lang', $lang);
         $this->assign('lang1', $lang1);*/
    }
	
    /**
     * 登录页面
     *
     * @return
    */
    public function index(){ 
        global $C;
     /*   switch (variable) {
        	case 'value':
        		# code...
        	break;
        	
        	default:
        		# code...
        	break;
        }*/ 
        return $this->fetch('index');
    }  

    /**
     * 获取用户错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
    */
    private function vensRegError($code = 0){
        switch ($code) {
            case -1:  $error = '请输入登录账号、登录密码！'; break;
            case -2:  $error = '账号不存在！'; break;
            case -3:  $error = '登录密码错误！'; break;
            case -4:  $error = '该账号未激活或被禁用，请联系Boss'; break;
            case -5:  $error = '登录失败'; break;
            case -6:  $error = '验证码错误或者以超时(点击验证码)'; break;

            case -14: $error = '注册失败！'; break;
            case -201: $error = '注册成功！'; break;
            default:  $error = '未知错误';
        }
        return $error;
    }


  //验证码图
    public  function verify(){
        ob_end_clean();
        $config =    array(
            'fontSize'    =>    30,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            // 'useNoise'    =>    false, // 关闭验证码杂点
            'expire'=>60,
        );

        $verify = new Verify($config);
        $verify->entry();
    }

   //验证验证码
    public function verify_code(){ 
        $param = input('param.');
        $verify = new Verify();
        $code = $param['code'];

        if($verify->check($code)){
            return json(['code'=>200,'msg'=>null,'data'=>null]);
        }else{

            return json(['code'=>101,'msg'=>'验证码错误或者以超时(点击验证码)','data'=>null]);
        }

    }

 


//登录操作
    public function sign(){
        $param = input('param.');

        //登录类型（password 一般的用户名/手机号/邮箱+密码方式、mobile手机号+短信验证码、weixin等）
        if(!isset($param["logintype"])){
            $logintype="password";
        }else{
            $logintype =$param["logintype"];
        }

        //账号、密码登录模式
        if($logintype=="password"){

            return $this->password();

        }elseif ($logintype=="mobile") { //通过手机号、验证码登录
            return $this->mobile();

        }elseif ($logintype=="open") { //通过第三方登录
            return $this->open();
        }elseif ($logintype=="weixin") { //通过微信登录

        }
    }


    //根据账号、密码登录（一般的用户名、密码，手机号、密码或邮箱密码登录）
    public function password(){
        //定义信息
        $param = input('param.');
        $username= $param["username"];
        $password = $param["userpassword"];

        if(empty($username) || empty($password)) return json(array('code'=>101,'msg'=>$this->showRegError('-1'),'data'=>null));
       //实例化
        $Umember = new Admin(); 
        //开启验证码状态
       if(isset($param["code"])){
            $verify = new Verify();
            if(!$verify->check($param["code"])){
                return json(['code'=>101,'msg'=>$this->showRegError('-6'),'data'=>null]);
            }
        }
	
	
        //获取用户信息
        $dataUser =  $Umember->where('adname',$username)->find();
 
        if(empty($dataUser)) return json(['code'=>101,'msg'=>$this->showRegError('-2'),'data'=>null]);

        //验证密码
        if(VensPassword($password,$dataUser->salt) != $dataUser->adpassword) return json(['code'=>101,'msg'=>$this->showRegError('-3'),'data'=>null]);
        //用户状态
        if(1 != $dataUser->status){
           
            writelog($dataUser->adminid, $username, '用户【' . $username . '】登录失败:'.$this->showRegError('-4'), 2);
            return json(['code'=>101,'msg'=>$this->showRegError('-4'),'data'=>null]);
        }   
        //保存登录信息
        return $this->save($dataUser);

    }

    //保存登录信息
    public function save($dataUser=array()){
        global $C;
         $Umember = new Admin(); 



        //登录失败返回
        if(!$dataUser)  return $this->json(['code'=>101,'msg'=>$this->showRegError('-5'),'data'=>null]);
        //获取用户权限
        $role = new Role();
        $roledata = $role->where('roleid',$dataUser['role_id'])->find();
        $dataUser['rolename'] = $roledata['rolename'] ;
        $dataUser['menu_id'] = $roledata['menu_id'] ;
        //非完整数据时，从缓存读取用户当前数据(有缓存情况下)

        //SESSION 机制
        $sessionwz =  new \com\Sessionwz();
        $sessionwz->set('isLogin', $dataUser->adminid,APP_CATALOG);
        $sessionwz->set('adminid', $dataUser->adminid,APP_CATALOG);
        $sessionwz->set('admindata', $dataUser,APP_CATALOG);

        //token 未加

        //保存登录信息（最后登录时间、次数等）

        $data['lastlogintime'] = time();
        $data['lastloginip'] = get_client_ip();
        $num = $Umember->where('adminid',$dataUser->adminid)->update($data);
        if($num){
            writelog($dataUser->adminid, $dataUser->adname, '用户【' . $dataUser->adname . '】登录成功', 1); 
            //登录成功返回  
            return json(['code'=>200,
                'msg'=>'登录成功',
                'data'=>[
                    'adminid'=>$dataUser['adminid'],  
                    'admindata'=>$dataUser
                ]]);
        }
        
       

    }



   //退出操作
    public function out(){
	
        $sessionwz =  new \com\Sessionwz();
 
        // 清除think作用域
        session(null,APP_CATALOG); 
		cache('db_configw_data', null); //清除缓存中网站配置信息
        $this->redirect(url('index')); 
       // $this->success('退出成功','index');
       
    }


}
