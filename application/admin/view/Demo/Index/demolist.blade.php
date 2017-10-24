<?php
	$title = "Demo 列表";
	/*
    设置列表，表头
    "mobile"=>array("name"=>"所属分会"),"sort"=>true,"width"=>"120","head_str"=>"width='30' nowrap","align"=>"left"),
    mobile 显示的字段
    name     表头名称
    sort     是否排序，默认false 不排序
    width    列宽度
    head_str 表头的其他信息
    data_str 数据其他信息
    align    设置内容项位置：left,center,right 默认是居中center
    nowrap   设置表格内容过长时是否进行缩进，默认false
    */
    $list_set = array(
        "checkbox"=>array("name"=>"选择","width"=>"30"),
        "id"=>array("name"=>"编号","sort"=>true),
        "name"=>array("name"=>"商家名称","width"=>"120"),
        "manager"=>array("name"=>"经营者","sort"=>true,"align"=>"left"),
        "mobile"=>array("name"=>"联系方式"),
        "mobile"=>array("name"=>"所属地区"),
        "fenhui"=>array("name"=>"所属分会","sort"=>true,"width"=>"120","head_str"=>"style='color:red' nowrap"),
        "agents_id"=>array("name"=>"订单数"),
        "pay"=>array("name"=>"销售额(元)"), //自定义的显示值
        "count"=>array("name"=>"会员数"),   //自定义的显示值
        "isrecommend"=>array("name"=>"是否推荐","sort"=>true,"data_arr"=>array("0"=>"停用","1"=>"启用","data_color"=>array("0"=>"red")),
        "bussinesssorter"=>array("name"=>"排序","sort"=>true,"data_sort"=>true,"_url"=>"/demo/pub/sort")),
        "addtime"=>array("name"=>"添加时间","sort"=>true,"nowrap"=>true),
        "act"=>array("name"=>"操作","width"=>"200"),
    );

    /*
    前端显示数据处理
    也可以在controller中处理
    */
    foreach($pagelist as $key=>$row){
    	//print_r($row);
		// $pagelist[$key]['act'].= Html::button(array("type"=>"popup_listpage","name"=>"button","value"=>"编辑","_width"=>"500","_height"=>"400","_title"=>"编辑","_url"=>"/demo/pub/edit/id/".Encode('1')))."&nbsp;";
  //       $pagelist[$key]['act'].= Html::button(array("type"=>"confirm","name"=>"button","value"=>"删除","_width"=>"500","_height"=>"200","_title"=>'是否确定删除该记录?',"_url"=>"/demo/linkinfo1/del/id/".Encode('1')))."&nbsp;";
  //       $pagelist[$key]['act'].= Html::button(array("type"=>"confirm","name"=>"button","value"=>"停用","_width"=>"500","_height"=>"200","_title"=>'确定停用该记录?',"_url"=>"/demo/linkinfo3/updatestatus/status/1/id/".Encode('1')));

        $pagelist[$key]['act'][]= array("type"=>"popup_listpage","name"=>"button","value"=>"编辑","_width"=>"500","_height"=>"400","_title"=>"编辑","_url"=>"/demo/pub/edit/id/".Encode('1'));

        $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"删除","_width"=>"500","_height"=>"200","_title"=>'是否确定删除该记录?',"_url"=>"/demo/linkinfo1/del/id/".Encode('1'));
        
        $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"停用","_width"=>"500","_height"=>"200","_title"=>'确定停用该记录?',"_url"=>"/demo/linkinfo3/updatestatus/status/1/id/".Encode('1'));

        $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
	}

    /*
    设置搜索项
    "keyword"=>array("type"=>"text","name"=>"商品名称/关键字","value"=>$this->_request->getParam("keyword")),
    "keyword" 表示搜索项的名称
    "type"    类型：text | select | time | times 
    "value"   初始化值，之后的值自动获取到，但是controller层需要把数据提交过来
    "name"    中文说明
    "option"  设置select类型的下拉选项
    "dateFmt" 1-年月日（默认） 2-年月日时分秒 3-年月 4-年  用于时间控件
	
    说明：times类型下，表单的命名自动在前面加"start_"和"end_"
          显示的值  自动获取 
    */
 	$search = array(
        "keyword"=>array("type"=>"text","name"=>"商品名称/关键字","value"=>""),
        "productcode"=>array("type"=>"text","name"=>"商品编号"),
        "categoryid"=>array("type"=>"select","name"=>"商品分类","option"=>$categoryidarr),
        "area"=>array("type"=>"area","name"=>"地区"),
        "time"=>array("type"=>"time","name"=>"时间","dateFmt"=>3),
        "addtime"=>array("type"=>"times","name"=>"添加时间","start_value"=>'',"end_value"=>''),
        "addtime1"=>array("type"=>"times","name"=>"添加时间","start_value"=>'',"end_value"=>''),
        "addtime2"=>array("type"=>"times","name"=>"添加时间","start_value"=>'',"end_value"=>''),
        "addtime3"=>array("type"=>"times","name"=>"添加时间","start_value"=>'',"end_value"=>''),
    );

    //帮助说明内容
    $helpstr = "暂无帮助内容";
    $export_url = "/demo/index/export"; //导出数据的action

    //自动生成按钮
    $button = array(
            "bt1"=>array("name"=>"button","value"=>"按钮名称","onclick"=>"alert('hello world!');"),//普通按钮
            "bt2"=>array("type"=>"popup","name"=>"button","value"=>"编辑","_width"=>"500","_height"=>"200","_title"=>"编辑","_url"=>"/demo/pub/edit/id/".Encode('1')),//弹窗按钮
            "bt3"=>array("type"=>"confirm","name"=>"button","value"=>"删除","_width"=>"500","_height"=>"200","_title"=>'是否确定删除该记录?',"_url"=>"/demo/linkinfo1/del/id/".Encode('1')),//提示按钮
            "bt4"=>array("type"=>"confirm","name"=>"button","value"=>"停用","_width"=>"500","_height"=>"200","_title"=>'确定停用该记录?',"_url"=>"/demo/linkinfo3/updatestatus/status/1/id/".Encode('1')),//提示按钮
        );
