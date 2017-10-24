<?php
use app\lib\Db;

	$title = $title;
	
	$list_set = array(
	    "checkbox" => array("name" => "选择"),
	    "id" => array("name" => "序号"),
	    "businessname" => array("name" => "店铺名称"),
	    "categoryname" => array("name" => "所属分类"),
	    "area" => array("name" => "所在地区"),
	    "licence_image" => array("name" => "营业执照图片",'thumb'=>['width'=>80,'height'=>80]),
	    "act" => array("name" => "操作"),
	);
	
	foreach ($pagelist as $key => $row) {
// 		if($row['enable'] != 1){
// 			$strEnable ='启用';
// 			$enable = 1;
// 		}else{
// 			$strEnable ='禁用';
// 			$enable = -1;
// 		}
        
		$pagelist[$key]['act'][] = ["type"=>"popup_listpage","name"=>"button","value"=>"设置分润对象","_width"=>"850","_height"=>"800","_title"=>"设置分润对象","_url"=>"/Amount/ProfitSetting/stosetting?id=".Encode($row['id'])];
			
	    $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
	}
	
    $search = array(
        "mobile" => array("type" => "text", "name" => "手机号码", "value" => ""),
        "businessname" => array("type"=>"text", "name"=>"店铺名称","value"=>""),
//         "mobile" => array("type" => "text", "name" => "手机号码", "value" => ""),
//         "area_code" => array("type" => "select", "name" => "所在省市区", "option" => array("1" => "test1", "2" => "test2"), "value" => ""),
//         "addtime" => array("type" => "times", "name" => "添加时间"),
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