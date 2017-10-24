<?php
	use app\lib\Db;

	$title = $title;
	
	$list_set = array(
	    // "checkbox" => array("name"=>"选择"),
	    "orderno"  => array("name"=>"订单号"), 
	    "mobile"  => array("name"=>"用户手机号码"), 
	    "cust_name"  => array("name"=>"用户名"), 
	    "count"  => array("name"=>"砖石数量"), 
	    "totalamount"  => array("name"=>"订单总额"), 
	    "addtime"  => array("name"=>"申请时间"), 
	    "orderstatus" => array("name"=>"订单状态","data_arr"=>['0'=>'待付款','1'=>'已付款','2'=>'订单完结','3'=>'已取消']),
	    "pay_voucher"=>array("name"=>"支付类型","data_arr"=>['1'=>'在线购买','2'=>'转账购买']),
	    "payamount"=>array("name"=>"支付总额"),
	    "businessid"=>array("name"=>"商家id"),
	    "paytime"=>array("name"=>"支付时间"),
	    // "act" => array("name" => "操作"),
	);
	
	foreach ($pagelist as $key => $row) {
		// if($row['orderstatus'] == 0){
		//     $pagelist[$key]['act'][] = array("type" => "confirm", "name" => "button", "value" => "确认付款", "_width" => "650", "_height" => "550", "_title" => "确认付款", "_url" => "/Order/Conorder/checkorder?id=".Encode($row['id'])."&status=1");
		//     $pagelist[$key]['act'][] = array("type" => "confirm", "name" => "button", "value" => "取消订单", "_width" => "650", "_height" => "550", "_title" => "取消订单", "_url" => "/Order/Conorder/checkorder?id=".Encode($row['id'])."&status=3");
		// }
	 //    $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
	}
	
    $search = array(
 		"orderno" => array("type"=>"text", "name"=>"订单号","value"=>""),
 		"cust_name" => array("type"=>"text", "name"=>"用户名","value"=>""),
 		"mobile" => array("type"=>"text", "name"=>"手机号码","value"=>""),
 		"orderstatus"=> array("type"=>"select", "name"=>"订单状态","value"=>"","option"=>['0'=>'待付款','1'=>'已付款','2'=>'订单完结','3'=>'取消']),
 		"addtime" => array("type"=>"times", "name"=>"申请时间","value"=>""),
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
