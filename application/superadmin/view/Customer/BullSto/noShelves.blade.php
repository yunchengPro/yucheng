<?php
use app\lib\Db;

	$title = $title;
	
	$list_set = array(
	    "checkbox" => array("name" => "选择"),
	    "id" => array("name" => "序号"),
	    "businessname" => array("name" => "店铺名称"),
	    "mobile" => array("name" => "手机号码"),
	    "ischeck" => array("name" => "审核状态", "data_arr" => array("-1" => "未申请审核", "0" => "待审核", "1" => "审核通过", "2" => "未通过")),
	    "act" => array("name" => "操作"),
	);
	
	foreach ($pagelist as $key => $row) {
// 		$pagelist[$key]['act'][] = array("type" => "confirm", "name" => "button", "value" => "禁用", "_width" => "500", "_height" => "200", "_title" => "是否禁用该牛掌柜", "_url" => "/Customer/BullSto/enable?id=".Encode($row['id'])."&enable=-1");

// 		$pagelist[$key]['act'][]= array("type"=>"popup_listpage","name"=>"button","value"=>"粉丝", "_width"=>"1000","_height"=>"650","_title"=>"粉丝","_url"=>"/Customer/BullCus/userFans?role=5&id=".Encode($row['id']));
		
	    $pagelist[$key]['act'][]= array("type"=>"popup_listpage","name"=>"button","value"=>"查看", "_width"=>"1000","_height"=>"650","_title"=>"查看","_url"=>"/Customer/BullSto/examStoInfo?customerid=".Encode($row["customerid"]));
// 	    $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"上架", "_width"=>"1000","_height"=>"650","_title"=>"上架","_url"=>"/Customer/BullSto/updateCheck?customerid=".Encode($row['customerid'])."&status=1");
        $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"上架", "_width"=>"1000","_height"=>"650","_title"=>"上架","_url"=>"/Customer/BullSto/ischeck?id=".Encode($row['id'])."&ischeck=1");
// 	    $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"审核失败", "_width"=>"1000","_height"=>"650","_title"=>"审核失败","_url"=>"/Customer/BullSto/updateCheck?customerid=".Encode($row['customerid'])."&status=-1");
        $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"审核失败", "_width"=>"1000","_height"=>"650","_title"=>"审核失败","_url"=>"/Customer/BullSto/ischeck?id=".Encode($row['id'])."&ischeck=3");
	    $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
	}
	
    $search = array(
        "ischeck" => array("type" => "select", "option" => array("-1" => "未申请审核", "0" => "待审核", "1" => "审核通过", "2" => "未通过")),
        "businessname" => array("type"=>"text", "name"=>"店铺名称","value"=>""),
        "mobile" => array("type" => "text", "name" => "手机号码", "value" => ""),
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