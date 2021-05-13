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

    /**
     * 管理员列表
     * @param string $keyword
     * @param int $status
     * @return array
     * @throws \think\db\exception\DbException
     */
    function user_list(string $keyword='',int $status=0){
        $where=" 1 ";
        if($keyword){
            $where.=" and name like '%".$keyword."%'";
        }
        if($status){
            $where.=' and status ='.$status;
        }
        $list=Db::name("user")->where($where)->order("id desc")->paginate(10);
        $page = $list->render();
        return ['list'=>$list,"page"=>$page];
    }

    /**
     * 管理员详情
     * @param $id
     * @return array|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    function user_info($id){
        $user_info=Db::name("user")->where(["id"=>$id])->find();
        return $user_info;
    }

    /**
     * 用户禁用/启用
     * @param int $id
     * @param int $status
     * @return array|int[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    function user_disable(int $id,int $status=0){
        $user_info=$this->user_info($id);
        if(empty($user_info)){
            return ['error'=>10000,"未找到用户"];
        }else{
            if(!$status){
                $status=$user_info["status"]==2?1:2;
            }
           Db::name("user")->where(["id"=>$id])->update(["status"=>$status]);
        }
        return ['error'=>0];
    }
    /**
     * 用户删除
     * @param int $id
     * @param int $status
     * @return array|int[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    function user_del(int $id){
        $user_info=$this->user_info($id);
        if(empty($user_info)){
            return ['error'=>10000,"未找到用户"];
        }else{
            Db::name("user")->where(["id"=>$id])->delete();
        }
        return ['error'=>0];
    }
}