<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>功能介绍</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    {include file="Pub/assetcss" /}
    {include file="Pub/assetjs" /}

    <style>
    	.version-wrap{padding-left: 10px;}
    	.one-version{ border-bottom: 1px solid #EEEEEE;position: relative;padding: 10px 0;}
    	.one-version .v-no{color: #000000;font-size: 15px;}
    	.one-version .v-date{color: #999999;font-size: 12px;}
    	.one-version .v-icon{position: absolute;top: 50%;right: 10px;margin-top: -11px;color: #999999;}
    </style>
</head>

<body>
   
    <div class="version-wrap">
    	<div class="version-list" id="version-list">

        <?php foreach($version as $value){ ?>
    		<div class="one-version">
    			<a href="/versionintro/index/detail?id=<?=$value['id']?>">
    				<div class="v-no"><?=$value['title']?></div>
    				<div class="v-date"><?=$value['addtime']?></div>
    				<i class="am-icon-angle-right am-icon-sm v-icon"></i>
    			</a>
    		</div>
    	<?php } ?>
    		
    	</div>
    	
    </div>
    
    <input type="hidden" id="limit" value="2">
    
    
    
</body>

<script>
    var stop=false;
    var lastPage=false;
   
    $(window).scroll(function(event){ 
        event.preventDefault();
        event.stopPropagation();
        if($(document).height() - 100 <=$(window).scrollTop()+$(window).height()){
          
            //是否是最后一页
            if(lastPage==false){
                //stop 用来防止多次请求
                if(stop==false){ 
                    stop=true;
                   var limit=parseInt($("#limit").val());
                   $.ajax({
                    type:"get",
                    url:"/versionintro/index/ajaxload",
                    data:{"page":limit,"type":'<?=$type?>'},
                    async:true,
                    success:function(data){
                       // $("#loading").fadeOut(1000);
                        stop=false;
                        var html="";
                        //var _data=data;
                        var _data=JSON.parse(data); 
                        console.log(_data);
                        if(_data&&_data.length){

                            for(var i in _data){
                        		
                                html+='<div class="one-version">'+
					    			'<a href="/versionintro/index/detail">'+
					    				'<div class="v-no">'+_data[i].title+'</div>'+
					    				'<div class="v-date">'+_data[i].addtime+'</div>'+
					    				'<i class="am-icon-angle-right am-icon-sm v-icon"></i>'+
					    			'</a>'+
					    		'</div>';
                        	}
                            
                            $("#version-list").append(html);
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
