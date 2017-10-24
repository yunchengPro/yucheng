<?php
    $list_set = array(
        "name"=> array("name" => "账户"),
        "cashamount" => array("name" => "现金余额"),
        "profitamount" => array("name" => "收益现金余额"),
        "bullamount" => array("name" => "牛豆"),
        "fut_cashamount" => array("name" => "待返还现金"),
        "fut_profitamount" => array("name" => "待返还收益现金"),
        "fut_bullamount" => array("name" => "待返牛豆"),
        "counteramount" => array("name" => "手续费余额"),
        "charitableamount" => array("name" => "慈善余额"),
    );
    
    $search = array(
        
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