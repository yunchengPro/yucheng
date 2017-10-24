  $(document).on("click",".keyword-list li",function(event){
      $(this).parent(".keyword-list").siblings(".keyword").val($(this).html());
      $(".keyword-list").hide();
      event.stopPropagation();
  });

  $(document).click(function(){
      $(".keyword-list").hide();
  });

  $(document).on("click",".keyword",function(){
      return false; 
  });
        
  $(document).on("focus",".keyword",function(){
      $(this).siblings(".keyword-list").show();
      $(this).siblings(".keyword-list").css({
                'width':$(this).width()
      });
           
  });

$(document).on("keyup",".keyword",function(){
     var keyVal=$(this).val();
           
    if(keyVal==""){
                $(this).siblings(".keyword-list").show();
                $(this).siblings(".keyword-list").find("li").show();
     }else{
            var flag=0;
            $(this).siblings(".keyword-list").find("li").each(function(idx,ele){
                if($(ele).html().indexOf(keyVal)!=-1){
                    $(ele).show();
                    flag=1;
                }else{
                    $(ele).hide();
                }
            });
            
            if(flag){
                $(this).siblings(".keyword-list").show();
            }else{
                $(this).siblings(".keyword-list").hide();
            }
        }
                
});