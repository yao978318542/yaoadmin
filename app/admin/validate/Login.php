<?php
/**
 *  Created by www.kuleiman.com.
 *  User: YaoHongQiang
 *  Date: 2021/5/14   14:25
 *  description:
 */

namespace app\admin\validate;


use think\Validate;

class Login extends Validate
{

    protected $message=[
        "phone:require"=>"手机号不能为空",
        "password:require"=>"请填写密码",
        "password:min"=>"密码不能少于6位",
        "password:max"=>"密码不能多于15位",
    ];
    public function sceneLogin(){
        return $this->append("phone","require")
            ->append("password","require|min:6|max:15");
    }
}