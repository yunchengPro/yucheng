<?php
    $list_set = array(
        
        "flowid" => array("name" => "流水号","width"=>"100"),
        "user" => array("name" => "用户","width"=>"100"),
        "flowtype" => array("name" => "流水类型","width"=>"100"),
        "direction" => array("name" => "收入|支出","width"=>"100"),
        "amount" => array("name" => "金额","width"=>"100","sort"=>true),
        "flowtime" => array("name" => "流水时间","width"=>"100","sort"=>true),
        "orderno" => array("name" => "订单号","width"=>"100"),
    );
    
    $search = array(
        "user" => array("type" => "text", "name" => "用户"),
        "flowid" => array("type" => "text", "name" => "流水号"),
        "orderno" => array("type" => "text", "name" => "订单号"),
        "flowtime" => array("type" => "times", "name" => "流水时间"),
        "table"=>array("type"=>"hidden","value"=>$table),
        "direction"=>array("type"=>"hidden","value"=>$direction),
        "userid"=>array("type"=>"hidden","value"=>$userid),
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