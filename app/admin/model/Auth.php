<?php
namespace app\admin\model;
use think\Model;
use think\facade\Db;
class Auth extends Model{
    protected $table = "auth_rule";
    function auth_list($type){
        $auths=Db::name("auth_rule")->where(["status"=>1])->select()->toArray();
        $tree_list=$this->auth_tree($auths,0,0);
        if($type=="obj"){
            return $this->auth_tree_option($tree_list,0);
        }elseif($type=="option"){
            return $this->auth_tree_option_html($tree_list,0);
        }else{
            return $tree_list;
        }
    }
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
                        "nodes"=>$this->auth_tree($auths,$auth['id'],$level),
                    ];
                }
            }
        }
        return $tree_list;
    }
    function auth_tree_option($tree_list,$parent_id){
        $option_obj=[];
        $level_html="";
        if(!empty($tree_list)){
            foreach($tree_list as $key=>$auth){
                if($auth['parent_id']==$parent_id){
                    if($auth['level']>1){
                        $level_html="|";
                        for($i=0;$i<$auth['level'];$i++){
                            $level_html.="-";
                        }
                    }
                    $option_obj[]=[
                        "id"=>$auth["id"],
                        "text"=>$level_html.$auth["text"],
                    ];
                    if(!empty($auth['nodes'])){
                        $child_obj=$this->auth_tree_option($auth['nodes'],$auth['id']);
                        $option_obj = array_merge($option_obj, $child_obj);
                    }
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
                    if($auth['level']>1){
                        $level_html="|";
                        for($i=0;$i<$auth['level'];$i++){
                            $level_html.="-";
                        }
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
}