?> 
<?php if ($full_page){?>
<!---头部-->
{include file="Pub/header" /}
{include file="Pub/pubHead" /}
<!---头部-->
			<!---搜索条件-->
			{include file="Pub/pubSearch" /}
			<!---搜索条件-->
            
            <!---这里可以写操作按钮等-->
                    <?=Html::button(array("type"=>"confirm","name"=>"button","value"=>"启用","_width"=>"500","_height"=>"200","_title"=>'选中记录标记为启用?',"_url"=>"/demo/linkinfo1/del/id/".Encode('1')));?><br>
                    <?=Html::button(array("type"=>"popup","name"=>"button","value"=>"编辑","_width"=>"500","_height"=>"200","_title"=>"编辑","_url"=>"/demo/linkinfo1/edit/id/".Encode('1')));?>
                    &nbsp;
                    <button class="button "  type="button"  id="btn_mark_stop" ><?='选中记录标记为'?><font color='red'><?='停用'?></font></button>
                    <button class="button "  type="button" id="btn_mark_normal"  ><?='选中记录标记为'?><font color='green'><?='启用'?></font></button>
                    <button class="button " _rel="popup" _title="新增" _url="/demo/linkinfo1/edit/id/1" type="button"  id="btn_mark_stop" ><?='选中记录标记为'?><font color='red'><?='新增'?></font></button>
            <!---这里可以写操作按钮等-->
<?php }?>
			
			<!---列表+分页-->		
			{include file="Pub/pubList" /}
			<!---列表+分页-->
<?php if ($full_page){?>
<!---尾部-->
{include file="Pub/pubTail" /}
{include file="Pub/footer" /}
<!---尾部-->
<?php }?>