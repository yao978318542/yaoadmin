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
        if(!session('uid')){
            $url = url('admin/login/index',["tip"=>1])->domain(true);
            header("location:" . $url);
            die;
        }
        $controller = request()->controller();
        $action = request()->action();
        $auth = new Auth();
        if(!$auth->check($controller . '-' . $action, session('uid'))){
            $this->error('你没有权限访问');
        }
    }
}