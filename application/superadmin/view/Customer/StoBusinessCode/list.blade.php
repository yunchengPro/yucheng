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
        "id"=>array("name"=>"ID"),
        "business_code"=>array("name"=>"平台号",),
        "businessname"=>array("name"=>"商家名称"),
        "isuse"=>array("name"=>"是否绑定", 'data_arr' => ['-1' => '未绑定', '1' => '已绑定']),
        "addtime"=>array("name"=>"添加时间"),
        "usetime"=>array("name"=>"使用时间"),
        "act"=>array("name"=>"操作"),
    );

    /*
    前端显示数据处理
    也可以在controller中处理
    */
    foreach($pagelist as $key=>$row){

        

        $pagelist[$key]['act'][]= array("type"=>"newpage","name"=>"button","value"=>"下载","_width"=>"500","_height"=>"400","_title"=>"下载","_url"=>"Customer/StoBusinessCode/getBusinessCodeQRcode?business_code=".$row['business_code']);


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
        "businessname"=>array("type"=>"text","name"=>"商家名称","value"=>""),
        "business_code"=>array("type"=>"text","name"=>"商家平台号","value"=>""),
        "isuse"=>array("type"=>"select","name"=>"是否绑定","value"=>"", 'option' => ['-1' => '未绑定', '1' => '已绑定']),
    
    );

    //自动生成按钮
    $button = array(
            "bt1"=>array("type"=>"popup_listpage","name"=>"button","value"=>"生成平台号","_width"=>"500","_height"=>"300","_title"=>'生成平台号',"_url"=>"/Customer/StoBusinessCode/choseCreatNum"),

            // "bt2"=>array("type"=>"confirm_all","name"=>"button","value"=>"批量通过","_width"=>"500","_height"=>"200","_title"=>'批量通过选中项?',"_url"=>"/Product/Index/passcheck?rel=check")
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
            <button _rel="newpage" id="goto2" style="display: none;" name="button" class="button " _title="创建平台号" _width="500" _height="300" type="newpage" _url="">创建平台号</button>

           
                  
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
    alert(url);
    $("#goto2").attr("_url", url);
    $("#goto2").click();
}
</script>
