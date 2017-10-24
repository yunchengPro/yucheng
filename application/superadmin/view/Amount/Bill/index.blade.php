<?php
use app\lib\Db;

	$title = $title;
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
        "date"=>array("name"=>"日期"),
        "app_type_name"=>array("name"=>"对账单类型"),
        "count"=>array("name"=>"总交易单数"),
        "amount"=>array("name"=>"总交易额"),
        "ch_amount"=>array("name"=>"手续费总金额"),
        "sys_count"=>array("name"=>"平台-总交易单数"),
        "sys_amount"=>array("name"=>"平台-总交易额"),
        "act"=>array("name"=>"操作"),
    );

    /*
    前端显示数据处理
    也可以在controller中处理
    */
    foreach($pagelist as $key=>$row){
        
        //$pagelist[$key]['businessid'] = Db::Model("BusBusiness")->getRow(['id'=>$row['businessid']],'businessname')['businessname'];
        if($row['app_type']==1 || $row['app_type']==2)
            $pagelist[$key]['act'][] = array("type"=>"popup_listpage","name"=>"button","name"=>"button","value"=>"对账明细","_width"=>"1000","_height"=>"700","_title"=>"对账明细","_url"=>"/Amount/Bill/list?billid=".Encode($row['id']));


        $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
	}

    
 	$search = array(
        "app_type" => array("type" => "select", "name" => "对账单类型", "value" => "","option"=>$app_type),
        "date" => array("type" => "text", "name" => "记录日期", "value" => ""),
        "addtime" => array("type" => "times", "name" => "提交时间", "value" => ""),
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