/**
 * 弹窗提醒
 * @param msg
 * @param type success,error.info,warning
 * @param position http://yaoadmin.com/demo/src/assets/js/example-toastr.js?ver=2.2.0
 */
function toast(msg,type="success",position) {
    toastr.clear();
    if(!position){
        position={
            position: "top-center"
        }
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