<?php
    $list_set = array(
        "checkbox" => array("name" => "选择"),
        "id" => array("name" => "序号"),
        "orderno" => array("name" => "充值订单"),
        "recharge_type" => array("name" => "充值类型", "data_arr" => array("1" => "现金", "2" => "绑定现金", "3" => "牛豆","4"=>"牛粮奖励金")),
        "mobile" => array("name" => "用户手机号码"),
        "pay_money" => array("name" => "支付金额"),
        "addtime" => array("name" => "提交时间"),
    );
    
    $search = array(
        "recharge_type" => array("type" => "select", "name" => "充值类型", "option" => array("1" => "现金", "2" => "绑定现金", "3" => "牛豆","4"=>"牛粮奖励金")),
        "orderno" => array("type" => "text", "name" => "充值订单", "value" => ""),
        "mobile" => array("type" => "text", "name" => "手机号码", "value" => ""),
        "addtime" => array("type" => "times", "name" => "提交时间", "value" => ""),
    );
    
    $button = array(
        "bt1" => array("type" => "popup_listpage","name"=>"button","value"=>"用户充值","_width"=>"650","_height"=>"550","_title"=>"用户充值","_url"=>"/Amount/Recharge/addSysRecharge"),
        "bt2" => array("type" => "popup_listpage","name"=>"button","value"=>"系统公司充值","_width"=>"650","_height"=>"550","_title"=>"系统公司充值","_url"=>"/Amount/Recharge/addSysComRecharge"),
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