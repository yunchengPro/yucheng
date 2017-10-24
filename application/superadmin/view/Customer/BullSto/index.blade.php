<?php
use app\lib\Db;

	$title = $title;
	
	$list_set = array(
	    "checkbox" => array("name" => "选择"),
	    "id" => array("name" => "序号"),
	    "businessname" => array("name" => "店铺名称"),
	    "mobile" => array("name"=>"手机号码"),
	    "categoryname" => array("name" => "所属分类"),
	    "area" => array("name" => "所在地区"),
	    "parentMobile" => array("name"=>"分享人"),
	    "istakeout"    => array("name"=>"配送状态",'data_arr'=>['1'=>"外卖","-1"=>"非外卖"]),
// 	    "licence_image" => array("name" =>                                                                                                                                                                                                                                                                                                  "营业执照图片",'thumb'=>['width'=>80,'height'=>80]),
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
		
        if($row['istakeout'] == 1){
        	$takeout = -1;
        	$takeout_str = '取消外卖';
        }else{
        	$takeout = 1;
        	$takeout_str = '设置外卖';
        }

        $pagelist[$key]['act'][] = array("type" => "confirm", "name" => "button", "value" => $takeout_str, "_width" => "500", "_height" => "200", "_title" => $takeout_str, "_url" => "/Customer/BullSto/setTakeount?id=".Encode($row['id'])."&takeout=".$takeout);


		$pagelist[$key]['act'][] = array("type" => "confirm", "name" => "button", "value" => "禁用", "_width" => "500", "_height" => "200", "_title" => "是否禁用该牛掌柜", "_url" => "/Customer/BullSto/enable?id=".Encode($row['id'])."&enable=-1");

		$pagelist[$key]['act'][]= array("type"=>"popup_listpage","name"=>"button","value"=>"粉丝", "_width"=>"1000","_height"=>"650","_title"=>"粉丝","_url"=>"/Customer/BullCus/userFans?role=5&id=".Encode($row['id']));
		
		$pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"升级vip", "_width"=>"500","_height"=>"200","_title"=>"是否升级成为vip牛掌柜","_url"=>"/Customer/BullSto/isvip?id=".Encode($row['id'])."&status=1");
		
		$pagelist[$key]['act'][] = array("type"=>"confirm","name"=>"button","value"=>"下架","_width"=>"500","_height"=>"200","_title"=>"是否下架该牛掌柜","_url"=>"/Customer/BullSto/ischeck?id=".Encode($row['id'])."&ischeck=-1");
		
	    $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
	}
	
    $search = array(
        "mobile" => array("type" => "text", "name" => "手机号码", "value" => ""),
        "businessname" => array("type"=>"text", "name"=>"店铺名称","value"=>""),
        "area_code"=>array("type"=>"area","name"=>"地区","class"=>"width250"),
//         "mobile" => array("type" => "text", "name" => "手机号码", "value" => ""),
//         "area_code" => array("type" => "select", "name" => "所在省市区", "option" => array("1" => "test1", "2" => "test2"), "value" => ""),
//         "addtime" => array("type" => "times", "name" => "添加时间"),
    );
    
    $button = array(
        "bt1" => array("type" => "popup_listpage","name"=>"button", "value" =>"添加牛掌柜", "_width"=>"650","_height"=>"550","_title"=>"添加牛掌柜","_url"=>"/Customer/BullSto/addBullSto?type=-1"),
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