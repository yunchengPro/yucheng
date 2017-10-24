{include file="Pub/header" /}
<?php if ($full_page){?>
<style type="text/css">
    .item-right button{
        float: right;
    }
    .goodsInfo{
        padding-top: 20px;
    }
    span.c-999 {
        font-weight: bold;
        font-size: 24px;
    }
</style>
<div class="page-container">
        <div class="page-container addOrder">
            <div class="agintsLi">
                <ul>
                    <li><span>营业现金余额：</span><span class="c-999">￥<?=$cashamount?></span></li>
                    <li><span>营业现金：</span><span class="c-999">￥<?=$sumProductAmount?></span></li>
                    <li><span>待收现金：</span><span class="c-999">￥<?=$sumWillProductAmount?></span></li>
                </ul>
                <ul>
                    <li><span>营业牛豆余额：</span><span class="c-999">￥<?=$bullamount?></span></li>
                    <li><span>营业牛豆：</span><span class="c-999">￥<?=$sumBullAmount?></span></li>
                    <li><span>待收牛豆：</span><span class="c-999">￥<?=$sumWillBullAmount?></span></li>
                </ul>
            </div>
        </div>  
    
         <!-- <iframe scrolling="no" name="getname" frameborder="0" width="100%;" height="700px" src="/Achievement/index/my_income" ></iframe> -->
    </div>
</div>
<?php } ?>
<?php
use app\lib\Db;

    $title = $title;
    /*
    设置列表，表头
    "mobile"=>array("name"=>"所属分会"),"sort"=>true,"width"=>"120","head_str"=>"width='30' nowrap","align"=>"left"),
    mobile 显示的字段
    name     表头名称
    sort     是否排序，默认false 不排序
    width    列宽度
    head_str 表头的其他信息
    data_str 数据其他信息
    align    设置内容项位置：left,center,right 默认是居中center
    nowrap   设置表格内容过长时是否进行缩进，默认false
    */
    $list_set = array(
        "checkbox"=>array("name"=>"选择"),
        "orderno"=>array("name"=>"订单号"),
        "type"=>array("name"=>"类型",),
        "productcount"=>array("name"=>"商品总数"),
        "productamount"=>array("name"=>"商品总金额"),
        "bullamount"=>array("name"=>"商品总牛币数"),
        "totalamount"=>array("name"=>"订单总金额"),
        "actualfreight"=>array("name"=>"实际运费"),
        "act"=>array("name"=>"操作"),
    );

    /*
    前端显示数据处理
    也可以在controller中处理
    */
    foreach($pagelist as $key=>$row){
        
     

        $pagelist[$key]['act'][]= array("type"=>"newpage","name"=>"button","value"=>"查看","_width"=>"500","_height"=>"400","_title"=>"查看<font color='green'>".$row['orderno']."</font>","_url" => "/Order/Index/lookOrder?id=".Encode($row['id']));


        $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
    }

    /*
    设置搜索项
    "keyword"=>array("type"=>"text","name"=>"商品名称/关键字","value"=>$this->_request->getParam("keyword")),
    "keyword" 表示搜索项的名称
    "type"    类型：text | select | time | times 
    "value"   初始化值，之后的值自动获取到，但是controller层需要把数据提交过来
    "name"    中文说明
    "option"  设置select类型的下拉选项
    "dateFmt" 1-年月日（默认） 2-年月日时分秒 3-年月 4-年  用于时间控件
    
    说明：times类型下，表单的命名自动在前面加"start_"和"end_"   
          显示的值  自动获取 
    */
    $search = array(
        "orderno"=>array("type"=>"text","name"=>"订单号","value"=>"")
    );

    //自动生成按钮
    $button = array(
          
        );

?> 

<?php if ($full_page){?>

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
    //alert(url);
    $("#goto2").attr("_url", url);
    $("#goto2").click();
}
</script>
{include file="Pub/footer" /}