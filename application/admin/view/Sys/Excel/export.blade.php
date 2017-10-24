{include file="Pub/header" /}
<style>
	.excel_show{
		border: 1px solid;
		width:400px;
		height: 130px;
		padding-top: 25px;
		font-size: 16px;
		text-align: center;
	}
</style>
<table class="" width='100%' height='100%'>
	<tr>
		<td align="center" style="padding-top:50px">
			<div class="excel_show">
				<div id="status">未开始</div>
				<div id="info">已完成：<span id='count_data'>0</span></div>
				<div id="down_excel"><?=Html::button(array("name"=>"bt1","class"=>"bg-main","value"=>"开始导出","onclick"=>"getData()"))?></div>
			</div>
		</td>
	</tr>
</table>
<script>
var excel_option = {
	export_flag:<?php echo $export_flag==true?1:-1;?>,
	excel_key:'<?=$excel_key?>',
	getData_url:'<?=$export_url?>',
	excel_domain:'<?=$excel_domain?>',
	excel_url:'<?=$excel_url?>',
	getexcel_url:'<?=$getexcel_url?>',
	query_str:'<?=$query_str?>',
	filepath:'<?=$filepath?>',
	filename:'<?=$filename?>',
	count:0,
	excel_count:1,
	this_excel_count:0
};
function postData(postdata){
	if(postdata.data.length>0 && typeof(postdata.data)!='undefined' && postdata.data!=''){
		$.post(excel_option.excel_url,{
			excel_key:postdata.excel_key,
			head:postdata.head,
			data:postdata.data,
			count:excel_option.count,
			excel_count:excel_option.excel_count,
			this_excel_count:excel_option.this_excel_count
		},function(data){
			//alert(data);
			var obj = eval('('+decodeURI(data)+')'); 
			if(obj.status=='200'){
				excel_option.count=parseInt(obj.count);
				excel_option.excel_count = parseInt(obj.excel_count);
				excel_option.this_excel_count = parseInt(obj.this_excel_count);
				excel_option.filepath = obj.filepath;
				$("#count_data").html(excel_option.count);
				getData(postdata.next_offset);
			}else if(obj.status=='400'){
				alert(obj.info);
			}
		});
	}else{
		alert("end");
	}
}

function getData(offset){
	$("#status").html("导出中...");
	$("#bt1").removeAttr('onclick').removeClass("bg-main").attr("value","导出中").html("导出中");

	var data_obj = eval('('+excel_option.query_str+')');
	data_obj.excel_key=excel_option.excel_key;
	data_obj.offset=offset;

	$.post(excel_option.getData_url,data_obj,function(data){
			//返回json数据格式数据 excel_key offset next_offset head data
			//alert(data);
			//var obj = eval('('+decodeURI(data)+')'); 
			var obj = eval('('+data+')');

			//alert(obj.data.length);
			if(obj.data.length>0 && typeof(obj.data)!='undefined' && obj.data!=''){
				excel_option.filename = obj.filename;
				postData(obj);
			}else{
				//导出完成
				$.post(excel_option.excel_url,{
					excel_key:excel_option.excel_key,
					endflag:1,
					filepath:excel_option.filepath,
					excel_count:excel_option.excel_count
				},function(data){
					//alert(data);
					var endobj = eval('('+decodeURI(data)+')'); 
					/*
					for(var s in endobj){
						alert(s+"===="+endobj[s]);
					}
					*/
					$.post("/Sys/Excel/endexport",{excel_key:excel_option.excel_key,filepath:endobj.filepath,filename:excel_option.filename},function(){});
					$("#status").html("导出完成");
					$("#down_excel").html("<a href='"+excel_option.getexcel_url+"?filepath="+encodeURI(endobj.filepath)+"&filename="+decodeURI(excel_option.filename)+"'><font color='red'>点击下载</font></a>");
				});	

				
			}
	});
}

$(function(){
	//开始数据导出
	//getData();
	if(excel_option.export_flag==-1){
		$("#status").html("10分钟内不能重复导出");
		$("#down_excel").html("<a href='"+excel_option.getexcel_url+"?filepath="+encodeURI(excel_option.filepath)+"&filename="+decodeURI(excel_option.filename)+"'><font color='red'>点击下载</font></a>");
	}
})
</script>
{include file="Pub/footer" /}