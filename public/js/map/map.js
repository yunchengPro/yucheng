var mapObj;
var marker = new Array();
var windowsArr = new Array();
//初始化地图
//address：详细地址，初始化定位。
function mapInit(address) {
    mapObj = new AMap.Map("container");
    //在地图中添加ToolBar插件  
    mapObj.plugin(["AMap.ToolBar"], function () {
        toolBar = new AMap.ToolBar();
        mapObj.addControl(toolBar);
    });
    AMap.event.addListener(mapObj, 'click', addMarker); //点击事件
    if (address) {
        geocoderByAddress(address);
    }
}
//根据地址查询
function geocoderByAddress(address) {
    
    var MGeocoder;
    //加载地理编码插件
    AMap.service(["AMap.Geocoder"], function () {
        MGeocoder = new AMap.Geocoder({
            //city: "010", //城市，默认：“全国”
            radius: 500 //范围，默认：500
        });
        //返回地理编码结果  
        //地理编码
        MGeocoder.getLocation(address, function (status, result) {
            if (status === 'complete' && result.info === 'OK') {
                geocoderByAddress_CallBack(result);
            }
        });
    });
}
//根据坐标查询地址
function geocoderByCoordinates(lnglatXY) {
    var MGeocoder;
    //加载地理编码插件
    AMap.service(["AMap.Geocoder"], function () {
        MGeocoder = new AMap.Geocoder({
            radius: 500,
            extensions: "all"
        });
        //逆地理编码
        MGeocoder.getAddress(lnglatXY, function (status, result) {
            
            if (status === 'complete' && result.info === 'OK') {
                geocoderByCoordinates_CallBack(result,lnglatXY);
            } else if (status == "no_data") {
                art.dialog.tips("无该地区信息!");
            }
        });
    });
}
//输入查询
function keydown(event) {
    
    var key = (event || window.event).keyCode;
    var result = document.getElementById("txtAddress");
    var cur = result.curSelect;
    if (key === 40) {//down
        if (cur + 1 < result.childNodes.length) {
            if (result.childNodes[cur]) {
                result.childNodes[cur].style.background = '';
            }
            result.curSelect = cur + 1;
            result.childNodes[cur + 1].style.background = '#CAE1FF';
            document.getElementById("keyword").value = result.tipArr[cur + 1].name;
        }
    } else if (key === 38) {//up
        if (cur - 1 >= 0) {
            if (result.childNodes[cur]) {
                result.childNodes[cur].style.background = '';
            }
            result.curSelect = cur - 1;
            result.childNodes[cur - 1].style.background = '#CAE1FF';
            document.getElementById("keyword").value = result.tipArr[cur - 1].name;
        }
    } else if (key === 13) {
        var res = document.getElementById("result1");
        if (res && res['curSelect'] !== -1) {
            selectResult(document.getElementById("result1").curSelect);
        }
    } else {
        autoSearch();
    }
}
//输入提示
function autoSearch() {
    var keywords = document.getElementById("txtAddress").value;
    var auto;
    var m_selectCity = $("#City").find("option:selected").text();
    var m_citycode = "";
    if (m_selectCity != "请选择城市") {
        m_citycode = m_selectCity;
    }
    //加载输入提示插件
    AMap.service(["AMap.Autocomplete"], function () {
        var autoOptions = {
            city: m_citycode //城市，默认全国
        };
        auto = new AMap.Autocomplete(autoOptions);
        //查询成功时返回查询结果
        if (keywords.length > 0) {
            auto.search(keywords, function (status, result) {
                autocomplete_CallBack(result);
            });
        }
        else {
            document.getElementById("result1").style.display = "none";
        }
    });
}
//输出输入提示结果的回调函数
function autocomplete_CallBack(data) {
    
    var resultStr = "";
    var tipArr = data.tips;
    if (tipArr && tipArr.length > 0) {
        for (var i = 0; i < tipArr.length; i++) {
            resultStr += "<div id='divid" + (i + 1) + "' onmouseover='openMarkerTipById(" + (i + 1)
                        + ",this)' onclick='selectResult(" + i + ")' onmouseout='onmouseout_MarkerStyle(" + (i + 1)
                        + ",this)' style=\"font-size: 13px;cursor:pointer;padding:5px 5px 5px 5px;\"" + "data=" + tipArr[i].adcode + " data-address=" + tipArr[i].district + ">" + tipArr[i].name + "<span style='color:#C1C1C1;'>" + tipArr[i].district + "</span></div>";
        }
    }
    else {
        resultStr = " π__π 亲,人家找不到结果!<br />要不试试：<br />1.请确保所有字词拼写正确<br />2.尝试不同的关键字<br />3.尝试更宽泛的关键字";
    }
    document.getElementById("result1").curSelect = -1;
    document.getElementById("result1").tipArr = tipArr;
    document.getElementById("result1").innerHTML = resultStr;
    document.getElementById("result1").style.display = "block";
}
//输入提示框鼠标滑过时的样式
function openMarkerTipById(pointid, thiss) {  //根据id打开搜索结果点tip 
    thiss.style.background = '#CAE1FF';
}
//输入提示框鼠标移出时的样式
function onmouseout_MarkerStyle(pointid, thiss) {  //鼠标移开后点样式恢复 
    thiss.style.background = "";
}
//点击选择搜索结果
function selectResult(index) {
    if (index < 0) {
        return;
    }
    if (navigator.userAgent.indexOf("MSIE") > 0) {
        document.getElementById("txtAddress").onpropertychange = null;
        document.getElementById("txtAddress").onfocus = focus_callback;
    }
    
    //截取输入提示的关键字部分
    var text = document.getElementById("divid" + (index + 1)).innerHTML.replace(/<[^>].*?>.*<\/[^>].*?>/g, "");
    var cityCode = document.getElementById("divid" + (index + 1)).getAttribute('data');
    var address = document.getElementById("divid" + (index + 1)).getAttribute('data-address');

    document.getElementById("txtAddress").value = text;
    document.getElementById("result1").style.display = "none";
    //根据选择的输入提示关键字查询
    mapObj.plugin(["AMap.PlaceSearch"], function () {
        var msearch = new AMap.PlaceSearch();  //构造地点查询类
        AMap.event.addListener(msearch, "complete", placeSearch_CallBack); //查询成功时的回调函数
        msearch.setCity(cityCode);
        msearch.search(text);  //关键字查询查询
    });

    var m_Province = $("#Province").find("option:selected").text();
    var m_City = $("#City").find("option:selected").text();
   // if (m_Province != "请选择省份" && m_City != "请选择城市") {
        //$("#District option").each(function (i, op) {
        //    
        //    var _thisText = $(op).text();
        //    var m_detailAddress = m_Province + m_City + _thisText;
        //    if (m_detailAddress == address) {
        //        $(op).attr("selected", true);
        //    } else {
        //        $(op).attr("selected", false);
        //    }
        //});
    //} else {
        $("#Province option").each(function () {
            var m_ProvinceName = $(this).text();
            if (address.indexOf(m_ProvinceName) > -1) {
                $("#Province").val($(this).val());
                GetAreaData("City", $(this).val(), address, "请选择城市");
                var m_CitySelectValue = $("#City").find("option:selected").val();
                //if (m_CitySelectValue && m_CitySelectValue > 0) {
                GetAreaData("District", m_CitySelectValue, address, "请选择县市");
                //}
            }
        });
   // }
    //District

}
//定位选择输入提示关键字
function focus_callback() {
    if (navigator.userAgent.indexOf("MSIE") > 0) {
        document.getElementById("txtAddress").onpropertychange = autoSearch;
    }
}
//点击添加标记
function addMarker(e) {
    
    mapObj.clearMap();
    var lngX = e.lnglat.lng;
    var latY = e.lnglat.lat;

    document.getElementById("lngX").value = lngX;
    document.getElementById("latY").value = latY;
    CommonMarker(0, lngX, latY, "");
    //已知点坐标
    var lnglatXY = new AMap.LngLat(lngX, latY);
    geocoderByCoordinates(lnglatXY);
}
//添加标记
function addmarker(i, d) {
    var lngX = d.location.getLng();
    var latY = d.location.getLat();
    CommonMarker(i, lngX, latY, d.formattedAddress);
}
//标注
function CommonMarker(i, lngX, latY, contentAddress) {
    var markerOption = {
        map: mapObj,
        icon: "http://webapi.amap.com/images/" + (i + 1) + ".png",
        position: new AMap.LngLat(lngX, latY)
    };
    var mar = new AMap.Marker(markerOption);
    marker.push(new AMap.LngLat(lngX, latY));

    var infoWindow = new AMap.InfoWindow({
        content: contentAddress,
        autoMove: true,
        size: new AMap.Size(150, 0),
        offset: { x: 0, y: -30 }
    });
    windowsArr.push(infoWindow);

    var aa = function (e) { infoWindow.open(mapObj, mar.getPosition()); };
    AMap.event.addListener(mar, "click", aa);
}
//地理编码返回结果展示   
function geocoderByAddress_CallBack(data) {
    
    var resultStr = "";
    //地理编码结果数组
    var geocode = new Array();
    geocode = data.geocodes;
    //for (var i = 0; i < geocode.length; i++) {
        //拼接输出html
        //resultStr += "<span style=\"font-size: 12px;padding:0px 0 4px 2px; border-bottom:1px solid #C1FFC1;\">" + "<b>地址</b>：" + geocode[i].formattedAddress + "" + "<b>&nbsp;&nbsp;&nbsp;&nbsp;坐标</b>：" + geocode[i].location.getLng() + ", " + geocode[i].location.getLat() + "" + "<b>&nbsp;&nbsp;&nbsp;&nbsp;匹配级别</b>：" + geocode[i].level + "</span>";
    if (geocode.length > 0) {
        addmarker(0, geocode[0]);
    }
    
    //}
    mapObj.setFitView();
    //document.getElementById("result").innerHTML = resultStr;
}
//点击返回结果展示
function geocoderByCoordinates_CallBack(data,lnglatXY) {

	var m_province = data.regeocode.addressComponent.province;//省份
    var m_city = data.regeocode.addressComponent.city;//城市
    var m_district = data.regeocode.addressComponent.district;//县市级
    var township = data.regeocode.addressComponent.township;//乡
    var street = data.regeocode.addressComponent.street;//街道
    var streetNumber = data.regeocode.addressComponent.streetNumber;//街道号
    var m_address = township + street + streetNumber;
    if (streetNumber && streetNumber.indexOf("号") == -1) {
        m_address += "号";
    }
    $("#area_province option").each(function () {
        var m_ProvinceName = $(this).text();
        if (m_ProvinceName == m_province) {

            $("#area_province").val($(this).val());

            getarea('city',"area_city",m_city,$(this).val());
            //var m_CitySelectValue = $("#area_city").find("option:selected").val();
            getcityvalue (m_city,$(this).val(),m_district);


            //GetAreaData("City", $(this).val(), m_city, "请选择城市");
            //var m_CitySelectValue = $("#City").find("option:selected").val();
            //if (m_CitySelectValue && m_CitySelectValue > 0) {
            //GetAreaData("District", m_CitySelectValue, m_district, "请选择县市");
            //}
        }
    });
    $("#txtAddress").val(m_address);
}

