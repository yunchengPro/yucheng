
$(function(){
	
	DefaultMessage = '';
	DefaultMessage += '<span error_type="start" class="msg J_Message" style="display:none"><i class="icon-exclamation-sign"></i>首件应输入1至9999的数字</span>';
	DefaultMessage += '<span error_type="postage" class="msg J_Message" style="display:none"><i class="icon-exclamation-sign"></i>首费应输入0.00至999.99的数字</span>';
	DefaultMessage += '<span error_type="plus" class="msg J_Message" style="display:none"><i class="icon-exclamation-sign"></i>续件应输入1至9999的数字</span>';
	DefaultMessage += '<span error_type="postageplus" class="msg J_Message" style="display:none"><i class="icon-exclamation-sign"></i>续费应输入0.00至999.99的数字</span>';
	
	
	SpecialMessage = '';
	SpecialMessage += '<span  error_type="area" class="msg J_Message" style="display:none"><i class="icon-exclamation-sign"></i>指定地区城市为空或指定错误</span>';
	SpecialMessage += '<span  error_type="start" class="msg J_Message" style="display:none"><i class="icon-exclamation-sign"></i>首件应输入1至9999的数字</span>';
	SpecialMessage += '<span error_type="postage" class="msg J_Message" style="display:none"><i class="icon-exclamation-sign"></i>首费应输入0.00至999.99的数字</span>';
	SpecialMessage += '<span error_type="plus" class="msg J_Message" style="display:none"><i class="icon-exclamation-sign"></i>续件应输入1至9999的数字</span>';
	SpecialMessage += '<span error_type="postageplus" class="msg J_Message" style="display:none"><i class="icon-exclamation-sign"></i>续费应输入0.00至999.99的数字</span>';
	
	
	
	/*	首(件|重|体积)离开校验*/
	$('.trans-line').on('blur','input[data-field="start"]',function (){
		
		var valuation_type = $("input[name=valuation_type]:checked").val();
		
		switch(valuation_type){
			
			case "1":
				if ($(this).val() != ''){
					$(this).val($(this).toInt());
				}else{
					$(this).val(1);
				}
				break;
			
			case "2":		
			case "3":
				if ($(this).val() != ''){
					//$(this).val().toFixed(3);					
					sNum = new Number($(this).val());
					sNum = sNum.toFixed(3);
					$(this).val(sNum);
					
				}else{
					$(this).val(0.000);
				}
				break;
			
		}	
		
		
	});
	
	
	/*	首费离开校验*/
	$('.trans-line').on('blur','input[data-field="postage"]',function (){
		var oNum = new Number($(this).val());
		oNum = oNum.toFixed(2);
		if (oNum > 999.99) oNum = 999.99;
		if (oNum=='NaN') oNum = '0.00'; 
		$(this).val(oNum);
		if($(this)[0].className=='w50 mr5 text input-error') $(this).removeClass('input-error');
		if($(this)[0].className=='w50 ml5 mr5 text input-error') $(this).removeClass('input-error');
		
		if($(this).parent()[0].className=='default J_DefaultSet'){
			//首费不为空了，如果是默认的首费，隐藏提示层span
			$(this).parent().find('.J_DefaultMessage').find('span[error_type="postage"]').css('display','none');
		}else{
			//如果是动态添加的首费,当所有首费输入框都不为空时，提示层span隐藏
			isRemove = true;
			$(this).parent().parent().parent().find('input[data-field="postage"]').each(function(){
				if ($(this).val()==''){
					isRemove = false;return false;
				}
			});
			//提示层span隐藏
			if (isRemove == true){
				$(this).parent().parent().parent().parent().parent().parent().find('.tbl-attach').find('.J_SpecialMessage').find('span[error_type="postage"]').css('display','none');
			}
		}
	});
	
	
	/*	续费离开校验*/
	$('.trans-line').on('blur','input[data-field="postageplus"]',function (){
		var oNum = new Number($(this).val());
		oNum = oNum.toFixed(2);
		if (oNum > 999.99) oNum = 999.99;
		if (oNum=='NaN') oNum = '0.00';
		$(this).val(oNum);
		if($(this)[0].className=='w50 mr5 text input-error') $(this).removeClass('input-error');
		if($(this)[0].className=='w50 ml5 mr5 text input-error') $(this).removeClass('input-error');

		if($(this).parent()[0].className=='default J_DefaultSet'){
			//续费不为空了，如果是默认的首费，隐藏提示层span
			$(this).parent().find('.J_DefaultMessage').find('span[error_type="postageplus"]').css('display','none');
		}else{
			//如果是动态添加的首费,当所有续费输入框都不为空时，提示层span隐藏
			isRemove = true;
			$(this).parent().parent().parent().find('input[data-field="postageplus"]').each(function(){
				if ($(this).val()==''){
					isRemove = false;return false;
				}
			});
			//提示层span隐藏
			if (isRemove == true){
				$(this).parent().parent().parent().parent().parent().parent().find('.tbl-attach').find('.J_SpecialMessage').find('span[error_type="postageplus"]').css('display','none');
			}
		}			
	});

	/*	续件离开校验*/
	$('.trans-line').on('blur','input[data-field="plus"]',function (){
		var valuation_type = $("input[name=valuation_type]:checked").val();
		
		switch(valuation_type){
			
			case "1":
				if ($(this).val() != ''){
					$(this).val($(this).toInt());
				}else{
					$(this).val(1);
				}
				break;
			
			case "2":		
			case "3":
				if ($(this).val() != ''){
					//$(this).val().toFixed(3);					
					sNum = new Number($(this).val());
					sNum = sNum.toFixed(3);
					$(this).val(sNum);
					
				}else{
					$(this).val(0.000);
				}
				break;
			
		}
	});
	
	
	
	$("#j_number").click(function(){
		
		
		
		if(!confirm("切换计价方式后，所设置当前模板的运输信息将被清空，确定继续吗？")){
			return false;
		}

		$(this).parent().parent().find('span').removeClass('sp-on');
		$(this).parent().find('span').addClass('sp-on');

		var html = express(1);
		
		$("#express").html(html);
		// alert(type);
		if(type == 1){
			window.location.reload();
		}
		
	});
	
	$("#j_weight").click(function(){

		

		if(!confirm("切换计价方式后，所设置当前模板的运输信息将被清空，确定继续吗？")){
			return false;
		}

		$(this).parent().parent().find('span').removeClass('sp-on');
		$(this).parent().find('span').addClass('sp-on');

		var html = express(2);
		
		$("#express").html(html);
		// alert(type);
		if(type == 2){
			window.location.reload();
		}
		
	});
	
	$("#j_volume").click(function(){
		
		if(!confirm("切换计价方式后，所设置当前模板的运输信息将被清空，确定继续吗？")){
			return false;
		}

		$(this).parent().parent().find('span').removeClass('sp-on');
		$(this).parent().find('span').addClass('sp-on');
		
		var e_html = express(3);
		
		$("#express").html(e_html);
		// alert(type);
		if(type == 3){
			window.location.reload();
		}
		
	});
	
	//为指定地区城市设置运费
	$("#j_addrule").click(function(){
		
		var r_html;
		
		r_html = rule();
		
		$("#list-table").append(r_html);
		
		
	});
	
	$("#transport").submit(function(){
		$('.J_SpecialMessage').html(SpecialMessage);
		$('.J_DefaultSet').find('.J_DefaultMessage').html(DefaultMessage);
		isSubmit = true;
		
				
		//首费JS空判断-------------------------------
		var obj = $('.entity').find('.J_DefaultSet').find('input[data-field="postage"]');
		
		if(!$(obj).val()){
			isShowError = false;
			if($(obj).val()==''){
				$(obj).addClass('input-error'); isShowError = true; isSubmit = false;
			}else{
				$(obj).removeClass('input-error');
			}
			if (isShowError){
				$('.entity').find('.J_DefaultSet').find('span[error_type="postage"]').show();
			}
		}
		
		//续费JS空判断-------------------------------
		var obj = $('.entity').find('.J_DefaultSet').find('input[data-field="postageplus"]');
		if(!$(obj).val()){
			isShowError = false;
			if($(obj).val()==''){
				$(obj).addClass('input-error'); isShowError = true; isSubmit = false;
			}else{
				$(obj).removeClass('input-error');
			}
			if (isShowError){
				$('.entity').find('.J_DefaultSet').find('span[error_type="postageplus"]').show();
			}				
		}
		
		
		//地区空判断-------------------------------
		if($('.tbl-except').find('.cell-area').html() != null){
			isShowError = false;
			$('.tbl-except').find('tbody>tr').each(function(){
				if($(this).find('input[type="hidden"]').val()==''){
					//isShowError = true; 
					//isSubmit = false;
				}
			});
			if (isShowError){
				$(this).find('.tbl-attach').find('span[error_type="area"]').show();
			}
		}
		
		//首费JS空判断-------------------------------
		if($('.tbl-except').find('.cell-area').html() != null){
			isShowError = false;
			$('.tbl-except').find('input[data-field="postage"]').each(function(){
				if ($(this).val()==''){
					$(this).addClass('input-error');isShowError = true; isSubmit = false;
				}
			});

			if (isShowError){
				$('.tbl-attach').find('span[error_type="postage"]').show();
			}
		}
		
		//续费JS空判断-------------------------------
		if($('.tbl-except').find('.cell-area').html() != null){
			isShowError = false;
			$('.tbl-except').find('input[data-field="postageplus"]').each(function(){
				if ($(this).val()==''){
					$(this).addClass('input-error'); isShowError = true; isSubmit = false;
				}
			});

			if (isShowError){
				$('.tbl-attach').find('span[error_type="postageplus"]').show();
			}
		}
		
		
		//运费模板名称校验
		if ($('#title').val()==''){
			isSubmit = false;
			$('p[error_type="title"]').show();
		}else{
			$('p[error_type="title"]').hide();
		}
		
		//所属商家校验
		if ($('#business_id').val()==''){
			isSubmit = false;
			$('p[error_type="business_id"]').show();
		}else{
			$('p[error_type="business_id"]').hide();
		}
		
		
		
		if (isSubmit == true){
			return true;
		}else{
			return false;
		}
		
	});
	
	
	
	/*	选择运送区域*/
	$('.trans-line').on('click','a[entype="J_EditArea"]',function () {
		
		//curTransType = $(this).next().next().attr('name').substring(6,8);
		curTransType = 'kd';
        //取消所有已选择的checkbox
		$('#J_CityList').find('input[type="checkbox"]').attr('checked',false).attr('disabled',false);

		//取消显示所有统计数量
		$('#J_CityList').find('.check_num').html('');

		//记录当前行的标识n1,n2,n3....
		curIndex = $(this).attr('data-group');

		//记录当前操作的行，选择完地区会向该区域抛出值
		objCurlArea = $('tr[data-group="'+curIndex+'"]');
		//记录已选择的所有省及市的value，SelectArea下标为value值，值为true，如江苏省SelectArea[320000]=true,南京市SelectArea[320100]=true
		SelectArea = new Array();

		//取得当前行隐藏域内的city值，放入SelectArea数组中
		var expAreas = $('input[name="areas['+curTransType+']['+curIndex.substring(1)+']"]').val();
		expAreas = expAreas.split('|||');
		expAreas = expAreas[0].split(',');
		try{
			if(expAreas[0] != ''){
				for(var v in expAreas){
					SelectArea[expAreas[v]] = true;
				}
			}
			//初始化已选中的checkbox
			$('#J_CityList').find('.ncsc-province').each(function(){
				var count = 0;
				$(this).find('input[type="checkbox"]').each(function(){
					if(SelectArea[$(this).val()]==true){
						$(this).attr('checked',true);
						if($(this)[0].className!='J_Province') count++;
					}
				});
				if (count > 0){
					$(this).find('.check_num').html('('+count+')');
				}
	
			});

			//循环每一行，如果一行省都选中，则大区载选中
			$('#J_CityList>li').each(function(){
				$(this).find('.J_Group').attr('checked',true);
				father = this;
				$(this).find('.J_Province').each(function(){
					if (!$(this).attr('checked')){
						$(father).find('.J_Group').attr('checked',false);
						return ;
					}
				});
			});
		}catch(ex){};
		
		
		//其它行已选择的地区，不能再选择了
		$(objCurlArea).parent().parent().find('.area-group').each(function(){
			
			if ($(this).next().attr('name') != 'areas['+curTransType+']['+curIndex.substring(1)+']'){
				expAreas = $(this).next().val().split('|||');
				expAreas = expAreas[0].split(',');
				//重置SelectArea
				SelectArea = new Array();
				try{
					if(expAreas[0] != ''){
						for(var v in expAreas){
							SelectArea[expAreas[v]] = true;
						}
					}

					//其它行已选中的在这里都置灰
					$('#J_CityList').find('input[type="checkbox"]').each(function(){
						if(SelectArea[$(this).val()]==true){
							$(this).attr('disabled',true).attr('checked',false);
						}
					});
					//循环每一行，如果一行的省都被disabled，则大区域也disabled
					$('#J_CityList>li').each(function(){
						$(this).find('.J_Group').attr('disabled',true);
						father = this;
						$(this).find('.J_Province').each(function(){
							if (!$(this).attr('disabled')){
								$(father).find('.J_Group').attr('disabled',false);
								return ;
							}
						});
					});				
				}catch(ex){}
			}
		});
		
		
		
		$("#dialog_areas").css({'position' : 'fixed','display' : 'block', 'z-index' : '9999'});
		$('.ks-ext-mask').css('display','block');
	
	});
	
	
	/*	关闭选择区域层*/
	$('#dialog_areas').on('click','.ks-ext-close',function(){
	    $("#dialog_areas").css('display','none');
	    $("#dialog_batch").css('display','none');
	    $('.ks-ext-mask').css('display','none');
	    return false;
	});

	$('#dialog_batch').on('click','.ks-ext-close',function(){
	    $("#dialog_areas").css('display','none');
	    $("#dialog_batch").css('display','none');
	    $('.ks-ext-mask').css('display','none');
	    return false;
	});

	/*	关闭选择区域层*/
	$('#dialog_areas').on('click','.J_Cancel',function(){
	    $("#dialog_areas").css('display','none');
	    $("#dialog_batch").css('display','none');
	    $('.ks-ext-mask').css('display','none');
	});

	$('#dialog_batch').on('click','.J_Cancel',function(){
	    $("#dialog_areas").css('display','none');
	    $("#dialog_batch").css('display','none');
	    $('.ks-ext-mask').css('display','none');
	});
	
	
	/*	省份点击事件*/
	$('.J_Province').on('click',function(){
		
		
		if ($(this).is(':checked')){
			
			//选择所有未被disabled的子地区
			$(this).parent().find('.ncsc-citys-sub').eq(0).find('input[type="checkbox"]').each(function(){
				if (!$(this).attr('disabled')){
					$(this).attr('checked',true);
				}else{
					$(this).attr('checked',false);
				}
			});
			//计算并显示所有被选中的子地区数量
			num = '('+$(this).parent().find('.ncsc-citys-sub').eq(0).find('input:checked').size()+')';
			
			if (num == '(0)') num = '';
			$(this).parent().parent().find(".check_num").eq(0).html(num);

			//如果该大区域所有省都选中，该区域选中
			input_checked 	= $(this).parent().parent().parent().find('input:checked').size();
			input_all 		= $(this).parent().parent().parent().find('input[type="checkbox"]').size();
			if (input_all == input_checked){
				$(this).parent().parent().parent().parent().find('.J_Group').attr('checked',true);
			}	

		}else{
			//取消全部子地区选择，取消显示数量
			$(this).parent().parent().find(".check_num").eq(0).html('');
			$(this).parent().find('.ncsc-citys-sub').eq(0).find('input[type="checkbox"]').attr('checked',false);
			//取消大区域选择
			$(this).parent().parent().parent().parent().find('.J_Group').attr('checked',false);
		}
	});
	
	
	
	/*	省份下拉事件*/
	$('.trigger').on('click',function () {
		objTrigger = this;objHead = $(this).parent();objPanel = $(this).next();
		if ($(this).next().css('display') == 'none'){
			//隐藏所有已弹出的省份下拉层，只显示当前点击的层
			$('.ks-contentbox').find('.ncsc-province').removeClass('showCityPop');
			$(this).parent().parent().addClass('showCityPop');
		}else{
			//隐藏当前的省份下拉层
			$(this).parent().parent().removeClass('showCityPop');
		}
		//点击省，市所在的head与panel层以外的区域均隐藏当前层
        var oHandle = $(this);
        //oHandle = document.getElementById($(this).attr('id'));//不兼容Ie8,废弃
		var de = document.documentElement?document.documentElement : document.body;
        de.onclick = function(e){
	        var e = e || window.event;
	        var target = e.target || e.srcElement;
	        var getTar = target.getAttribute("id");
	        while(target){
	        	//循环最外层一个时，会出现异常
				try{
					//jquery 转成DOM对象，比较两个DOM对象
	                if(target==$(objHead)[0])return true;
	                if(target==$(objPanel)[0])return true;
	                //暂不考虑使用ID比较
	                //if(target.getAttribute("id")==$(objHead).attr('id'))return true;
	                //if(target.getAttribute("id")==$(objPanel).attr('id'))return true;
				}catch(ex){};
	            target = target.parentNode;
	        }
	        $(objTrigger).parent().parent().removeClass('showCityPop');
        }
	});
	
	
	/*	市级地区单事件*/
	$('.J_City').on('click',function(){
		//显示选择市级数量，在所属省后面
		num = '('+$(this).parent().parent().find('input:checked').size()+')';
		if (num=='(0)')num='';
		$(this).parent().parent().parent().find(".check_num").eq(0).html(num);
		//如果市级地区全部选中，则父级省份也选中，反之有一个不选中,则省份和大区域也不选中
		if (!$(this).attr('checked')){
			//取消省份选择
			$(this).parent().parent().parent().find('.J_Province').attr('checked',false);
			//取消大区域选择
			$(this).parent().parent().parent().parent().parent().parent().find('.J_Group').attr('checked',false);
		}else{
			//如果该省所有市都选中，该省选中
			input_checked 	= $(this).parent().parent().find('input:checked').size();
			input_all 		= $(this).parent().parent().find('input[type="checkbox"]').size();
			if (input_all == input_checked){
				$(this).parent().parent().parent().find('.J_Province').attr('checked',true);
			}
			//如果该大区域所有省都选中，该区域选中
			input_checked 	= $(this).parent().parent().parent().parent().parent().find('input:checked').size();
			input_all 		= $(this).parent().parent().parent().parent().parent().find('input[type="checkbox"]').size();
			if (input_all == input_checked){
				$(this).parent().parent().parent().parent().parent().parent().find('.J_Group').attr('checked',true);
			}
		}
	});
	
	
	/*	大区域点击事件（华北、华东、华南...）*/
	$('.J_Group').on('click',function(){

		if ($(this).attr('checked')){
			//alert(1);
			//区域内所有没有被disabled复选框选中，带disabled说明已经被选择过了，不能再选
			$(this).parent().parent().parent().find('input[type="checkbox"]').each(function(){
				if (!$(this).attr('disabled')){
					$(this).attr('checked',true);
				}else{
					$(this).attr('checked',false);
				}				
			});
			//循环显示每个省下面的市级的数量
			$(this).parent().parent().parent().find('.ncsc-province-list').find('.ncsc-province').each(function(){
				//显示该省下面已选择的市的数量
				num = '('+$(this).find('.ncsc-citys-sub').find('input:checked').size()+')';
				//如果是0，说明没有选择，不显示数量
				if (num != '(0)'){
					$(this).find(".check_num").html(num);
				}
			});
		}else{
			//alert(2);
			//区域内所有筛选框取消选中
			//$(this).parent().parent().parent().find('input[type="checkbox"]').attr('checked',false);
			$(this).parent().parent().parent().find('input[type="checkbox"]').attr('checked',true);
			//循环清空每个省下面显示的市级数量
			$(this).parent().parent().parent().find('.ncsc-province-list').find('.ncsc-province').each(function(){
				$(this).find(".check_num").html('');
			});
		}

	});
	
	
	/*	选择完区域后，确定事件*/
	$('#dialog_areas').on('click','.J_Submit',function (){
		var CityText = '', CityText2 = '', CityValue = '';
		//记录已选择的所有省及市的value，SelectArea下标为value值，值为true，如江苏省SelectArea[320000]=true,南京市SelectArea[320100]=true
//		SelectArea = new Array();
		//取得已选的省市的text，返回给父级窗口，如果省份下的市被全选择，只返回显示省的名称，否则显示已选择的市的名称
		//首先找市被全部选择的省份
		$('#J_CityList').find('.ncsc-province-tab').each(function(){
			var a = $(this).find('input[type="checkbox"]').size();
			var b = $(this).find('input:checked').size();
			//市被全选的情况
			if (a == b){
				alert(1);
				CityText += ($(this).find('.J_Province').next().html())+',';
			}else{
				//市被部分选中的情况
				$(this).find('.J_City').each(function(){
						//计算并准备传输选择的区域值（具体到市级ID），以，隔开
							if ($(this).attr('checked')){
								CityText2 += ($(this).next().html())+',';
							}
				});
			}
		});
		CityText += CityText2;

		//记录弹出层内所有已被选择的checkbox的值(省、市均记录)，记录到CityValue，SelectArea中
		$('#J_CityList').find('.ncsc-province-list').find('input[type="checkbox"]').each(function(){
			if ($(this).attr('checked')){
				CityValue += $(this).val()+',';
			}
		});

		//去掉尾部的逗号
		CityText = CityText.replace(/(,*$)/g,'');
		CityValue = CityValue.replace(/(,*$)/g,'');

		//返回选择的文本内容
		if (CityText == '')CityText = '未添加地区';
		
		$(objCurlArea).find('.area-group>p').html(CityText);
		//返回选择的值到隐藏域
		$('input[name="areas['+curTransType+']['+curIndex.substring(1)+']"]').val(CityValue+'|||'+CityText);
		//关闭弹出层与遮罩层
	    $("#dialog_areas").css('display','none');
	    $('.ks-ext-mask').css('display','none');
	    //清空check_num显示的数量
		$(".check_num").html('');
		$('#J_CityList').find('input[type="checkbox"]').attr('checked',false);
		//如果该配送方式，地区都不为空，隐藏地区的提示层
		isRemove = true;
		$('div[data-delivery="'+curTransType+'"]').find('input[type="hidden"]').each(function(){
			if ($(this).val()==''){
				isRemove = false;return false;
			}
		});
		if (isRemove == true){
			$('div[data-delivery="'+curTransType+'"]').find('span[error_type="area"]').css('display','none');
		}
	});
	
	
	
	
	/*	关闭弹出的市级小层*/
	$('#dialog_areas').on('click','.close_button',function(){ 
	    $(this).parent().parent().parent().parent().removeClass('showCityPop');
	});
	
	
	
	
	
	
	
	
	
	
	
	
	
});

