{include file="Pub/header" /}
<div class="page-container">

    <div id="tab-system" class="HuiTab">
        <div class="tabBar cl">
            <span><a href="/Customer/BullTalent/index" target="getname">列表</a></span>
            <span><a href="/Customer/BullTalent/waitAudit" target="getname">待审核</a></span>
<!--             <span><a href="/Customer/BullTalent/noPassList" target="getname">审核失败</a></span> -->
<!--             <span><a href="http://www.baidu.com" target="getname">黑名单</a></span> -->
        </div>
        <iframe scrolling="no" name="getname" frameborder="0" width="100%;" height="1000px" src="/Customer/BullTalent/index" ></iframe>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $.Huitab("#tab-system .tabBar span","#tab-system .tabCon","current","click","0");
    });
</script>
{include file="Pub/footer" /}

