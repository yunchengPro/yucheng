{include file="Pub/header" /}
<div class="page-container">
	<div id="tab-system" class="HuiTab">
		<div class="tabBar cl">
			<span><a href="/Amount/Index/flowCus?customerid={$id}&type=1" target="getname">现金流水</a></span>
			<span><a href="/Amount/Index/flowCus?customerid={$id}&type=2" target="getname">收益现金流水</a></span>
			<span><a href="/Amount/Index/flowCus?customerid={$id}&type=3" target="getname">牛豆流水</a></span>
			<span><a href="/Amount/Index/flowCus?customerid={$id}&type=4" target="getname">企业账户流水</a></span>
		</div>
		<iframe scrolling="no" name="getname" frameborder="0" width="100%;" height="1000px" src="/Amount/Index/flowCus?customerid={$id}&type=1" ></iframe>
	</div>
</div>
<script type="text/javascript">
    $(function(){
        $.Huitab("#tab-system .tabBar span","#tab-system .tabCon","current","click","0");
    });
</script>
{include file="Pub/footer" /}
