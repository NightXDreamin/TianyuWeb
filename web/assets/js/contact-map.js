// assets/js/contact-map.js

function initMap() {
    const mapContainer = document.getElementById('map-container');

    if (!mapContainer) {
        // 如果页面上没有地图容器，就直接返回，防止报错
        return;
    }

    try {
        var infoWindow, map, level = 16,
            center = { lng: 113.612466, lat: 34.735581 },
            features = [{
                "icon": "cir",
                "color": "red",
                "name": "河南天昱环保工程有限公司",
                "desc": "河南省郑州市中原区桐柏路与陇海路交叉口凯旋门大厦1406",
                "lnglat": { "Q": 34.73558112897725, "R": 113.61246635645625, "lng": 113.612466, "lat": 34.735581 },
                "offset": { "x": -9, "y": -31 },
                "type": "Marker"
            }];

        function loadFeatures() {
            for (var feature, data, i = 0, len = features.length; i < len; i++) {
                data = features[i];
                if (data.type === "Marker") {
                    feature = new AMap.Marker({
                        map: map,
                        position: new AMap.LngLat(data.lnglat.lng, data.lnglat.lat),
                        zIndex: 3,
                        extData: data,
                        offset: new AMap.Pixel(data.offset.x, data.offset.y),
                        title: data.name,
                        content: '<div class="icon icon-' + data.icon + ' icon-' + data.icon + '-' + data.color + '"></div>'
                    });
                    if (feature) {
                        AMap.event.addListener(feature, "click", mapFeatureClick);
                    }
                }
            }
        }

        function mapFeatureClick(e) {
            if (!infoWindow) {
                infoWindow = new AMap.InfoWindow({ autoMove: true, isCustom: false });
            }
            var extData = e.target.getExtData();
            infoWindow.setContent("<div class='myinfowindow'><h5>" + extData.name + "</h5><div>" + extData.desc + "</div></div>");
            infoWindow.open(map, e.lnglat);
        }

        // 关键改动：确保地图在正确的容器ID "map-container" 中初始化
        map = new AMap.Map("map-container", {
            center: new AMap.LngLat(center.lng, center.lat),
            zoom: level, // 使用了变量 level，而不是固定的数字
            keyboardEnable: true,
            dragEnable: true,
            scrollWheel: true,
            doubleClickZoom: true
        });

        loadFeatures();

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

// 确保在DOM加载完成后再执行初始化
document.addEventListener('DOMContentLoaded', initMap);