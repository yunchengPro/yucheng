<?php

    $list_set = [];

    foreach($fieldarr as $k=>$v){
        $list_set[$v['field']] = array("name" => $v['name'],"width"=>"100");
    }
    
    $search = array(
        /*"user" => array("type" => "text", "name" => "用户"),
        "flowid" => array("type" => "text", "name" => "流水号"),
        "orderno" => array("type" => "text", "name" => "订单号"),
        "flowtime" => array("type" => "times", "name" => "流水时间"),
        "table"=>array("type"=>"hidden","value"=>$table),
        "direction"=>array("type"=>"hidden","value"=>$direction),
        "userid"=>array("type"=>"hidden","value"=>$userid),*/
        "transaction_id" => array("type" => "text", "name" => "微信订单号"),
        "out_trade_no" => array("type" => "text", "name" => "商户订单号"),
        "billid"=>array("type"=>"hidden","value"=>$billid),
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