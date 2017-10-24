<?php
use app\lib\Db;
$title = $title;

    $list_set = array(
        "orderno" => array("name" => "订单号"),
        "flowname" => array("name" => "流水明细"),
        "direction" => array("name" => "流水类型", "data_arr"=>array("1"=>"收入","2"=>"支出")),
        "amount" => array("name" => "流水金额"),
        "fromuserInfo" => array("name" => "流水来源用户"),
        "flowtime" => array("name" => "流水时间"),
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
            <!-- 当前页总收入和支出 该用户总的收入和支出 -->   
            【总统计】收入：<?=$shouru?>；支出：<?=$zhichu?>；收入-支出=：<?=$amount_diff?>
            <br>
            【当前页统计】收入：<?=$shouru_page?>；支出：<?=$zhichu_page?>；收入-支出：<?=$amount_diff_page?>
			
			<!---列表+分页-->		
			{include file="Pub/pubList" /}
			<!---列表+分页-->
<?php if ($full_page){?>
<!---尾部-->
{include file="Pub/pubTail" /}
{include file="Pub/footer" /}
<!---尾部-->
<?php }?>