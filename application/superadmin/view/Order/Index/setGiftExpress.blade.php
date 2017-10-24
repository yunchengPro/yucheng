{include file="Pub/header" /}
<style type="text/css">
    .page-container{
        margin-bottom: 150px;
    }
    .addOrder{ 
        padding-top: 0;
    }
    .order_detail_title{
        margin-top:0;
        font-size: 18px;

    }
    .addOrder .agintsLi li {
     display: block; 
    margin-right: 40px;
}
   
</style>
<div class="page-container addOrder">
	<div class="agintsLi">
		<form action="<?php echo $actionUrl; ?>" name="expressForm" id="expressForm" method="post">
    		<ul>
    			<li>
                    <span>快递公司：</span><span class="pos-r"><input type="text" class="input-text width250 keyword" name="express_name" id="express_name">
                     <ul class="keyword-list">
                     	<?php if(!empty($expressList)) {?>
                     		<?php foreach ($expressList as $k => $express) {?>
                     			<li><?php echo $express;?></li>
                     		<?php }?>
                     	<?php }?>
                        </ul>
                        </span>
                </li>
    		</ul>
    		<ul>
    			<li><span>快递单号：</span><input type="text" class="input-text width250 " name="express_no" id="express_no"></li>
    		</ul>
    		<ul>
    			<li><span><input type="submit" class="btn btn-danger" value="提交" id="submit"></span></li>
    		</ul>
    	</form>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$("#submit").on("click", function(){
		var express_name = $("#express_name").val();
		var express_no = $("#express_no").val();

		if(express_name == '' || express_no == '') {
			alert("请填写完整物流信息");
			return false;
		}
	});
});
</script>
{include file="Pub/footer" /}
<script type="text/javascript" src="/js/keywords.js"></script>