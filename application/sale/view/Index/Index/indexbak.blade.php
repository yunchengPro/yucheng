{include file="Pub/header" /}
    {include file="Pub/headernav" /}
    <div class="store-header am-g">
        <a href="store-intro.html" class="am-u-sm-2" ><img src="<?=$business['businesslogo']?>" width="40px" /></a>
        <span class="am-u-sm-8 store-title"><?=$business['businessname']?></span>
       <!--  <a href="login.html" class="am-u-sm-2 share">
            <div class="flex-column gray share-f">
                <i class="icon icon-share"></i>分享
            </div>
        </a> -->
    </div>
    <div data-am-widget="tabs" class="am-tabs am-tabs-d2">
        <ul class="am-tabs-nav am-cf">
            <li class="am-active">
                <a href="[data-tab-panel-0]">
                    店铺首页
                </a>
            </li>
            <li>
                <a href="[data-tab-panel-1]">
                    全部宝贝
                </a>
            </li>
        </ul>
        <div class="am-tabs-bd">
            <div data-tab-panel-0 class="am-tab-panel am-active">
                <div class="store-banner">
                <div class="am-slider am-slider-default" data-am-flexslider id="demo-slider-0">
                  <ul class="am-slides">
                    <li><img src="http://s.amazeui.org/media/i/demos/bing-1.jpg" /></li>
                    <li><img src="http://s.amazeui.org/media/i/demos/bing-2.jpg" /></li>
                  </ul>
                </div>
                   <!--  <img src="<?=$business['banner'][0][thumb]?>" /> -->
                </div>
                <?php 
                    $count = count($productlist['list']);
                    foreach($productlist['list'] as $k=>$v){?>
                    <?php 
                        if($k%2==0){
                    ?>
                    <div class="pro-container am-g">
                    <?php }?>
                        <div class="pro-box am-u-sm-6">
                            <a href="/index/index/goodsdetail?goodsid=<?=$v['productid']?>">
                                <img src="<?=$v['thumb']?>" />
                                <div class="pro-box-txt">
                                    <div class="text-overflow-2"><?=$v['productname']?></div>
                                    <div><span class="red"><?=$v['prouctprice']?></span><small>元</small></div>
                                    <div><span class="red"><?=$v['marketprice']?></span><small>元</small>+<span class="red"><?=$v['bullamount']?></span><small>牛豆</small></div>
                                </div>
                            </a>
                        </div>
                    <?php 
                        if($k%2==1 || $k==$count-1){
                    ?>
                    </div>
                    <?php }?>
                <?php } ?>
           

               <!--  <div class="pro-cate">商品分类</div> -->
            </div>
            <div data-tab-panel-1 class="am-tab-panel ">
                 <?php foreach($productlist['list'] as $k => $v){ ?>
                     <div class="pro-container " style="width: 50%;float: left;height: 400px;">
                        <div class="pro-box">
                            <a href="/index/index/goodsdetail?goodsid=<?=$v['productid']?>">
                                <img src="<?=$v['thumb']?>" />
                                <div class="pro-box-txt">
                                    <div class="text-overflow-2"><?=$v['productname']?></div>
                                    <div><span class="red"><?=$v['prouctprice']?></span><small>元</small></div>
                                    <div><span class="red"><?=$v['marketprice']?></span><small>元</small>+<span class="red"><?=$v['bullamount']?></span><small>牛豆</small></div>
                                </div>
                            </a>
                        </div>
                       
                    </div>
                <?php } ?>
               <!--  <div class="pro-container am-g">
                    <div class="pro-box am-u-sm-6">
                        <a href="pro-detail.html">
                            <img src="/mobile//mobile/img/pro3.png" />
                            <div class="pro-box-txt">
                                <div class="text-overflow-2">【新品上市】雅诗兰黛气垫BB霜保湿遮瑕</div>
                                <div><span class="red">429.00</span><small>元</small></div>
                                <div><span class="red">329.00</span><small>元</small>+<span class="red">100.00</span><small>牛豆</small></div>
                            </div>
                        </a>
                    </div>
                    <div class="pro-box am-u-sm-6">
                        <a href="pro-detail.html">
                            <img src="/mobile//mobile/img/pro4.png" />
                            <div class="pro-box-txt">
                                <div class="text-overflow-2">新品上市】雅诗兰黛气垫BB霜保湿遮瑕</div>
                                <div><span class="red">429.00</span><small>元</small></div>
                                <div><span class="red">329.00</span><small>元</small>+<span class="red">100.00</span><small>牛豆</small></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="pro-container am-g">
                    <div class="pro-box am-u-sm-6">
                        <a href="pro-detail.html">
                            <img src="/mobile//mobile/img/pro2.png" />
                            <div class="pro-box-txt">
                                <div class="text-overflow-2">SKII 神仙水 sk2精华液面部护肤套装保...</div>
                                <div><span class="red">590.00</span><small>元</small></div>
                                <div><span class="red">329.00</span><small>元</small>+<span class="red">100.00</span><small>牛豆</small></div>
                            </div>
                        </a>
                    </div>
                    <div class="pro-box am-u-sm-6">
                        <a href="pro-detail.html">
                            <img src="/mobile//mobile/img/pro1.png" />
                            <div class="pro-box-txt">
                                <div class="text-overflow-2">【新品上市】雅诗兰黛气垫BB霜保湿遮瑕</div>
                                <div><span class="red">429.00</span><small>元</small></div>
                                <div><span class="red">329.00</span><small>元</small>+<span class="red">100.00</span><small>牛豆</small></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="pro-container am-g">
                    <div class="pro-box am-u-sm-6">
                        <a href="pro-detail.html">
                            <img src="/mobile//mobile/img/pro3.png" />
                            <div class="pro-box-txt">
                                <div class="text-overflow-2">【新品上市】雅诗兰黛气垫BB霜保湿遮瑕</div>
                                <div><span class="red">429.00</span><small>元</small></div>
                                <div><span class="red">329.00</span><small>元</small>+<span class="red">100.00</span><small>牛豆</small></div>
                            </div>
                        </a>
                    </div>
                    <div class="pro-box am-u-sm-6">
                        <a href="pro-detail.html">
                            <img src="/mobile//mobile/img/pro4.png" />
                            <div class="pro-box-txt">
                                <div class="text-overflow-2">新品上市】雅诗兰黛气垫BB霜保湿遮瑕</div>
                                <div><span class="red">429.00</span><small>元</small></div>
                                <div><span class="red">329.00</span><small>元</small>+<span class="red">100.00</span><small>牛豆</small></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="pro-container am-g">
                    <div class="pro-box am-u-sm-6">
                        <a href="pro-detail.html">
                            <img src="/mobile//mobile/img/pro1.png" />
                            <div class="pro-box-txt">
                                <div class="text-overflow-2">【新品上市】雅诗兰黛气垫BB霜保湿遮瑕...</div>
                                <div><span class="red">429.00</span><small>元</small></div>
                                <div><span class="red">329.00</span><small>元</small>+<span class="red">100.00</span><small>牛豆</small></div>
                            </div>
                        </a>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default footer flex-center am-g login-note">
        <img src="/mobile/img/icon/LOGO2.png" class="am-u-sm-2" />
        <div class="footer-des flex-column am-u-sm-6">
            <div>你买单，我送钱</div>
            <div>赶快下载<label class="red"></label>手机客户端</div>
        </div>
        <div class="am-u-sm-4 text-right">
            <span class="download">点击下载</span>
        </div>
    </div>
{include file="Pub/footer" /}