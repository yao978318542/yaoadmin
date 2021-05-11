/**
 * 弹窗提醒
 * @param msg
 * @param type success,error.info,warning
 * @param position http://yaoadmin.com/demo/src/assets/js/example-toastr.js?ver=2.2.0
 */
function toast(msg,type="success",position,callback) {
    toastr.clear();
    if(!position){
        position={
            position: "top-center"
        }
    }
    if(callback){
        position.onHidden=callback;
    }
    NioApp.Toast(msg, type, position);
}

/**
 * 加载按钮loding
 * @param id
 */
function load_add(id) {
    $("#"+id).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>\n' +
        '  <span class="sr-only">Loading...</span>').attr("disabled",true);
}
function load_end(id) {
    $("#"+id).html('提交').attr("disabled",false);
}

/**
 * 表单手机号码验证 大陆 台湾 香港 德国
 */
function verify_phone(value){
    var reg01 =
        /^(0|86|17951)?(13[0-9]|14[0-9]|15[0-9]|16[0-9]|17[0-9]|18[0-9]|19[0-9])[0-9]{8}$/;
    var reg02 =
        /^09\d{8}$/;
    var reg03 =
        /^(008529|008526|008525)\d{7}$/;
    var reg04 =
        /^(0049)\d{11}$/;
    if(!reg01.test(value) && !reg02.test(value) && !reg03.test(value) && !reg04.test(value)){
        return false;
    }
    return true;
}
jQuery.validator.addMethod("verify_phone", function(value, element, param) {
    return verify_phone(value);
},"请填写正确的手机号");