<?php
use app\lib\Db;

	$title = $title;
	
	$list_set = array(
	    "checkbox" => array("name" => "选择"),
	    //"id" => array("name" => "序号"),
	    "type" => array("name" => "所属类型", "data_arr" => array("1" => "个人", "2" => "公司")),
	    "realname" => array("name" => "真实姓名"),
	    "mobile" => array("name" => "手机号码"),
	    "area" => array("name" => "地区名称"),
	    "join_area" => array("name" => "加盟地区"),
	    "act" => array("name" => "操作"),
	);
	
	foreach ($pagelist as $key => $row) {
// 	    $pagelist[$key]['act'][] = array("type" => "popup_listpage", "name" => "button", "value" => "查看", "_width" => "650", "_height" => "550", "_title" => "查看审核信息", "_url" => "/Customer/BullSto/editRecoStoInfo?id=".Encode($row['id']));

	    $pagelist[$key]['act'][] = array("type"=>"popup_listpage", "name"=>"button", "value"=>"编辑", "_width"=>"650", "_height"=>"550", "_title"=>"编辑代理信息", "_url"=>"/Customer/BullCounty/editCountyInfo?id=".Encode($row['id']));
	    $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
	}
	
    $search = array(
        "mobile" => array("type" => "text", "name" => "手机号码", "value" => ""),
        "join_code"=>array("type"=>"area","name"=>"加盟县区","class"=>"width250"),
//         "addtime" => array("type" => "times", "name" => "添加时间"),
    );
    
    $button = array(
        "btn1" => array("type" => "popup_listpage", "name" => "button", "value" => "添加孵化中心", "_width"=>"650", "_height"=>"550", "_title"=>"添加孵化中心", "_url"=>"/Customer/BullCounty/addCountyAgent"),
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