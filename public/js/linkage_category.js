
/*
设置
topcate_id 上架分类
city_id 市下拉表单id
county_id 区下拉表单id
areaid 地区id
*/
function inicategory(topcate_id,soncate_id,parentid,sonid){
    var parentid=parentid || 0;
    var sonid = sonid || 0;
    var topcategory=parentid;
    var soncategory=sonid;
 
    if(typeof topcate_id!='undefined'){
        getcategory('topcate',topcate_id,parentid,sonid);
    }
    // alert(soncate_id);
    // alert(topcate_id);
    if(typeof soncate_id!='undefined' || topcate_id!=''){
        if(topcategory!='' || soncategory!=''){
            
            getcategory('soncate',soncate_id,parentid,sonid);
        }else{
            $('#'+soncate_id).html("<option value=''>请选择分类</option>");
        }
    }


    
    //绑定事件
    $('#'+topcate_id).change(function() {

        var value_province = $("#"+topcate_id).val();
       
        getcategory('soncate',soncate_id,value_province);
    });
    // $('#'+soncate_id).change(function() {
    //     var value_city = $("#"+soncate_id).val();
    //     getcategory('soncate',soncate_id,'',value_city);
    // });
}



function getcategory(type,id,parentid,sonid){

    var parentid=parentid||0;
    var sonid=sonid||0;
    var areaurl = "/Setup/ProCategory/";
    if(type=='topcate')
        areaurl+="getCategory";
    else if(type=='soncate')
        areaurl+="getsSonCategory";
    areaurl+="?categoryid="+parentid+"&sonid="+sonid+"&"+Math.random();
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


/*
设置
topcate_id 上架分类
city_id 市下拉表单id
county_id 区下拉表单id
areaid 地区id
*/
function inicategoryno(topcate_id,soncate_id,parentid,sonid){
    var parentid=parentid || 0;
    var sonid = sonid || 0;
    var topcategory=parentid;
    var soncategory=sonid;
 
    if(typeof topcate_id!='undefined'){
        getcategoryno('topcate',topcate_id,parentid,sonid);
    }
    // alert(soncate_id);
    // alert(topcate_id);
    if(typeof soncate_id!='undefined' || topcate_id!=''){
        if(topcategory!='' || soncategory!=''){
            
            getcategoryno('soncate',soncate_id,parentid,sonid);
        }else{
            $('#'+soncate_id).html("<option value=''>请选择分类</option>");
        }
    }


    
    //绑定事件
    $('#'+topcate_id).change(function() {

        var value_province = $("#"+topcate_id).val();
       
        getcategory('soncate',soncate_id,value_province);
    });
    // $('#'+soncate_id).change(function() {
    //     var value_city = $("#"+soncate_id).val();
    //     getcategory('soncate',soncate_id,'',value_city);
    // });
}

function getcategoryno(type,id,parentid,sonid){

    var parentid=parentid||0;
    var sonid=sonid||0;
    var areaurl = "/Setup/ProCategory/";
    if(type=='topcate')
        areaurl+="getCategoryno";
    else if(type=='soncate')
        areaurl+="getsSonCategory";
    areaurl+="?categoryid="+parentid+"&sonid="+sonid+"&"+Math.random();
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