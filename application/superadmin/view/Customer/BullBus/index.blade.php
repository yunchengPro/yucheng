<?php
use app\lib\Db;

	$title = $title;
	
	$list_set = array(
	    "checkbox" => array("name" => "选择"),
	    //"id" => array("name" => "序号"),
	    "businessname" => array("name" => "店铺名称"),
	    "mobile" => array("name" => "手机号码"),
	    "realname" => array("name" => "负责人姓名"),
// 	    "areaname" => array("name" => "地区名称"),
	    "area" => array("name" => "地区名称"),
	    "parentMobile" => array("name" => "分享人"),
	    "addtime" => array("name" => "提交时间"),
	    "act" => array("name" => "操作"),
	);
	
	foreach ($pagelist as $key => $row) {
		
		
		if($row['enable'] != 1){
			$strEnable ='启用';
			$enable = 1;
		}else{
			$strEnable ='禁用';
			$enable = -1;
		}

	    $pagelist[$key]['act'][] = array("type" => "popup_listpage", "name" => "button", "value" => "查看", "_width" => "650", "_height" => "550", "_title" => "查看牛商信息", "_url" => "/Customer/BullBus/editBusInfo?id=".Encode($row['id']));
	    
	    $pagelist[$key]['act'][] = array("type"=>"popup_listpage","name"=>"button","value"=>"粉丝", "_width"=>"1000","_height"=>"650","_title"=>"粉丝","_url"=>"/Customer/BullCus/userFans?role=4&id=".Encode($row['id']));
	    
	    $pagelist[$key]['act'][] = array("type"=>"popup_listpage","name"=>"button","value"=>"修改售价", "_width"=>"500", "_height"=>"250", "_title"=>"修改售价方式", "_url"=>"/Customer/BullBus/editPriceType?id=".Encode($row['id']));
	    
	    $pagelist[$key]['act'][] = array("type"=>"confirm","name"=>"button","value"=>"升级vip", "_width"=>"500","_height"=>"200","_title"=>"是否升级成为vip牛商","_url"=>"/Customer/BullBus/isvip?id=".Encode($row['id'])."&status=1");
	    
	    $pagelist[$key]['act'][] = array("type" => "confirm", "name" => "button", "value" => $strEnable, "_width" => "500", "_height" => "200", "_title" => "是否禁用该牛商", "_url" => "/Customer/BullBus/enable?id=".Encode($row['id'])."&enable=".$enable);

	    $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
	}
	
    $search = array(
        "businessname" => array("type"=>"text", "name"=>"店铺名称","value"=>""),
        "mobile" => array("type" => "text", "name" => "手机号码", "value" => ""),
        //"area_code" => array("type" => "area", "name" => "所在省市区"),
        "area_code"=>array("type"=>"area","name"=>"地区","class"=>"width250"),
        "addtime" => array("type" => "times", "name" => "添加时间"),
    );
    
    // 自动生成按钮
    $button = array(
//         "bt1" => array("type" => "popup_listpage", "name" => "button", "value" => "快速添加供应商", "_width" => "550", "_height"=>"450", "_title"=>"快速添加供应商", "_url"=>"/Customer/BullBus/quickAddBullBus"),
            "bt1" => array("type"=>"popup_listpage","name"=>"button","value"=>"添加牛商","_width"=>"650","_height"=>"550","_title"=>"添加牛商","_url"=>"/Customer/BullBus/addBullBus?type=-1"),
        
        
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
                  
           <button _rel="newpage" id="goto2" style="display: none;" name="button" class="button " _title="添加产品" _width="500" _height="300" type="newpage" _url="">添加产品</button>
                  
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
<script type="text/javascript">
function goto2(url)
{
    
    $("#goto2").attr("_url", url);
    $("#goto2").click();
}
</script>