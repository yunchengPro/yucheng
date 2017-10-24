<?php
	use app\lib\Db;

	$title = $title;
	
	$list_set = array(
	    "checkbox" => array("name"=>"选择"),
	    "role"  => array("name"=>"角色","data_arr"=>['1'=>'消费者','2'=>'商家']), 
	    "adddate"  => array("name"=>"分红日期"), 
	    "proportion"  => array("name"=>"分红比例"), 
	    "addtime"  => array("name"=>"添加时间"),
	    "act" => array("name" => "操作"),
	);
	
	foreach ($pagelist as $key => $row) {

		$pagelist[$key]['act'][] = array("type" => "popup_listpage", "name" => "button", "value" => "修改", "_width" => "650", "_height" => "550", "_title" => "修改", "_url" => "/System/Bonussetting/editcusbonus?id=".Encode($row['id']));
		
	    $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
	}
	
    $search = array(
 		
 		"addtime" => array("type"=>"times", "name"=>"添加时间","value"=>""),
    );

    $button = array(
       

        "bt1"=>array("type"=>"popup_listpage","name"=>"button","value"=>"添加分红指数","_width"=>"650","_height"=>"400","_title"=>'添加分红指数',"_url"=>"/System/Bonussetting/addcusbonus")
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
