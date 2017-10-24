<?php
use app\lib\Db;

	$title = $title;
	
	$list_set = array(
	    "checkbox" => array("name" => "选择"),
	    "id" => array("name" => "序号"),
	    "sto_name" => array("name" => "店铺名称"),
	    "mobile" => array("name" => "手机号码"),
	    "areaname" => array("name" => "地区名称"),
	    "instroducermobile" => array("name" => "引荐人手机号码"),
	    "addtime" => array("name" => "提交时间"),
	    "act" => array("name" => "操作"),
	);
	
	foreach ($pagelist as $key => $row) {
	    $pagelist[$key]['act'][] = array("type" => "popup_listpage", "name" => "button", "value" => "编辑", "_width" => "650", "_height" => "550", "_title" => "编辑审核信息", "_url" => "/Customer/BullSto/editRecoStoInfo?id=".Encode($row['id']));
	    $pagelist[$key]['act'][] = array("type" => "confirm", "name" => "button", "value" => "审核通过", "_width" => "500", "_height" => "200", "_title" => "是否审核通过该推荐信息", "_url" => "/Customer/BullSto/updateExamStatus?status=2&id=".Encode($row['id']));
	    $pagelist[$key]['act'][] = array("type" => "popup_listpage", "name" => "button", "value" => "审核失败", "_width" => "650", "_height" => "550", "_title" => "是否拒绝该审核推荐信息", "_url" => "/Customer/BullSto/examFail?status=3&id=".Encode($row['id']));
	    $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
	}
	
    $search = array(
        "sto_name" => array("type"=>"text", "name"=>"店铺名称","value"=>""),
        "mobile" => array("type" => "text", "name" => "手机号码", "value" => ""),
//         "area_code" => array("type" => "select", "name" => "所在省市区", "option" => array("1" => "test1", "2" => "test2"), "value" => ""),
        "addtime" => array("type" => "times", "name" => "添加时间"),
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