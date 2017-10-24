<?php
	use app\lib\Db;

	$title = $title;
	
	$list_set = array(
	    "checkbox" => array("name"=>"选择"),
	    "orderno"  => array("name"=>"订单号"), 
	    "name"  => array("name"=>"姓名"), 
	    "mobile"  => array("name"=>"联系电话"), 
	    "area"  => array("name"=>"所在地区"), 
	    "address"  => array("name"=>"详细地址"), 
	    "addtime"  => array("name"=>"申请时间"), 
	    "join_type" => array("name"=>"加盟方式","data_arr"=>['1'=>'业绩扣除','2'=>'5折现金支付']),
	    "status" => array("name"=>"审核状态","data_arr"=>['1'=>'待审核','2'=>'审核通过','3'=>'审核失败']),
	    "act" => array("name" => "操作"),
	);
	
	foreach ($pagelist as $key => $row) {

		if($row['status'] == 1){
		    $pagelist[$key]['act'][] = array("type" => "popup_listpage", "name" => "button", "value" => "通过审核", "_width" => "650", "_height" => "550", "_title" => "通过审核", "_url" => "/Customer/Apply/applybuspass?id=".Encode($row['id'])."&status=2");
		    $pagelist[$key]['act'][] = array("type" => "popup_listpage", "name" => "button", "value" => "拒绝通过", "_width" => "650", "_height" => "550", "_title" => "拒绝通过", "_url" => "/Customer/Apply/applybuspass?id=".Encode($row['id'])."&status=3");
		}
		
	    $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
	}
	
    $search = array(
 		"name" => array("type"=>"text", "name"=>"姓名","value"=>""),
 		"mobile" => array("type"=>"text", "name"=>"联系电话","value"=>""),
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