function getcityvalue(areaid,parentid,m_district){
    var areaurl = "/setup/area/";

        areaurl+="getcityvalue";
    areaurl+="?areaid="+areaid+"&f_parentcode="+parentid+"&"+Math.random();
    $.ajax({
        type:"GET",
        url:areaurl,
        success:function(data){
            
            getarea('county',"area_county",m_district,data);
        }
    });
}
//输出关键字查询结果的回调函数
function placeSearch_CallBack(data) {
    
    //清空地图上的InfoWindow和Marker
    mapObj.clearMap();
    var resultStr1 = "";
    var poiArr = data.poiList.pois;
    var resultCount = poiArr.length;
    for (var i = 0; i < resultCount; i++) {
        //addmarker(i, poiArr[i]);
        var lngX = poiArr[i].location.getLng();
        var latY = poiArr[i].location.getLat();
        CommonMarker(i, lngX, latY, TipContents(i, poiArr[i].name,poiArr[i].type, poiArr[i].address, poiArr[i].tel));
    }
    mapObj.setFitView();
    document.getElementById("result").innerHTML = resultStr1;
    document.getElementById("result").style.display = "block";
}
//infowindow显示内容
function TipContents(i, name, type, address, tel) {  //窗体内容
    if (!name) {
        name = "暂无";
    }
    if (!type) {
        type = "暂无";
    }
    if (!address) {
        address = "暂无";
    }
    if (!tel) {
        tel = "暂无";
    }
    var str = "<h3><font color=\"#00a6ac\">  " + (i + 1) + ". " + name + "</font></h3>  地址：" + address + "<br />  电话：" + tel + " <br />  类型：" + type;
    return str;
}
//隐藏搜索结果
function hideSearchTip() {
    document.getElementById("result1").style.display = "none";
}
//获取区域数据
function GetAreaData(id, parentId, selectedVal, tip) {
    var url = "/Admin/Common/GetAreaByParentID";
    var t = "<option value=\"\">" + tip + "</option>";
    if (parentId > 0) {
        var query = { ParentID: parentId };
        $.U1Ajax(url, query, function (data) {
            var s = "";
            if (data) {
                for (var i = 0; i < data.length; i++) {
                    s = "";
                    if (selectedVal.indexOf(data[i].AreaName) >-1) {
                        s = ' selected=\"selected\"';
                    }
                    t += '<option value=' + data[i].ID + s + '>' + data[i].AreaName + '</option>';
                }
            }
        }, false);
    }
    $("#" + id).html(t);
}
