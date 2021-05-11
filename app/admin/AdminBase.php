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
use app\admin\model\Auth as MemberAuth;

class AdminBase extends BaseController
{

    public function initialize()
    {
        session('uid',1);
        session('user_info',["group_id"=>1]);
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
            abort(500, '没有访问权限');
        }
        //查找菜单
        $menu=session('menu');
        if(empty($menu)){
            $model=new MemberAuth();
            $menu=$model->get_menu();
            if(!empty($menu)){
                session('menu',$menu);
            }
        }
        View::assign('menu',$menu);
    }
    /**
     * 验证数据
     * @param $list
     * @param $validate
     * @return bool|\think\response\Json
     */
    protected function validateList($list,$validate){
        try {
            $this->validate($list, $validate);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return $this->validateError($e->getError());
        }catch (\Exception $e) {
            // 这是进行异常捕获
            return $this->validateError($e->getError());
        }
        return true;
    }
    /**
     * 操作错误的快捷方法
     * @param int $errno 验证失败10000-10999 操作失败11000开始
     * @param string $msg
     * @param array $data
     * @return \think\response\Json
     */
    public function error(int $errno, string $msg, array $data = [])
    {
        return json(['status' => $errno, 'message' => $msg, 'data' => $data]);
    }


    /**
     * 操作成功的快捷方法
     * @param string $msg
     * @param array $data
     * @return \think\response\Json
     */
    public function success( string $msg, array $data = []){
        return json(['status' => 200, 'message' => $msg, 'data' => $data]);
    }
    /**
     * 验证器验证页面传入参数数据失败的方法
     * @param string $msg
     * @return \think\response\Json
     */
    final public function validateError(string $msg,int $code=10001)
    {
        return $this->error($code, $msg);
    }
}