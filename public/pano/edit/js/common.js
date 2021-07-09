//右键添加
function right_click(){
    //获取当前鼠标坐标
    let x=krpano.get("mouse.x");
    let y=krpano.get("mouse.y");
    let coordinate=krpano.screentosphere(x,y);
    let ath=coordinate.x
    let atv=coordinate.y
    let data={
        "ath":ath,
        "atv":atv,
        "width":60,
        "height":60,
        "type":"image",
        "url":"/pano/edit/images/design.svg",
    }
    add_hotspot("design",data);
}
//添加热点
function add_hotspot(name,data) {
    krpano.call("addhotspot("+name+")");
    let hotspot="hotspot["+name+"]";
    $.each(data,function (i,item) {
        if(i!="point"){
            krpano.set(hotspot+"."+i,item);
        }else{
            for(let j=0;j<item.length;j++){
                krpano.set(hotspot+".point["+j+"].ath",item[j].ath);
                krpano.set(hotspot+".point["+j+"].atv",item[j].atv);
            }
        }
    });
}