<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$category_name?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    {include file="Pub/assetcss" /}
    {include file="Pub/assetjs" /}
    <style>
        body {
            background: #EEEEEE;
        }
         .ellipsis{
            overflow: hidden;
        text-overflow:ellipsis;
        white-space: nowrap;
        }
        .topic-banner {
            margin-bottom: 10px;
        }
        
        .one-topic {
            margin-bottom: 9px;
            background: #FFFFFF;
        }
        
        .one-topic:last-child {
            margin-bottom: 0;
        }
        
        .topic-list {
            width: 100%;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            -ms-scroll-chaining: chained;
            -webkit-transform: translateZ(0);
            overflow: auto;
            background: #FFFFFF;
            font-size: 0;
            padding: 0 10px;
        }
        
        .one-good {
            display: inline-block;
            width: 40%;
            margin-right: 10px;
        }
        
        .one-good img,
        .one-good-2 img {
            width: 100%;
            margin-bottom: .5rem;
        }
        
        .one-good .g-price,
        .one-good-2 .g-price {
            margin-top: .5rem;
            color: #333;
            font-size: 1.4rem;
            margin: 0;
            text-align: center;
            margin-bottom: .2rem;
        }
        
        .one-good .g-name,
        .one-good-2 .g-name {
            color: #333;
            font-size: 1.2rem;
            margin: 0;
            text-align: center;
            margin-bottom: .8rem;
            /*white-space: normal;*/

        }
        
        .topic-list-2 {
            padding: 0 .5rem;
        }
        
        .one-topic .am-u-sm-4 {
            padding: .4rem;
        }
        
        .one-good-3 {
            border-bottom: 1px solid #EEEEEE;
            padding: 1rem;
            position: relative;
            min-height: 120px;
        }
        
        .one-good-3 img {}
        
        .one-good-3 .g-price {
                color: #333;
            font-size: 1.4rem;
            margin: 0;
            /* margin-bottom: .2rem; */
            position: absolute;
            bottom: 1rem;
        }
        
        .one-good-3 .g-name {
            color: #333;
            font-size: 1.4rem;
            margin-bottom: 0.25rem;
            display: -webkit-box;
            overflow: hidden;
            text-overflow: ellipsis;
            -webkit-box-orient: vertical;
             -webkit-line-clamp: 2;
        }
        
        .one-good-3 .g-desc {
            color: #999;
            font-size: 1.2rem;
            margin-bottom: 0rem;
            margin-top: 0;
        }
        
        .one-good-3 .am-u-sm-8 {
            position: absolute;
            left: 33.333%;
            bottom: 0;
            top: 0;
            padding: 1rem 1rem 1rem 1.5rem;
        }
        
        .one-good-3 .one-topic .am-u-sm-4 {
            padding: 0 10px;
        }
        
        .one-topic .one-good-3 .am-u-sm-4 {
            padding: 0rem;
        }

        #loading{background: transparent;position: fixed;display: none;bottom:0; text-align: center; width: 100%;line-height: 2rem; z-index: 10;}
        #loading p{ width: 100%; background: url(<?=$publicDomain?>/mobile/img/loading.gif) 36% center no-repeat; color: #666; font-size: 1.2rem;margin-bottom: 0;}
        .no-data{display:none;text-align:center;color:#f13437;margin-top: .15rem;margin-bottom: 0;}

    </style>
</head>

<body>
 <!--    <header data-am-widget="header" class="am-header am-header-default am-header-fixed">
        <div class="am-header-left am-header-nav">
            <a href="pro-detail.html">
                <i class="am-icon-angle-left am-icon-md gray"></i>
            </a>
        </div>
        <h1 class="am-header-title">
            <a href="#title-link">家居家纺</a>
        </h1>
    </header> -->
    
    <div class="topic-wrap">
        <!--毛巾-->
        <div class="am-g one-topic">
            <img data-original="<?=$publicDomain?>/mobile/img/banner-category/banner-<?=$categoryid?>.png" src="<?=$publicDomain?>/mobile/img/banner-category/banner-<?=$categoryid?>.png"  class="topic-banner lazy"/>
            <div class="topic-list">

                <?php
                    $total = count($productlist);
                    $count = $total>=6?6:$total;
                    for($i=0;$i<$count;$i++){
                ?>
                <div class="one-good">
                    <a href="/index/index/goodsdetail?goodsid=<?=$productlist[$i]['id']?>&urltype=1" onclick="window.goToApp.clickOnAndroid('1','<?=$productlist[$i]['id']?>')">
                        <img class="lazy" data-original="<?=$productlist[$i]['thumb']?>" src="<?=$publicDomain?>/mobile/img/default.png"/>
                        <p class="g-price ellipsis">
                            <?php if($productlist[$i]['prouctprice'] > 0){ ?>
                               <span class="red"><?=$productlist[$i]['prouctprice']?></span>元 
                            <?php }  ?> 
                            <?php if($productlist[$i]['prouctprice'] > 0 && $productlist[$i]['bullamount'] > 0){ ?> 
                            + 
                            <?php }?>
                            <?php if($productlist[$i]['bullamount'] > 0) { ?>
                                <span class="red"><?=$productlist[$i]['bullamount']?></span>牛豆  
                            <?php } ?> 
                        </p>
                        <p class="g-name ellipsis"><?=$productlist[$i]['productname']?></p>
                    </a>
                    
                </div>
                <?php }?>
                
            </div>
        </div>
        <!--/end毛巾-->
        
        
      
        <!--四件套-->
        <div class="one-topic">
            <div class="topic-list-3" id="good-list">
                <?php 
                    foreach ($goodList as $key => $productlist){
                ?>
                <div class="am-g  one-good-3">
                    <a href="/index/index/goodsdetail?goodsid=<?=$productlist['id']?>&urltype=1" onclick="window.goToApp.clickOnAndroid('1','<?=$productlist['id']?>')">
                        <div class="am-u-sm-4">
                            <img class="lazy" data-original="<?=$productlist['thumb']?>" src="<?=$publicDomain?>/mobile/img/default.png" />
                        </div>
                        <div class="am-u-sm-8">
                            <p class="g-name"><?=$productlist['productname']?></p>
                           
                             <p class="g-desc tl-ellipsis-2">
                            <?php foreach ($productlist['spec'] as $key => $value) {  ?>
                               <?php echo $value['spec_name']?>  : <?php foreach($value['value'] as $va){ ?> <?php echo $va['spec_value'];?>  <?php } ?>&nbsp;&nbsp;
                            <?php  } ?>
                            </p>
                            
                            <p class="g-price">
                            <?php if($productlist['prouctprice'] > 0){ ?>
                                <span class="red"><?=$productlist['prouctprice']?></span>元 
                            <?php }  ?> 
                            <?php if($productlist['prouctprice'] > 0 && $productlist['bullamount'] > 0){ ?> 
                            + 
                            <?php }?>
                            <?php if($productlist['bullamount'] > 0) { ?>
                                <span class="red"><?=$productlist['bullamount']?></span>牛豆  
                            <?php } ?> 
                            </p>
                        </div>
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>
        <!--四件套-->
       
    </div>
    <p class="no-data">亲！没有数据了哦~</p>
     <!--<p class="no-data"></p> -->
    <input type="hidden" id="limit" value="3">
    <div id="loading">
            <p>玩命加载中…</p>
    </div>
</body>

<script>
    var categoryid = "<?=$categoryid?>";
    $(function(){
       
        $("#good-list img.lazy").lazyload({
            threshold : 200,
            failurelimit : 10,  
            effect: "fadeIn"
        });

        $(".one-good img").lazyload({    
            placeholder : "<?=$publicDomain?>/mobile/img/default.png",    
            event : "sporty"   
            });    
        });

        $(window).bind("load", function() {    
            var timeout = setTimeout(function() {$("img").trigger("sporty")}, 3000);    

        });

    var stop=false;
    var lastPage=false;
    $(window).scroll(function(event){ 
        event.preventDefault();
        event.stopPropagation();
        if($(document).height() - 100 <=$(window).scrollTop()+$(window).height()){
            // 
            //if($(".no-data").is(":hidden")){
            //是否是最后一页
            if(lastPage==false){
                //stop 用来防止多次请求
                if(stop==false){ 
                    stop=true;
                   
                   var limit=parseInt($("#limit").val());
                   $.ajax({
                    type:"get",
                    url:"/Active/Index/ajaxGoodsList",
                    data:{"page":limit,"categoryid":categoryid},
                    async:true,
                    success:function(data){
                       // $("#loading").fadeOut(1000);
                        stop=false;
                        var html="";
                        var _data=JSON.parse(data); 
                        console.log(_data);
                        if(_data&&_data.length){
                            for(var i in _data){
                                //console.log(_data[i].id);
                                //规格
                                 var spec_str="";
                                 for(var j in _data[i].spec){
                                    var spec_val_str="";
                                    //值有多个
                                    for(var k in _data[i].spec[j].value){
                                        spec_val_str+=_data[i].spec[j].value[k].spec_value;
                                    }
                                    //spec_str+=' <p class="g-desc tl-ellipsis">'+ _data[i].spec[j].spec_name+":"+spec_val_str+'</p>';
                                    spec_str+= _data[i].spec[j].spec_name+":"+spec_val_str+'&nbsp;&nbsp;';
                                 }
                                  
                                
                                //价格+牛豆
                                var price_str="";
                                if(_data[i].bullamount==0){
                                     price_str='<span class="red">'+_data[i].prouctprice+'</span>元';
                                                

                                }else if(_data[i].bullamount>0){
                                    price_str='<span class="red">'+_data[i].prouctprice+'</span>元'+
                                              '+ <span class="red">'+_data[i].bullamount+'</span>牛豆';            
                                }
                              
                                html+='<div class="am-g  one-good-3">'+
                                    '<a  href="/index/index/goodsdetail?goodsid='+_data[i].id+'&urltype=1" onclick="window.goToApp.clickOnAndroid(\'1\',\''+_data[i].id+'\')" >'+
                                        '<div class="am-u-sm-4">'+
                                            '<img src="'+_data[i].thumb+'" />'+
                                        '</div>'+
                                       ' <div class="am-u-sm-8">'+
                                            '<p class="g-name tl-ellipsis">'+_data[i].productname+'</p>'+
                                           
                                          
                                             
                                            '<p class="g-desc tl-ellipsis-2">'+ spec_str+'</p>'+
                                            
                                           ' <p class="g-price">'+price_str+'</p>'+
                                        '</div>'+
                                    '</a>'+
                                '</div>';
                            }
                            $("#good-list").append(html);
                            $("#limit").val(limit+1);
                        }else{
                            //没有数据了
                            //$(".no-data").show();
                            lastPage=true;
                        }
                    },
                    beforeSend:function(){
                        
                        //$("#loading").fadeIn(1000);
                    },
                    error:function(data){}
                    
                   });
                }
            } 
        } 

        return false;
    });


</script>
</html>
