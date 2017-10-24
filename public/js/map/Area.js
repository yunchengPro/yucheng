/*
add by zhengjl 2013-09-06
省市区三级联动
*/
var AreaSelectInit = function () {

    var areaJson = "http://10.70.40.246:8088/Admin/Common/GetAreaByParentID";
    var initProvince = "<option value=''>请选择省份</option>";
    var initCity = "<option value=''>请选择城市</option>";
    var initDistrict = "<option value=''>请选择区县</option>";
    return {
        Init: function (hasDistrict, provinceid, cityid, districtid) {
            //数据初始化
            var that = this,
                province = (provinceid ? $("#" + provinceid) : provinceid),
                city = (cityid ? $("#" + cityid) : null),
                district = (districtid ? $("#" + districtid) : null),
                preProvince = (provinceid ? $("#p" + provinceid) : provinceid),
                preCity = (cityid ? $("#c" + cityid) : null),
                preDistrict = (districtid ? $("#d" + districtid) : null);
            this.LoadOption(areaJson, preProvince, province, 1, initProvince);
            //that._LoadOptions(areaJson, preProvince, province, null, 0, initProvince);

            province.change(function () {              
                var parentId = "?parentId=" + $(province).val();
                if ($(province).val()) {
                    that.LoadOption(areaJson + parentId, preCity, city, 2, initCity);
                } else {
                    $(city).html(initCity);
                }
                if (hasDistrict) {
                    //$(district).html(initDistrict);
                    //that.LoadOption(areaJson, preDistrict, district, city, 4, initDistrict);
                    //that.LoadOption(areaJson + parentId, preDistrict, district, 3, initDistrict);

                }
            });

            if (hasDistrict) {
                city.change(function () {                  
                    var parentId = "?parentId=" + $(city).val();
                    if ($(city).val()) {
                        that.LoadOption(areaJson + parentId, preDistrict, district, 3, initDistrict);
                    } else {
                        $(district).html(initDistrict);
                    }
                });
                province.change(function () {
                    city.change();                                   
                });
            }
            if (preProvince.val() != "") {
                setTimeout(function () {
                    province.change();
                }, 500);
            }
        },
        /*       
            /*$.get(
              datapath,
              function (r) {
                  var t = ""; //t:html容器
                  var s;      //s:选中标识
                  var pre;    //pre:初始值
                  if (preobj === undefined || preobj === null) {
                      pre = 0;
                  }
                  else {
                      pre = preobj.val();
                  }
                  for (var i = 0; i < r.length; i++) {
                      s = "";
                      if (comparelen === 0) {
                          if (pre != "" && pre != 0 && r[i].Postcode == pre) {
                              s = ' selected=\"selected\"';
                              pre = '';
                          }
                          t += '<option value=' + r[i].Postcode + s + '>' + r[i].AreaName + '</option>';
                      }
                      else {
                          var p = parentobj.val();
                          if (p.substring(0, comparelen) === r[i].Postcode.substring(0, comparelen)) {
                              if (pre !== "" && pre !== 0 && r[i].Postcode === pre) {
                                  s = ' selected=\"selected\" ';
                                  pre = '';
                              }
                              t += '<option value=' + r[i].Postcode + s + '>' + r[i].AreaName + '</option>';
                          }
                      }
                  }
                  if (initoption !== '') {
                      targetobj.html(initoption + t);
                  } else {
                      targetobj.html(t);
                  }
              },
              "json"
          );
        }*/

        LoadOption: function (dataPath, preObj, targetobj, num, initOption) {
            $.U1Ajax(dataPath, null, function (r) {
                var t = ""; //t:html容器
                var s = "";      //s:选中标识
                var pre;    //pre:初始值
                if (preObj === undefined || preObj === null) {
                    pre = "";
                }
                else {
                    pre = preObj.val();
                }
                for (var i = 0; i < r.length; i++) {
                    if (pre && r[i].ID == pre) {
                        s = ' selected=\"selected\"';
                        pre = '';
                    }
                    t += '<option value=' + r[i].ID + s + '>' + r[i].AreaName + '</option>';
                    s = "";
                }

                targetobj.html(initOption + t);

            }, false);
        }
    };
}();