/*	自定义转成整形的方法*/
jQuery.fn.toInt = function() {
	var s = parseInt(jQuery(this).val().replace( /^0*/,''));
	return isNaN(s) ? 0 : s;
};

var r_num = 1;

function rule(){	
	
	var rule = '';
	
	rule += '<tbody><tr class="bd-line" data-group="n'+r_num+'">';
	rule += '<td class="cell-area"><span class="area-group"><input class="J_BatchField" style="width:15px; display:none" checked="checked" type="checkbox" value="" name="kd_n'+r_num+'"><p style="display:inline-block">未添加地区</p></span></span><input type="hidden" value="" name="areas[kd]['+r_num+']"><a class="btn-blue edit" data-group="n'+r_num+'" title="编辑运送区域" area-haspopup="true" area-controls="J_DialogArea" entype="J_EditArea" data-acc="event:enter" href="JavaScript:void(0);">编辑</a></td>';
	rule += '<td class="text-c"><input class="input-text" style="width:80px;" type="text" value="1" data-field="start" name="special[kd]['+r_num+'][start]"></td>';
	rule += '<td class="text-c"><input class="input-text" style="width:80px;" type="text" value="" data-field="postage" name="special[kd]['+r_num+'][postage]">&nbsp;元</td>';
	rule += '<td class="text-c"><input class="input-text" style="width:80px;" type="text" maxlength="4" autocomplete="off" value="1" data-field="plus" name="special[kd]['+r_num+'][plus]"></td>';
	rule += '<td class="text-c"><input class="input-text" style="width:80px;" type="text" maxlength="6" autocomplete="off" value="" data-field="postageplus" name="special[kd]['+r_num+'][postageplus]"></td>';
	rule += '<td class="text-c">&nbsp;&nbsp;<a href="javascript:;" onclick="delaction($(this))" class="J_DeleteRule" style="color:blue">删除</a></td>';
	
	rule += '<div class="J_SpecialMessage"></div>';
	
	
	rule += '</tr></tbody>';
	
	r_num++;
	
	return rule;
	
	
}





