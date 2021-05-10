<?php
namespace app\admin\model;
use think\Model;
use think\facade\Db;
class Auth extends Model{
    protected $table = "auth_rule";

    /**\
     * @param $type
     * @param string $keyword
     * @param int $status 1关闭2 开启 数据库0关闭1开启
     * @param int $retutn_type 返回值类型 1 数据库普通数据
     * @param int $id 权限组的id
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundExceptio
     *
     */
    function auth_list($type,string $keyword="",int $status=0,int $return_type=0,$id=''){
        $where=" 1 ";
        $post_status=0;
        if($status>0){
            $post_status=$status;
            $status=$status-1;
            $where.=" and  status =".$status;
        }
        if($keyword){
            $where.=" AND title like '%".$keyword."%'";
        }

        $auths=Db::name("auth_rule")
            ->where($where)
            ->order("sort desc,id desc")
            ->select()
            ->toArray();
        if(($keyword||$post_status>0)&&$return_type){
            $tree_list=[];
            if(!empty($auths)){
                foreach ($auths as $auth){
                    $tree_list[]= [
                        "id"=>$auth["id"],
                        "text"=>$auth["title"],
                        "parent_id"=>$auth["parent_id"],
                        "info"=>$auth,
                    ];
                }
            }
            return $tree_list;
        }
        $tree_list=$this->auth_tree($auths,0,0);
        if($type=="obj"){
            if($id){
                $rules=Db::name("auth_group")->where(["id"=>$id])->value("rules");
                $rules=explode(",",$rules);

            }else{
                $rules='';
            }
            return $this->auth_tree_option($tree_list,0,$rules);
        }elseif($type=="option"){
            return $this->auth_tree_option_html($tree_list,0);
        }else{
            return $tree_list;
        }
    }
    /**
     * 权限树
     * @param $auths
     * @param $parent_id
     * @param $level
     * @return array
     */
    function auth_tree($auths,$parent_id,$level){
        $tree_list=[];
        $level++;
        if(!empty($auths)){
            foreach($auths as $key=>$auth){
                if($auth['parent_id']==$parent_id){
                    $tree_list[]= [
                        "id"=>$auth["id"],
                        "text"=>$auth["title"],
                        "parent_id"=>$auth["parent_id"],
                        "level"=>$level,
                        "info"=>$auth,
                        "nodes"=>$this->auth_tree($auths,$auth['id'],$level),
                    ];
                }
            }
        }
        return $tree_list;
    }
    function auth_tree_option($tree_list,$parent_id,$rules=''){
        $option_obj=[];
        $level_html="";

        if(!empty($tree_list)){
            foreach($tree_list as $key=>$auth){
                if($auth['parent_id']==$parent_id){
                    $data=[
                        "id"=>$auth["id"],
                        "nodeId"=>$auth["id"],
                        "text"=>$auth["text"],
                        "state"=>['checked'=>true,'expanded'=>true],
                    ];
                    if(!empty($rules)){
                        if(in_array($auth["id"],$rules)){
                            $data["state"]["checked"]=true;
                        }else{
                            $data["state"]["checked"]=false;
                        }
                    }
                    if(!empty($auth['nodes'])){
                        $data['nodes']=$this->auth_tree_option($auth['nodes'],$auth['id'],$rules);
                    }
                    $option_obj[]=$data;
                }
            }
        }
        return $option_obj;
    }
    function auth_tree_option_html($tree_list,$parent_id){
        $option_html="";
        $level_html="";
        if(!empty($tree_list)){
            foreach($tree_list as $key=>$auth){
                if($auth['parent_id']==$parent_id){
                    $level_html="";
                    if($auth['level']>1){
                        for($i=0;$i<$auth['level'];$i++){
                            $level_html.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                        }
                        $level_html.="|-";
                    }
                    $option_html.="<option value ='".$auth["id"]."'>".$level_html.$auth["text"]."</option>";

                    if(!empty($auth['nodes'])){
                        $option_html.=$this->auth_tree_option_html($auth['nodes'],$auth['id']);
                    }
                }
            }
        }
        return $option_html;
    }

    /**
     * 获取给定id的所有子权限不包含自己
     * @param $id
     * @return array
     */
    function auth_id_all($id){
        $ids=[];
        if(is_int($id)){
            $id=[$id];
        }
        if(is_array($id)){
            $id=implode(",",$id);
        }
        $child_ids=Db::name("auth_rule")->whereIn("parent_id",$id)->field("id")->column("id");
        if(!empty($child_ids)){
            $ids=$this->auth_id_all($child_ids);
        }
        $ids=array_merge($ids,$child_ids);
        return $ids;
    }
    function group_list(string $keyword='',int $status=0){
        $where=" 1 ";
        if($keyword){
            $where.=" and title like '%".$keyword."%'";
        }
        if($status){
            $where.=" and status=".($status-1);
        }
        $list=Db::name("auth_group")->where($where)->select()->toArray();
        return $list;
    }

    function get_menu(){
        $rules=Db::name("user")
            ->alias("a")
            ->leftJoin("auth_group_access b","a.id=b.uid")
            ->leftJoin("auth_group c","b.group_id=c.id")
            ->where("a.id=".session('uid'))
            ->value("c.rules");
        $auths=Db::name("auth_rule")
            ->whereIn("id",$rules)
            ->where("type<3")->select()->toArray();
        return $this->auth_menu($auths,0);
    }
    /**
     * 菜单用
     * @param $auths
     * @param $parent_id
     * @param $level
     * @return array
     */
    function auth_menu($auths,$parent_id){
        $tree_list=[];
        if(!empty($auths)){
            foreach($auths as $key=>$auth){
                if($auth['type']>2){continue;}
                if($auth['parent_id']==$parent_id){
                    if($parent_id==0){
                        $url="#";
                    }else{
                        $url="/".str_replace("-","/",$auth['name']);
                    }
                    $tree_list[]= [
                        "id"=>$auth["id"],
                        "title"=>$auth["title"],
                        "parent_id"=>$auth["parent_id"],
                        "icon"=>$auth["icon"],
                        "url"=>$url,
                        "nodes"=>$this->auth_menu($auths,$auth['id']),
                    ];
                }
            }
        }
        return $tree_list;
    }

}