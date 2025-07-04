// assets/js/contact-map.js

function initMap() {
    // --- 地图API配置 ---
    // 在这里粘贴你从地图服务商获取的API密钥和初始化代码
    // 例如：高德地图、百度地图、腾讯地图等

    const mapContainer = document.getElementById('map-container');
    
    if (mapContainer) {
        // 这是一个示例，告诉你代码应该放在哪里
        // 真实代码需要替换成你选择的地图API的用法
        console.log("地图容器已准备好，请在此处初始化地图。");

        <!DOCTYPE html>
 <html>
 <head>
   <meta charset="utf-8">
   <meta name="keywords" content="高德地图,DIY地图,高德地图生成器">
   <meta name="description" content="高德地图，DIY地图，自己制作地图，生成自己的高德地图">
   <title>高德地图 - DIY我的地图</title>
   <style>
     body { margin: 0; font: 13px/1.5 "Microsoft YaHei", "Helvetica Neue", "Sans-Serif"; min-height: 960px; min-width: 600px; }
     .my-map { margin: 0 auto; width: 800px; height: 640px; } .my-map .icon { background: url(//a.amap.com/lbs-dev-yuntu/static/web/image/tools/creater/marker.png) no-repeat; } .my-map .icon-cir { height: 31px; width: 28px; } .my-map .icon-cir-red { background-position: -11px -5px; }
     .amap-container{height: 100%;}
     .myinfowindow{width: 240px;min-height: 50px;}
     .myinfowindow h5{ height: 20px; line-height: 20px; overflow: hidden; font-size: 14px; font-weight: bold; width: 220px; text-overflow: ellipsis; word-break: break-all; white-space: nowrap; }
     .myinfowindow div{ margin-top: 10px; min-height: 40px; line-height: 20px; font-size: 13px; color: #6f6f6f; }
   </style>
 </head>
 <body>
   <div id="wrap" class="my-map">
     <div id="mapContainer"></div>
   </div>
   <script src="//webapi.amap.com/maps?v=1.3&key=e07ffdf58c8e8672037bef0d6cae7d4a"></script>
   <script>
   !function(){
     var infoWindow, map, level = 16,
       center = {lng: 113.612466, lat: 34.735581},
       features = [{"icon":"cir","color":"red","name":"河南天昱环保工程有限公司","desc":"河南省郑州市中原区桐柏路与陇海路交叉口凯旋门大厦1406","lnglat":{"Q":34.73558112897725,"R":113.61246635645625,"lng":113.612466,"lat":34.735581},"offset":{"x":-9,"y":-31},"type":"Marker"}];
 
     function loadFeatures(){
       for(var feature, data, i = 0, len = features.length, j, jl, path; i < len; i++){
         data = features[i];
         switch(data.type){
           case "Marker":
             feature = new AMap.Marker({ map: map, position: new AMap.LngLat(data.lnglat.lng, data.lnglat.lat),
               zIndex: 3, extData: data, offset: new AMap.Pixel(data.offset.x, data.offset.y), title: data.name,
               content: '<div class="icon icon-' + data.icon + ' icon-'+ data.icon +'-' + data.color +'"></div>' });
             break;
           case "Polyline":
            for(j = 0, jl = data.path.length, path = []; j < jl; j++){
							path.push(new AMap.LngLat(data.path[j].lng, data.path[j].lat));
						}
             feature = new AMap.Polyline({ map: map, path: path, extData: data, zIndex: 2,
               strokeWeight: data.strokeWeight, strokeColor: data.strokeColor, strokeOpacity: data.strokeOpacity });
             break;
           case "Polygon":
             for(j = 0, jl = data.path.length, path = []; j < jl; j++){
               path.push(new AMap.LngLat(data.path[j].lng, data.path[j].lat));
             }
             feature = new AMap.Polygon({ map: map, path: path, extData: data, zIndex: 1,
               strokeWeight: data.strokeWeight, strokeColor: data.strokeColor, strokeOpacity: data.strokeOpacity,
               fillColor: data.fillColor, fillOpacity: data.fillOpacity });
             break;
           default: feature = null;
         }
         if(feature){ AMap.event.addListener(feature, "click", mapFeatureClick); }
       }
     }
 
     function mapFeatureClick(e){
       if(!infoWindow){ infoWindow = new AMap.InfoWindow({autoMove: true,isCustom: false}); }
       var extData = e.target.getExtData();
       infoWindow.setContent("<div class='myinfowindow'><h5>" + extData.name + "</h5><div>" + extData.desc + "</div></div>");
       infoWindow.open(map, e.lnglat);
     }
 
     map = new AMap.Map("mapContainer", {center: new AMap.LngLat(center.lng, center.lat), level: level, keyboardEnable:true, dragEnable:true, scrollWheel:true, doubleClickZoom:true});
     
     loadFeatures();
 
     map.on('complete', function(){
       map.plugin(["AMap.ToolBar", "AMap.OverView", "AMap.Scale"], function(){
         map.addControl(new AMap.ToolBar({ruler: true, direction: true, locate: false})); map.addControl(new AMap.OverView({isOpen: true})); map.addControl(new AMap.Scale);
       });	
     })
     
 	}();
   </script>
 </body>
 </html>
}

// 等待页面加载完毕后执行地图初始化
// 注意：如果你的地图API需要一个全局的回调函数，你可能需要调整这里的调用方式
document.addEventListener('DOMContentLoaded', initMap);