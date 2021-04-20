<?php
/**
 *  User: YaoHongQiang
 *  Date: 2021/4/20   14:42
 *  description:
 */

namespace app\admin;
use app\admin\controller\Login;
use app\BaseController;
use think\exception\HttpResponseException;
use think\facade\View;
use think\wenhainan\Auth;

class AdminBase extends BaseController
{

    public function initialize()
    {
        session('uid',1);
        if(!session('uid')){
            $url = url('admin/login/index',["tip"=>1])->domain(true);
            header("location:" . $url);
            die;
        }
        $module = app('http')->getName();
        $controller = request()->controller();
        $action = request()->action();
        $auth = new Auth();
        if(!$auth->check($module.'-'.$controller . '-' . $action, session('uid'))){
            abort(501, '没有访问权限');
        }
    }
}