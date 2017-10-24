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
	    "pay_code" => array("name" => "交易流水号","width"=>"100"),
        "businessname" => array("name" => "商家","width"=>"100"),
        "business_code" => array("name" => "平台号","width"=>"100"),
	    "customername" => array("name" => "支付用户","width"=>"100"),
	    "amount" => array("name" => "消费总金额","width"=>"100"),
	    "noinvamount" => array("name" => "不参与消费金额","width"=>"100"),
	    "mangercustomer" => array("name" => "相关操作人","width"=>"100"),
	    "addtime" => array("name" => "订单时间","width"=>"100"),
        "pay_type" => array("name" => "支付方式","width"=>"100"),
        //"payamount" => array("name" => "支付金额","width"=>"100"),
        "paytime" => array("name" => "支付时间","width"=>"100"),
	    //"act" => array("name" => "操作","width"=>"100"),
	);
	
	
	foreach ($pagelist as $key => $row) {
	    
	    /*$pagelist[$key]['act'][] = array("type" => "newpage", "name" => "button", "value" => "查看", "_width" => "500", "_height" => "400", "_title" => "查看", "_url" => "/Order/Index/lookOrder?id=".Encode($row['id']));
	    
	    $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);*/
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
	    "pay_code" => array("type" => "text", "name" => "订单编号", "value" => ""),
 	    "customername" => array("type" => "text", "name" => "支付用户手机号", "value" => ""),
        "businessname" => array("type" => "text", "name" => "商家名称", "value" => ""),
        "business_code" => array("type" => "text", "name" => "商家平台号", "value" => ""),
//  	    "realname" => array("type" => "text", "name" => "收货人", "value" => ""),
//  	    "mobile" => array("type" => "text", "name" => "收货人联系方式", "value" => ""),
 	    "addtime" => array("type" => "times", "name" => "下单时间", "value" => ""),
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