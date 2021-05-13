<?php
// 应用公共文件
function verify_phone($value){
    if(!preg_match('/^09\d{8}$/',$value)&&!preg_match('/^(008529|008526)\d{7}$/',$value)&&!preg_match('/^(0|86|17951)?(13[0-9]|14[0-9]|15[0-9]|16[0-9]|17[0-9]|18[0-9]|19[0-9])[0-9]{8}$/',$value)){
        return false;
    }else{
        return true;
    }
}