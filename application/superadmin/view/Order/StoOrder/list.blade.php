<?php
use app\lib\Model;

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
	    //"checkbox" => array("name" => "选择"),
	    "orderno" => array("name" => "订单编号","width"=>"100"),
        "businessname" => array("name" => "商家名称","width"=>"100"),
        "customer_name" => array("name" => "支付人","width"=>"100"),
        "realname" => array("name" => "收货人姓名","width"=>"100"),
        "mobile" => array("name" => "收货人手机","width"=>"100"),
        "adress" => array("name" => "收货人地址","width"=>"100"),
        "productamount" => array("name" => "商品总额","width"=>"100"),
        "orderstatus" => array("name" => "订单状态","width"=>"100"),
        "actualfreight" => array("name" => "配送费","width"=>"100"),
	    "productcount" => array("name" => "商品数量","width"=>"100"),
	    "totalamount" => array("name" => "订单总额","width"=>"100"),
        "return_status" => array("name" => "退款状态","width"=>"100"),
	    "addtime" => array("name" => "下单时间","width"=>"100"),
        //"payamount" => array("name" => "支付金额","width"=>"100"),
	    "act" => array("name" => "操作","width"=>"100"),
	);
	
	
	foreach ($pagelist as $key => $row) {
	    
	    $pagelist[$key]['act'][] = array("type" => "newpage", "name" => "button", "value" => "查看", "_width" => "500", "_height" => "400", "_title" => "查看", "_url" => "/Order/StoOrder/lookOrder?id=".Encode($row['id']));
	    
        if($row['orderstatus'] == '订单完结')
            $pagelist[$key]['act'][] = array("type" => "confirm", "name" => "button", "value" => "退款", "_width" => "500", "_height" => "400", "_title" => "退单后款项将无法退回，确定执行退单操作？", "_url" => "/Order/StoOrder/returnOrder?orderno=".$row['orderno']);
        
	    $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
	}

    /*
    前端显示数据处理
    也可以在controller中处理
    */

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
	    "orderno" => array("type" => "text", "name" => "订单编号", "value" => ""),
 	    "businessname" => array("type" => "text", "name" => "商家名称", "value" => ""),
        "mobile" => array("type" => "text", "name" => "支付用户手机号", "value" => ""),
        "addtime" => array("type" => "times", "name" => "下单时间", "value" => ""),
        "orderstatus" => array("type"=>"select","name"=>"订单状态","value"=>"", 'option' => $orderstatuss),
        "return_status" => array("type"=>"select","name"=>"退款状态","value"=>"", 'option' => $return_statuss),

	);

    //自动生成按钮

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