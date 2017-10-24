{include file="Pub/header" /}

<header class="navbar-wrapper">

    <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl">
            <a class="logo navbar-logo f-l mr-10 mt-10" href="javascript:void(0)">
                <!-- <img class="logo navbar-logo f-l mr-10 hidden-xs" src="./image/logo.png" alt="" > -->
                <img src="/newui/static/h-ui.admin/images/nnh/logo-white.png"><span class="sys-name">供应商管理系统</span>
            </a>
            <!--   <h4 class="nav-tit">超级管理系统</h4> -->
             <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
            <nav class="nav navbar-nav menu-nav">
                <ul class="cl" id="tab_ctrl">
                    <li url="#" itype="" iname="">
                        <a href="#" _id="" onclick="" class=""></a>
                    </li>
                </ul>
            </nav>
            <nav id="Hui-userbar" class="navbar-userbar">
                <span>欢迎您:</span>
                <span class="dropDown dropDown_hover"> <a class="dropDown_A" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <span class="user-name" title="<?php echo $userData['username']; ?>"><?php echo $userData['username']; ?></span><i class="Hui-iconfont" style="position: static;margin-left: 10px;">&#xe6d5;</i></a>
             <ul style="line-height: 40px;" class="dropDown-menu menu radius box-shadow">
                    <li class=""><a href="/login">切换账户</a></li>
                     <li class=""><a _rel="popup_listpage" id="button" name="button"  _title="修改密码" title="修改密码" _url="/Login/Index/setAdminPwd" _width="450" _height="300">修改密码</a></li>
                    <li><a href="/login/index/loginOut">退出</a></li>
                </ul>
            </span>
                <!--<ul class="cl">
            
            <li>你好</li>
            <li class="dropDown dropDown_hover" style="height: 67px;"> <a href="#" class="dropDown_A" style="padding-left:5px;padding-right:0px;height: 67px;">
                <span style="display:inline-block;width:79px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden;">admin</span>
                <i class="Hui-iconfont"></i></a>
                <ul style="width: 79px;line-height: 40px;" class="dropDown-menu menu radius box-shadow">
                     <li class="open"><a href="/login">切换账户</a></li>
                    <li><a href="/login/index/loginOut">退出</a></li>
                </ul>
            </li>
         </ul>-->
            </nav>

        </div>
    </div>

</header>
 <aside class="Hui-aside">
    <input runat="server" id="divScrollValue" type="hidden" value="" />
    <div class="menu_dropdown bk_2">
        <?php  
            $iframeUrl = '';
            $iframName = '';
            foreach($menuData as $key => $vo){ //print_r($vo['childMenu']);  
                if($iframeUrl =='' && $iframName =='' && $vo['url'] != '/' && $vo['url'] != ''){
                   $iframeUrl = $vo['url']; 
                   $iframName = $vo['menuname'];   
                }
            ?>
                <dl class="menu-article">
                    <dt <?php if($key ==0){  ?>  class="selected" <?php } ?>  <?php echo $vo['url']!='/'&&$vo['url']!=''?"class='dt_click_tab' data-title='".$vo['menuname']."' _href='".$vo['url']."'":"";?>  >
                        <i class="icon <?=$vo['class']!=''?$vo['class']:'icon-log'?>">&nbsp;&nbsp;</i>
                        <span><?=$vo['menuname']?></span>
                    </dt>

                    <?php if(!empty($vo['childMenu'])){ ?>
                    <dd <?php if($key ==0){  ?>  style="display: block;" <?php } ?> >
                        <ul>
                        <?php foreach ($vo['childMenu'] as $k => $v) {  
                        if($iframeUrl =='' && $iframName =='' && $v['url'] != '/' && $v['url'] != ''){
                           $iframeUrl = $v['url']; 
                           $iframName = $v['menuname'];   
                        } 
                    ?>
                             <li><a  <?php if($key ==0&&$k ==0){  ?>  class="clickOn" <?php } ?> _href="<?=$v['url']?>" data-title="<?=$v['menuname']?>" href="javascript:void(0);"><?=$v['menuname']?></a></li>
                        <?php } ?>
                        </ul>
                    </dd>
                    <?php } ?>
                </dl>
            <?php } ?>
    </div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<section class="Hui-article-box">
    <div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
        <div class="Hui-tabNav-wp">
            <ul id="min_title_list" class="acrossTab cl">
                <li class="active"><span title="<?=$iframName?>" data-href="<?=$iframeUrl?>"><?=$iframName?></span><em></em></li>
            </ul>
        </div>
        <div class="Hui-tabNav-more btn-group">
            <a id="js-tabNav-prev" class="btn radius btn-default size-S pos-r" href="javascript:;">
                <i class="Hui-iconfont">&#xe6d4;</i>
            </a>
            <a id="js-tabNav-next" class="btn radius btn-default size-S pos-r" href="javascript:;">
                <i class="Hui-iconfont">&#xe6d7;</i>
            </a>
        </div>
    </div>
    <div id="iframe_box" class="Hui-article">
        <div class="show_iframe">
            <div style="display:none" class="loading"></div>
            <iframe scrolling="yes" frameborder="0" src="<?=$iframeUrl?>"></iframe>
        </div>
    </div>
</section>
<script>
    //顶部页面标识(---不要删除---)
    var TOP_FLAG=1;
    $(function(){
        //alert(122);
        $(".dt_click_tab").each(function(index,obj){
            //alert($(obj).attr("_href"));
            if($(obj).attr("_href")!='')
                $(obj).bind("click",function(){
                    Hui_admin_tab($(this));
                });
        });

        $.Huifold(".Hui-aside .menu_dropdown dl dt",".Hui-aside .menu_dropdown dl dd","fast",1,"click");
    });
</script>
{include file="Pub/footer" /}