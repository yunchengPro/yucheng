$(function(){
       
    
    /* AJAX添加规格值 */
    // 添加规格
    $('a[nctype="specAdd"]').click(function(){
        var _parent = $(this).parents('li:first');
        _parent.find('div[nctype="specAdd1"]').hide();
        _parent.find('div[nctype="specAdd2"]').show();
        _parent.find('input').focus();
    });
    // 取消
    $('a[nctype="specAddCancel"]').click(function(){
        var _parent = $(this).parents('li:first');
        _parent.find('div[nctype="specAdd1"]').show();
        _parent.find('div[nctype="specAdd2"]').hide();
        _parent.find('input').val('');
    });
    // 提交
    $('a[nctype="specAddSubmit"]').click(function(){
        var _parent = $(this).parents('li:first');
        //eval('var data_str = ' + _parent.attr('data-param'));
        var spec_id=_parent.attr('data-speid');
        var spec_name=_parent.attr('data-spename');
        var url=_parent.attr('data-url');
        //console.log(data_str);
        var _input = _parent.find('input');
        var skuname=_input.val();
        //console.log(skuname);
        if($.trim(skuname)=="")
        {
            alert("不能添加空的规格值");
            return false;
        }
        $.getJSON(
			"/product/index/ajaxAddSepcValue", 
			{
				spec_id : spec_id,
                spec_name:spec_name,
				name : _input.val(),
			}, function(data){
            if (data.done) {
                _parent.before('<li><span nctype="input_checkbox"><input style="30px;display:inline" type="checkbox" name="sp_val[' + spec_id + '][' + data.value_id + ']" nc_type="' + data.value_id + '" value="' +_input.val()+ '" /></span><span nctype="pv_name">' + _input.val() + '</span></li>');
                _input.val('');
            }
            _parent.find('div[nctype="specAdd1"]').show();
            _parent.find('div[nctype="specAdd2"]').hide();
        });
    });



    // 修改规格名称
    $('input[nctype="spec_name"]').change(function(){
        eval('var data_str = ' + $(this).attr('data-param'));
        if ($(this).val() == '') {
            $(this).val(data_str.name);
        }
        $('th[nctype="spec_name_' + data_str.id + '"]').html($(this).val());
    });
	
	// 修改规格名称
    $('dl[nctype="spec_group_dl"]').on('click', 'input[type="checkbox"]', function(){
        pv = $(this).parents('li').find('span[nctype="pv_name"]');
        if(typeof(pv.find('input').val()) == 'undefined'){
            pv.html('<input type="text" maxlength="20" class="text" value="'+pv.html()+'" />');
        }else{
            pv.html(pv.find('input').val());
        }
    });
	
	$("#f_brandid").change(function(){
		getBrandName();
	});
	
	$(".spec_value").click(function(){
		
		var checked = $(this).is(':checked');
		
		if(checked == true){
			
		}
		
		
	});
	
	
	// 运费部分显示隐藏
    $('input[nctype="freight"]').click(function(){
            $('input[nctype="freight"]').nextAll('div[nctype="div_freight"]').hide();
            $(this).nextAll('div[nctype="div_freight"]').show();
    });
	
	//格式化SKU价格
	$('.field').on('blur','input[data_type="price"]',function (){
		
		obj = $(this);
		to_num(obj);
		
		
		
	});
	
	//格式化SKU市场价
	$('.field').on('blur','input[data_type="marketprice"]',function (){
		
		obj = $(this);
		to_num(obj);
		
		
		
	});
	
	//格式化SKU成本价
	$('.field').on('blur','input[data_type="costprice"]',function (){
		
		obj = $(this);
		to_num(obj);
		
		
		
	});
	
	//格式化SKU重量
	$('.field').on('blur','input[data_type="weight"]',function (){
		
		obj = $(this);
		to_num(obj,3);
		
		
		
	});
	
	//格式化SKU体积
	$('.field').on('blur','input[data_type="volume"]',function (){
		
		obj = $(this);
		to_num(obj,3);
		
		
	});
	
	
});


function to_num(obj,suffix_num){
	
	if(typeof suffix_num == "undefined"){
		suffix_num = 2;
		suffix_val = "0.00";
	}else{
		suffix_val = "0.000";
	}
	
	var oNum = new Number(obj.val());
	oNum = oNum.toFixed(suffix_num);
	
	if (oNum=='NaN') oNum = suffix_val; 
	
	obj.val(oNum);
	
}




// 计算商品库存
function computeStock(){
    // 库存
    var _stock = 0;
    $('input[data_type="productstorage"]').each(function(){
        if($(this).val() != ''){
            _stock += parseInt($(this).val());
        }
    });
    $('input[name="productstorage"]').val(_stock);
}