$('.J_DeleteRule').click(function(){
    $(this).parent().parent().remove();
});

function delaction(o){
	//alert(o.parent().parent().find('input[type="checkbox"]').val());
	var tid = o.parent().parent().find('input[type="checkbox"]').val();
	 $.ajax({
            type:'GET',
            dataType:'json',
            url:"/Business/Transport/delTransportExtend?tid="+tid,
            success: function(data){
                //data = eval("("+data+")");
                //alert(data.code);  
                if(data.code == 200){
                	alert('删除成功');
                	 o.parent().parent().remove();
                }else{
                	alert('删除失败');
                }
             
            }
    });
   

}

function showExpress(type){
	var html = express(type);
		
	$("#express").html(html);
}


function express(type){
	var t_val = "";
	switch(type){
		case 1:
			t_val = "件";
			break;
		case 2:
			t_val = "kg";
			break;
		case 3:
			t_val = "m³";
			break;			
	}
	
	/*var express = '<div class="ncsu-trans-type" data-delivery="kd">';
	express = '<div id="defaultset" class="entity" style="margin-bottom:10px;">';
	express += '<div class="J_DefaultSet">';
	express += '<label>默认运费：</label>';
	express += '<input type="text" class="input-text" data-field="start" name="default[kd][start]" style="width:80px;">&nbsp;'+t_val+'内，&nbsp;';
	express += '<input type="text" class="input-text" data-field="postage" name="default[kd][postage]" style="width:80px;">&nbsp;元，';
	express += '<label>每增加&nbsp;</label><input type="text" class="input-text" data-field="plus" name="default[kd][plus]" style="width:80px;">&nbsp;'+t_val+'，';
	express += '<label>增加运费&nbsp;</label><input type="text" class="input-text" data-field="postageplus" name="default[kd][postageplus]" style="width:80px;">&nbsp;元';	
	express += '<div class="J_DefaultMessage"></div>';
	express += '</div></div></div>';*/


	var express='<div class="row cl default-fare">'+
		'<span>默认运费：</span><input type="text" data-field="start" name="default[kd][start]" />'+t_val+'内，'+
		'<input type="text"  data-field="postage" name="default[kd][postage]"/> '+'元'+'，'+
		'每增加<input type="text" data-field="plus" name="default[kd][plus]"/>'+t_val+'，'+
		'运费增加<input type="text" data-field="postageplus" name="default[kd][postageplus]"/>元'+
	'</div>';
	
	
	
	switch(type){
		case 1:
			t_val = "件";
			break;
		case 2:
			t_val = "重(kg)";
			break;
		case 3:
			t_val = "体积(m³)";
			break;			
	}	
	
	// express += '<div class="tbl-except">';
	// express += '<table class="table table-hover" id="list-table">';
	// express += '<thead><tr>';
	// express += '<th width="145">运送到</th>';
	// express += '<th width="145">首'+t_val+'</th>';
	// express += '<th width="145">首费(元)</th>';
	// express += '<th width="145">续'+t_val+'</th>';
	// express += '<th width="145">续费(元)</th>';
	// express += '<th width="145">操作</th></thead>';
	// express += '</table></div>';
	
	express+='<div class="tbl-except row cl">'+
		'<table class="table table-border table-bordered fare-tb" id="list-table">'+
			'<thead>'+
				'<tr>'+
					'<th width="20%">运送到</th>'+
						'<th>首'+t_val+'）</th>'+
						'<th>首付（元）</th>'+
						'<th>续'+t_val+'</th>'+
						'<th>续费（元）</th>'+
						'<th>操作</th>'+
				'</tr>'+
			'</thead>'+
			
		'</table>'+
	'</div>';

	
	//express += '<div class="tbl-attach" style="margin: 0 10px;color:blue;"><div class="j_message"></div><a href="javascript:;" id="j_addrule" style="color:blue;">为指定地区城市设置运费</a></div>';
	
	return 	express;	
	
}
