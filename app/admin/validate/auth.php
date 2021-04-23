<?php
namespace app\admin\validate;

use think\Validate;

class Auth extends Validate
{
    protected $message=[
        "title:require"=>"请填写菜单名称",
        "title:max"=>"菜单名称错误",
        "type:require"=>"菜单类型错误",
        "type:number"=>"菜单类型错误",
        "type:in"=>"菜单类型错误",
        "name:require"=>"请填写菜单规则",
    ];
    public function sceneAuthAdd(){
        return $this->append("title","require|max:10")
            ->append("type","require|number|in:1,2,3")
            ->append("name","require");
    }
}