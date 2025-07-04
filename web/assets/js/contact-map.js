// assets/js/contact-map.js (修正版)

function initContactMap() {
    const mapContainer = document.getElementById('map-container');

    // 如果当前页面没有地图容器，就直接退出，防止在其他页面报错
    if (!mapContainer) {
        return;
    }

    try {
        const level = 16;
        const center = { lng: 113.612466, lat: 34.735581 };
        const features = [{
            "icon": "cir",
            "color": "red",
            "name": "河南天昱环保工程有限公司",
            "desc": "河南省郑州市中原区桐柏路与陇海路交叉口凯旋门大厦1406",
            "lnglat": { "lng": 113.612466, "lat": 34.735581 },
            "offset": { "x": -9, "y": -31 },
            "type": "Marker"
        }];

        // 初始化地图实例
        const map = new AMap.Map("map-container", {
            center: new AMap.LngLat(center.lng, center.lat),
            zoom: level,
            keyboardEnable: true,
            dragEnable: true,
            scrollWheel: true,
            doubleClickZoom: true
        });

        let infoWindow = null;

        // 点击标记点时触发的事件
        function mapFeatureClick(e) {
            if (!infoWindow) {
                infoWindow = new AMap.InfoWindow({ autoMove: true, isCustom: false });
            }
            const extData = e.target.getExtData();
            infoWindow.setContent(`<div class='myinfowindow'><h5>${extData.name}</h5><div>${extData.desc}</div></div>`);
            infoWindow.open(map, e.lnglat);
        }

        // 加载所有标记点
        features.forEach(data => {
            if (data.type === "Marker") {
                const marker = new AMap.Marker({
                    map: map,
                    position: new AMap.LngLat(data.lnglat.lng, data.lnglat.lat),
                    zIndex: 3,
                    extData: data,
                    offset: new AMap.Pixel(data.offset.x, data.offset.y),
                    title: data.name,
                    content: `<div class="icon icon-${data.icon} icon-${data.icon}-${data.color}"></div>`
                });
                marker.on("click", mapFeatureClick);
            }
        });

        // 地图加载完成后，添加工具条等插件
        map.on('complete', function () {
            map.plugin(["AMap.ToolBar", "AMap.OverView", "AMap.Scale"], function () {
                map.addControl(new AMap.ToolBar({ ruler: true, direction: true, locate: false }));
                map.addControl(new AMap.OverView({ isOpen: true }));
                map.addControl(new AMap.Scale());
            });
        });

    } catch (e) {
        console.error("高德地图加载失败: ", e);
        mapContainer.innerHTML = '<p style="text-align:center;padding-top:50px;">地图加载失败，请检查网络或刷新页面。</p>';
    }
}

// 确保在DOM加载完成后再执行我们的地图初始化函数
// 我们不再需要 document.addEventListener 了，因为 main.js 已经有了
// 直接调用函数即可，但要确保 main.js 是最后加载的
initContactMap();