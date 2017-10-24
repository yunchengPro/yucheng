<?php
    $list_set = array(
        "checkbox" => array("name" => "选择"),
        "id" => array("name" => "序号"),
        "orderno" => array("name" => "充值订单号"),
        "mobile" => array("name" => "充值对象"),
        "pay_money" => array("name" => "支付金额"),
        "pay_status" => array("name" => "充值状态", "data_arr" => array("0" => "未支付", "1" => "已支付", "2" => "支付异常")),
        "addtime" => array("name" => "提交时间"),
    );
    
    $search = array(
        "pay_status" => array("type" => "select", "name" => "充值状态", "option" => ["0" => "未支付", "1" => "已支付", "2" => "支付异常"]),
        "orderno" => array("type" => "text", "name" => "订单号"),
        "mobile" => array("type" => "text", "name" => "充值对象"),
        "addtime" => array("type" => "times", "name" => "提交时间"),
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