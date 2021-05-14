<?php
/**
 *  Created by www.kuleiman.com.
 *  User: YaoHongQiang
 *  Date: 2021/4/20   15:36
 *  description:
 */

namespace app\admin\controller;


use app\BaseController;
use think\facade\View;
use think\facade\Db;

class Login extends BaseController
{

    function index(){
        return View::fetch();
    }
    function login(){
        $phone=input("post.phone/s","");
        $password=input("post.password/s","");
        try {
            $this->validate(input(), 'Login.Login');
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return json(['status' => 10000, 'message' => $e->getError()]);
        }catch (\Exception $e) {
            // 这是进行异常捕获
            return json(['status' => 10000, 'message' => $e->getError()]);
        }
        $where="phone=".$phone;
        $admin=Db::name("user")->where($where)->find();
        if(empty($admin)||!password_verify($password,$admin['password'])){
            return json(['status' => 10000, 'message' => "用户名或密码错误"]);
        }else{
            session("uid",$admin["id"]);
            session("user_info",$admin);
            return json(['status' => 0, 'message' => "登陆成功","url"=>"/admin/user/index"]);
        }

    }
    function login_out(){
        session("uid",null);
        session("user_info",null);
        session('menu',null);
        return json(['status' => 0, 'message' => "您已退出登陆","url"=>"/admin/login/index"]);
    }
}