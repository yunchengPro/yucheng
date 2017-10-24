void function(){

	$(".row_edit").click(function(e){	
		var target  = e.target || e.srcElement;
		var row_edit = $(target).closest('.row_edit');
		var _url = row_edit.attr("_url");
		layer.open({
			type: 2,
			title: '修改记录',
			maxmin: true,
			shadeClose: false, //点击遮罩关闭层
			area : ['600px' , '420px'],
			content: _url+"?popurl="+encodeURI(_url),
		});

	});


	$("input[_rel=sort]").on("change",function(){
		var url = $(this).attr("_url");
		var val = $(this).val();
		var name = $(this).attr("name");
		if(isNaN(val)){
			return false;
		}		
		url = url + "?" + name + "=" + val;
		$.ajax({
			type: "GET",
			async: false,
			url: url,
			success: function(data){}
		});		
		
	});





	$("#btn_help").click(function(){//每个页面的帮助下拉
			var help_bar_div = document.getElementById("help_bar_div");
			if (help_bar_div.style.display == "block" ){
				help_bar_div.style.display = "none";	
			}else{
				help_bar_div.style.display = "block";	
			}
	});


	$(".btn_dropdown .toggle").on("click",function(){//下拉式按钮的处理
		var target = $(this).next();
		var displayStatus = target.css("display")=="none"?"block":"none";
		$(".dropdown_list").hide();
		target.css({display:displayStatus,"min-width":$(this).parent().width()});
	});

	$("a.mod_tips").hover(function(){
			$("<span>").addClass("mod_tips_wrap").html($(this).attr("title")).appendTo($(this));
		},function(){
			alert('hello');
		}
	);
	$(document).bind("click",function(evt){//下拉式按钮外的点击，需要收起下拉框
		var _target = evt ? evt.target : event.srcElement ;
		if ($(_target).attr("class") != "toggle"){
			$(".dropdown_list").hide();
		}
	});
	//异步表单提交
	var forms = $("form[rel=iframe-form]");
	forms.each(function(){
		var theform = this;
		var iframeID = 'formsubmitiframe';
		var iframe = $('<iframe />').attr({'src':'', 'frameBorder':0,'id':iframeID,'name':iframeID}).css({"display":"none"}).appendTo("body");
		$(this).attr("target",iframeID);
		iframe.load(function(){
			if ($(theform).find("input[type=submit]").attr("disabled")){
				$(theform).find("input[type=submit]").attr("disabled",false).removeClass().addClass('btn btn_blue'); 
			}
		});
	});
	$(window).on("keydown",function(e){
	    if (typeof(top.handle_event) == "function"){
            top.handle_event(e,window);
	    }
	})
	/*
	$(".tooltips").each(function(){
		if ($(this).attr("title")){
			var html = ([
				'<div class="tooltips_msg" style="display:none;">',
					$(this).attr("title"),
					'<span class="pointer"></span>',
				'</div>'
			]).join('');
			$(this).append(html);
			$(this).hover(function(){
				$(this).find(".tooltips_msg").show();
			});
			$(this).mouseout(function(){
				$(this).find(".tooltips_msg").delay(1000).hide();
			});
			$(this).attr("title","");
		}
	});
	*/
	$(document).click(function(e){
        var target  = e.target || e.srcElement;
        if( $(target).closest('.btn-select').length == 0 ){
    		$(".btn-select").removeClass("btn-select-hover");
    	}  else    {

    	    $(".btn-select").removeClass("btn-select-hover");
    	    $(target).closest('.btn-select').addClass('btn-select-hover');
    	}
	});
}();
var do_size_adjust = function(){
	if (window.frameElement){

		document.body.style.overflow = 'hidden';
			var height = window.document.body.scrollHeight;
			window.frameElement.style.height = height+'px';
			$(window.frameElement).parent().height(height);
			var h3 = $("h3",$(window.frameElement).parent().parent());
			h3.attr("title","height:"+$(window.document).height());
	}
}

if (window.frameElement){
    if (window.document.body.scrollHeight < 500 && YSL.getParameter('_resize') == 1){
        window.onload = do_size_adjust;
    }
}



