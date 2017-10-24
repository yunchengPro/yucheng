<?php
use app\lib\Db;

	$title = $title;
	
	$list_set = array(
		"num" => array("name"=>"序号"),
		"productname"=>array("name"=>"商品名称"),
		"businessname"=>array("name"=>"商家名称"),
		"supplyprice"=>array("name"=>"供货价"),
		"prouctprice"=>array("name"=>"活动价"),
		"saleprice"=>array("name"=>"销售价格"),
		"bullamount"=>array("name"=>"牛豆数"),
		"activeproductnum"=>array("name"=>"活动数量"),
		"productstorage"=>array("name"=>"活动库存"),
		"limitbuy"=>array("name"=>"限购数量(0为无限购)"),
		"starttime"=>array("name"=>"活动开始时间"),
		"endtime"=>array("name"=>"活动结束时间"),
		"enable"=>array("name"=>"商品状态","data_arr"=>["-1"=>"下架","1"=>"上架"]),
	    "act" => array("name" => "操作"),
	);

	foreach($pagelist as $key => $row) {
		if($row['enable'] == 1){
			// 上架状态出现 下架按钮
			$pagelist[$key]['act'][] = array("type"=>"confirm","name"=>"button","value"=>"下架","_width"=>"500","_height"=>"200","_title"=>"是否下架该活动商品","_url"=>"/Product/activityGoods/enable?enable=-1&id=".$row['id']);
		} else if($row['enable'] == -1) {
			// 下架状态出现 上架按钮、编辑按钮
			$pagelist[$key]['act'][] = array("type"=>"confirm","name"=>"button","value"=>"上架","_width"=>"500","_height"=>"200","_title"=>"是否上架该活动商品","_url"=>"/Product/activityGoods/enable?enable=1&id=".$row['id']);
			$pagelist[$key]['act'][] = array("type"=>"popup_listpage","name"=>"button","value"=>"编辑","_width"=>"1000","_height"=>"650","_title"=>"编辑活动商品","_url"=>"/Product/activityGoods/editInfo?id=".$row['id']);
			$pagelist[$key]['act'][] = array("type"=>"confirm","name"=>"button","value"=>"删除","_width"=>"500","_height"=>"200","_title"=>"是否删除该活动商品","_url"=>"/Product/activityGoods/enable?enable=2&id=".$row['id']);
		}
		$pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
	}
	
    $search = array(
        "activityStatus"=>array("type"=>"select","name"=>"活动状态","option"=>["0"=>"全部","1"=>"活动中","2"=>"活动未开始","3"=>"活动结束"]),
    	"productname"=>array("type"=>"text","name"=>"商品名称","value"=>""),
    	"businessname"=>array("type"=>"text","name"=>"商家名称","value"=>""),
    );
    
    $button = array(
    	"bt1" => array("type"=>"newpage","name"=>"button","value"=>"新增活动商品","_width"=>"500","_height"=>"400","_title"=>"新增活动商品","_url"=>"/Product/activityGoods/addActivityGoods"),
        // "bt1" => array("type" => "popup_listpage","name"=>"button", "value" =>"添加牛掌柜", "_width"=>"650","_height"=>"550","_title"=>"添加牛掌柜","_url"=>"/Customer/BullSto/addBullSto?type=-1"),
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