{include file="Pub/header" /}
<div class="page-container">

    <div id="tab-system" class="HuiTab">
        <div class="tabBar cl">
            <span><a href="/Customer/BullBus/index" target="getname">已审核</a></span>
            <span><a href="/Customer/BullBus/waitAudit" target="getname">待审核</a></span>
            <span><a href="/Customer/BullBus/examFailList" target="getname">审核失败</a></span>
            <span><a href="/Customer/BullBus/blackList" target="getname">黑名单列表</a></span>
        </div>
        <iframe scrolling="no" name="getname" frameborder="0" width="100%;" height="1000px" src="/Customer/BullBus/index" ></iframe>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $.Huitab("#tab-system .tabBar span","#tab-system .tabCon","current","click","0");
    });
</script>
{include file="Pub/footer" /}

