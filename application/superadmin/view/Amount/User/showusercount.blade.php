{include file="Pub/header" /}
<div class="page-container">

    <div id="tab-system" class="HuiTab">
        <div class="tabBar cl">
            <?php 
                foreach($urlarr as $k=>$v){
            ?>
                <span><a href="<?=$v['url']?>" target="getname"><?=$v['rolename']?></a></span>
            <?php }?>
        </div>
        <iframe scrolling="no" name="getname" frameborder="0" width="100%;" height="1000px" src="<?=$firsturl['url']?>" ></iframe>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $.Huitab("#tab-system .tabBar span","#tab-system .tabCon","current","click","0");
    });
</script>
{include file="Pub/footer" /}

