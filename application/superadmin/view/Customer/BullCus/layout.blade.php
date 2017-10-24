{include file="Pub/header" /}
<div class="page-container">

    <div id="tab-system" class="HuiTab">
        <div class="tabBar cl">
            <span><a href="/Customer/BullCus/index" target="getname">列表</a></span>
            <span><a href="/Customer/BullCus/blacklist" target="getname">黑名单</a></span>
        </div>
        <iframe scrolling="no" name="getname" frameborder="0" width="100%;" height="1000px" src="/Customer/BullCus/index" ></iframe>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $.Huitab("#tab-system .tabBar span","#tab-system .tabCon","current","click","0");
    });
</script>
{include file="Pub/footer" /}

