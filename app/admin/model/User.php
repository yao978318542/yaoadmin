<?php
/**
 *  Created by www.kuleiman.com.
 *  User: YaoHongQiang
 *  Date: 2021/5/11   14:24
 *  description:
 */

namespace app\admin\model;


use think\Model;
use think\facade\Db;
class User extends Model{
    protected $table = "auth_rule";

    function user_list(string $keyword,int $status=2){
        $where=' status ='.$status;
        if($keyword){
            $where.="name like '%".$keyword."%'";
        }
        $list=Db::name("user")->where($where)->order("id desc")->paginate(10);
        $page = $list->render();
        return ['list'=>$list,"page"=>$page];
    }
}