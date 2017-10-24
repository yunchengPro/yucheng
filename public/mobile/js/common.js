
/**
 * 判断是否登录状态
 * @Author   zhuangqm
 * @DateTime 2017-09-05T11:08:54+0800
 * @return   {[type]}                 [description]
 */
function cl(data){

	var result = eval("("+data.body+")");

	if(result.code=='104')
		window.location.href='/index/index/login';

	if(result.code=='408')
		window.location.reload(); // 刷新当前页面

	return result;
}

/**
 * 加载提示
 * @Author   zhuangqm
 * @DateTime 2017-09-11T14:57:45+0800
 * @param    {[type]}                 $option   {
 *                                                     
 * 										}
 * @param    {[type]}                 
 * @return   {[type]}                        [description]
 */
function loadtip(option){
	var _option = {
		modelid:'my-modal-loading',
		content:'加载中',
		close:false,
		timeout:5000,  //超时自动关闭
		alert_id:'modal-alert', // 在close：true 情况下
		alert_confirm_id:'modal-alert-confirm',
		alert_title:'提示', // 在close：true 情况下
		alert:'', // 弹窗 在close：true 情况下
		urlto:'', // 页面跳转 在close：true 情况下
	};
 
	$.extend( _option, option );

	if(!_option.close){

		if($("#my-modal-loading").length==0){
			var html = '<div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="'+_option.modelid+'"><div class="am-modal-dialog"><div class="am-modal-hd">'+_option.content+'</div><div class="am-modal-bd-loading"><span class="am-icon-spinner am-icon-spin"></span></div></div></div>';
			$("body").append(html);
		}

		$("#"+_option.modelid).modal();

		if(_option.timeout>0){
			setTimeout(function(){
				$("#"+_option.modelid).modal('close');
			},_option.timeout);
		}

	}else{
		//关闭
		$("#"+_option.modelid).modal('close');

		// 弹窗
		if(_option.alert!=''){
			$("#"+_option.alert_id).remove();
			var html ='<div class="am-modal am-modal-alert" tabindex="-1" id="'+_option.alert_id+'"><div class="am-modal-dialog"><div class="am-modal-hd">'+_option.alert_title+'</div><div class="am-modal-bd">'+_option.alert+'</div><div class="am-modal-footer"><span class="am-modal-btn" id="'+_option.alert_confirm_id+'">确定</span></div></div></div>';
			$("body").append(html);
			$("#"+_option.alert_id).modal();

			// 跳转路径
			if(_option.urlto!=''){
				$("#"+_option.alert_confirm_id).click(function(){
					window.location.href=_option.urlto;
				});
			}
		}else{
			// 跳转路径
			if(_option.urlto!=''){
				window.location.href=_option.urlto;
			}
		}

		
	}
}
