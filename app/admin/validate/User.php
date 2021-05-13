<?php
/**
 *  Created by www.kuleiman.com.
 *  User: YaoHongQiang
 *  Date: 2021/5/12   14:19
 *  description:
 */

namespace app\admin\validate;



use think\Validate;

class User extends Validate
{
    protected $message=[
        "name:require"=>"用户名称不能为空",
        "name:max"=>"用户名称不能大于15位",
        "password:require"=>"请填写密码",
        "password:min"=>"密码不能少于6位",
        "password:max"=>"密码不能多于15位",
        "phone:require"=>"请填写管理员手机号",
        "group:require"=>"请选择分组",
        "id:require"=>"参数错误",
    ];
    public function sceneUserAdd(){
        return $this->append("name","require|max:15")
            ->append("phone","require")
            ->append("group","require");
    }
    public function sceneUserInfo(){
        return $this->append("id","require");
    }
}