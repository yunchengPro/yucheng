function ajaxSelectCity(){
    //var areacode = $("#f_province").val();
    var id = $("select[name=f_province]").val();
    $.ajax({
        type:"GET",

        url:"/branch/index/city/id/"+id+"/"+Math.random(),

        success:function(rdata){
            if(rdata!=''){
                $('select[name=f_city]').html(rdata);
                $('select[name=f_city]').show();
            }
        }
    });
}

function ajaxSelectArea(){
    var id = $("select[name=f_city]").val();
    $.ajax({
        type:"GET",

        url:"/branch/index/district/id/"+id+"/"+Math.random(),

        success:function(rdata){
            if(rdata!=''){
                $('select[name=f_district]').html(rdata);
                $('select[name=f_district]').show();
            }
        }
    });
}

/*
设置
province_id 省下拉表单id
city_id 市下拉表单id
county_id 区下拉表单id
areaid 地区id
*/
function iniarea(province_id,city_id,county_id,areaid){

    var province='';
    var city='';
    var county='';
    var area = areaid.toString(); //转字符串
    if(typeof area!='undefined' && area!=''){
        //省
        if(area.substr(0,2)!='00')
            province=area.substr(0,2)+"0000";
        //市
        if(area.substr(2,2)!='00')
            city=area.substr(0,4)+"00";
        //区
        if(area.substr(4,2)!='00')
            county=area;
    }
    if(typeof province_id!='undefined'){
        getarea('province',province_id,province);
    }

    if(typeof city_id!='undefined' || province!=''){
        if(city!='' || province!=''){
            getarea('city',city_id,city,province);
        }else{
            $('#'+city_id).html("<option value=''>选择城市</option>");
        }
    }

    if(typeof county_id!='undefined' || city!=''){
        if(county!='' || city!=''){
            getarea('county',county_id,county,city);
        }else{
            $('#'+county_id).html("<option value=''>选择区县</option>");
        }
    }
    
    //绑定事件
    $('#'+province_id).change(function() {

        var value_province = $("#"+province_id).val();
        getarea('city',city_id,'',value_province);
    });
    $('#'+city_id).change(function() {
        var value_city = $("#"+city_id).val();
        getarea('county',county_id,'',value_city);
    });
}

function getarea(type,id,areaid,parentid){
    var areaurl = "/setup/area/";
    if(type=='province')
        areaurl+="getprovince";
    else if(type=='city')
        areaurl+="getcity";
    else if(type=='county')
        areaurl+="getcounty";
    areaurl+="?areaid="+areaid+"&parentid="+parentid+"&"+Math.random();
    $.ajax({
        type:"GET",
        url:areaurl,
        success:function(data){
            if(data!=''){
                $('#'+id).html(data);
            }
        }
    });
}