// 计算价格
function computePrice(){
    // 计算最低价格
    var _price = 0;var _price_sign = false;
    $('input[data_type="supplyprice"]').each(function(){
        if($(this).val() != '' && $(this)){
            if(!_price_sign){
                _price = parseFloat($(this).val());
                _price_sign = true;
            }else{
                _price = (parseFloat($(this).val())  > _price) ? _price : parseFloat($(this).val());
            }
        }
    });
    $('input[name="supplyprice"]').val(number_format(_price, 2));

    discountCalculator();       // 计算折扣
}

function specdelete(specname)
{
    console.log(specname);
    //eval('var data_str = ' + _parent.attr('data-param'));
    var skuid = document.getElementById(specname).value;//skuid
    console.log(skuid);
    $.ajax({
        type:'POST',
        async:true,
        timeout:10000,
        url:'/product/index/specdelete',
        data:{
            skuid:skuid
        },
        dataType:'json',
        success:function(data)
        {

            window.location.reload();
        },
        error:function(data){
             window.location.reload();
             return false;
        }
    });
}




//计算市场价
function computeMarketPrice(){
	// 计算最低市场价格
    var _marketprice = 0;var _marketprice_sign = false;
	$('input[data_type="bullamount"]').each(function(){
        if($(this).val() != '' && $(this)){
            if(!_marketprice_sign){
                _marketprice = parseFloat($(this).val());
                _marketprice_sign = true;
            }else{
                _marketprice = (parseFloat($(this).val())  > _marketprice) ? _marketprice : parseFloat($(this).val());
            }
        }
    });
	$('input[name="bullamount"]').val(number_format(_marketprice, 2));
	
}


//计算成本价格
function computeCostpricePrice(){
	// 计算最低市场价格
    var _costprice = 0;var _costprice_sign = false;
	$('input[data_type="costprice"]').each(function(){
        if($(this).val() != '' && $(this)){
            if(!_costprice_sign){
                _costprice = parseFloat($(this).val());
                _costprice_sign = true;
            }else{
                _costprice = (parseFloat($(this).val())  > _costprice) ? _costprice : parseFloat($(this).val());
            }
        }
    });
	$('input[name="f_costprice"]').val(number_format(_costprice, 2));
	
}




// 计算折扣
function discountCalculator() {
    var _price = parseFloat($('input[name="g_price"]').val());
    var _marketprice = parseFloat($('input[name="g_marketprice"]').val());
    if((!isNaN(_price) && _price != 0) && (!isNaN(_marketprice) && _marketprice != 0)){
        var _discount = parseInt(_price/_marketprice*100);
        $('input[name="g_discount"]').val(_discount);
    }
}

//获得商品名称
function getBrandName() {
    var brand_name = $('select[name="f_brandid"] > option:selected').html();
    $('input[name="f_brandname"]').val(brand_name);
}
//修改相关的颜色名称
function change_img_name(Obj){
     var S = Obj.parents('li').find('input[type="checkbox"]');
     S.val(Obj.val());
     var V = $('tr[nctype="file_tr_'+S.attr('nc_type')+'"]');
     V.find('span[nctype="pv_name"]').html(Obj.val());
     V.find('input[type="file"]').attr('name', Obj.val());
}
// 商品属性
function attr_selected(){
    $('select[nc_type="attr_select"] option:selected').each(function(){
        id = $(this).attr('nc_type');
        name = $(this).parents('select').attr('attr').replace(/__NC__/g,id);
        $(this).parents('select').attr('name',name);
    });
}
// 验证店铺分类是否重复
function checkSGC($val) {
    var _return = true;
    $('.sgcategory').each(function(){
        if ($val !=0 && $val == $(this).val()) {
            _return = false;
        }
    });
    return _return;
} 
/* 插入商品图片 */
function insert_img(name, src) {
    $('input[nctype="goods_image"]').val(name);
    $('img[nctype="goods_image"]').attr('src',src);
}

/* 插入编辑器 */
function insert_editor(file_path) {
    KE.appendHtml('goods_body', '<img src="'+ file_path + '">');
}

function setArea(area1, area2) {
    $('#province_id').val(area1).change();
    $('#city_id').val(area2);
}


function number_format(num, ext){
    if(ext < 0){
        return num;
    }
    num = Number(num);
    if(isNaN(num)){
        num = 0;
    }
    var _str = num.toString();
    var _arr = _str.split('.');
    var _int = _arr[0];
    var _flt = _arr[1];
    if(_str.indexOf('.') == -1){
        /* 找不到小数点，则添加 */
        if(ext == 0){
            return _str;
        }
        var _tmp = '';
        for(var i = 0; i < ext; i++){
            _tmp += '0';
        }
        _str = _str + '.' + _tmp;
    }else{
        if(_flt.length == ext){
            return _str;
        }
        /* 找得到小数点，则截取 */
        if(_flt.length > ext){
            _str = _str.substr(0, _str.length - (_flt.length - ext));
            if(ext == 0){
                _str = _int;
            }
        }else{
            for(var i = 0; i < ext - _flt.length; i++){
                _str += '0';
            }
        }
    }

    return _str;
}