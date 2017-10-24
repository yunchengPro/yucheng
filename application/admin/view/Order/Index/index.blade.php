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
	if($orderstatus == 1) {
    	$list_set = array(
    	    "checkbox" => array("name" => "选择"),
    	    "orderno" => array("name" => "订单编号"),
    	    "isabroad" => array("name" => "订单类型", "data_arr" => array("0" => "国内订单", "1"=>"海外购订单")),
            "businessname" => array("name" => "商家"),
//     	    "productname" => array("name" => "商品总金额"),
//     	    "totalamount" => array("name" => "订单总金额"),
    	    "cust_name" => array("name" => "买家"),
    	    "productamount" => array("name" => "商品总金额"),
    	    "totalamount" => array("name" => "订单总金额"),
    	    "bullamount" => array("name"=>"订单总牛豆数"),
    	    "actualfreight" => array("name"=>"运费"),
    	    //"prouctprice" => array("name" => "商品价格"),
            "payInfo"=> array("name" => "支付状态"),
    	    "orderstatus" => array("name" => "订单状态", "data_arr" => array("0" => "待付款", "1" => "已付款待发货", "2" => "已发货", "3" => "确认收货", "4" => "订单完结", "5" => "取消")),
    	    "returnstatus" => array("name" => "退款状态", "data_arr" => array("0" => "无退款", "1" => "退款中", "2" => "退款完成")),
    	    "addtime" => array("name" => "下单时间"),
    	    "act" => array("name" => "操作"),
    	);
	} else {
	    $list_set = array(
	        "checkbox" => array("name" => "选择"),
	        "orderno" => array("name" => "订单编号"),
	        "isabroad" => array("name" => "订单类型", "data_arr" => array("0" => "国内订单", "1"=>"海外购订单")),
            "businessname" => array("name" => "商家"),
//             "productname" => array("name" => "商品总金额"),
//             "totalamount" => array("name" => "订单总金额"),
	        "cust_name" => array("name" => "买家"),
	        "productamount" => array("name" => "商品总金额"),
	        "totalamount" => array("name" => "订单总金额"),
	        "bullamount" => array("name"=>"订单总牛豆数"),
	        "actualfreight" => array("name"=>"运费"),
            //"prouctprice" => array("name" => "商品价格"),
            "payInfo"=> array("name" => "支付状态"),
	        "orderstatus" => array("name" => "订单状态", "data_arr" => array("0" => "待付款", "1" => "已付款待发货", "2" => "已发货", "3" => "确认收货", "4" => "订单完结", "5" => "取消")),
	        "addtime" => array("name" => "下单时间"),
	        "act" => array("name" => "操作"),
	    );
	}
	
	foreach ($pagelist as $key => $row) {
	    $pagelist[$key]['productamount'] = DePrice($pagelist[$key]['productamount']);
	    $pagelist[$key]['bullamount'] = DePrice($pagelist[$key]['bullamount']);
	    $pagelist[$key]['totalamount'] = DePrice($pagelist[$key]['totalamount']);
	    $pagelist[$key]['actualfreight'] = DePrice($pagelist[$key]['actualfreight']);
        
	    $pagelist[$key]['act'][] = array("type" => "newpage", "name" => "button", "value" => "查看", "_width" => "500", "_height" => "400", "_title" => "查看", "_url" => "/Order/Index/lookOrder?id=".Encode($row['id']));
	    if($row['orderstatus'] == 2 || $row['orderstatus'] == 3 || $row['orderstatus'] == 4) {
	        $pagelist[$key]['act'][] = array("type" => "popup_listpage", "name" => "button", "value" => "查看物流信息", "_width" => "1000", "_height" => "400", "_title" => "查看物流信息", "_url" => "/Order/Index/lookLogisticsInfo?id=".Encode($row['id']));
	       if($row['orderstatus'] == 2) {
	           $pagelist[$key]['act'][] = array("type" => "popup_listpage", "name" => "button", "value" => "修改快递", "_width" => "1000", "_height" => "400", "_title" => "修改快递", "_url" => "/Order/Index/updateExpressInfo?id=".Encode($row['logisticsid']));
	       }
	    }
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
    if($type == 4) {
	    // 添加是否评论筛选
	    $search = array(
	        "evaluate" => array("type"=>"select","name"=>"是否评价","option"=>["0"=>"全部","-1"=>"未评价","1"=>"已评价"],"value"=>0),
	        "orderno" => array("type" => "text", "name" => "订单编号", "value" => ""),
	        "isabroad" => array("type" => "select", "name" => "订单类型", "option"=>["-1"=>"全部","0"=>"国内订单","2"=>"海外购订单"],"value"=>-1),
	        "cust_name" => array("type" => "text", "name" => "买家", "value" => ""),
	        "mobile"=>array("type"=>"text","name"=>"买家手机号码","value"=>""),
	        "addtime" => array("type" => "times", "name" => "下单时间", "value" => ""),
	        "type" => array("type" => "hidden", "name" => "", "value" => $type),
	    );
	} else {
     	$search = array(
    	    "orderno" => array("type" => "text", "name" => "订单编号", "value" => ""),
     	    "isabroad" => array("type" => "select", "name" => "订单类型", "option"=>["-1"=>"全部","0"=>"国内订单","2"=>"海外购订单"],"value"=>-1),
     	    "cust_name" => array("type" => "text", "name" => "买家", "value" => ""),
     	    "mobile"=>array("type"=>"text","name"=>"买家手机号码","value"=>""),
     	    "addtime" => array("type" => "times", "name" => "下单时间", "value" => ""),
            "type" => array("type" => "hidden", "name" => "", "value" => $type),
    	);
	}
    
    // if($type == 2 || $type == 1){
        $export_url = "/order/index/export?type=".$type; //导出数据的action
        //自动生成按钮
    // }

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