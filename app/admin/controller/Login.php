<?php
/**
 *  Created by www.kuleiman.com.
 *  User: YaoHongQiang
 *  Date: 2021/4/20   15:36
 *  description:
 */

namespace app\admin\controller;


use think\facade\View;

class Login extends \app\BaseController
{

    function index(){
        return View::fetch();
    }
}