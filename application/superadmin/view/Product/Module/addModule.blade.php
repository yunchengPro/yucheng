{include file="Pub/header" /}
<link rel="stylesheet" href="/newui/lib/zTree/v3/css/zTreeStyle/zTreeStyle.css" type="text/css">
<form  method="post" id="form" class="frm"  rel="iframe-form" ><!--rel="iframe-form"-->
<div class="type-edit" id="type-edit">
    <h4 class="font-gold">商品属性</h4>
    <div class="type-edit-row">
        <label>类型名称</label><input type="text" class="input-text" id="modulename" value="<?=$moduleData['modulename']?>" name="modulename" style="width:180px">
        <!--< label>关联分类</label>
        <select type="text" class="input-text" name="categoryid[]" multiple="multiple" style="width: 180px;height: 200px;">
            <?php foreach($categoryArr as $key =>  $value){ ?>
                <option <?php if (in_array($key, $relationCateDataId)) {?> selected="selected" <?php } ?> value="<?=$key?>"><?=$value?></option>
            <?php } ?>
        </select>  -->
    </div>
    <h4 class="font-gold">规格</h4>
    <div class="type-edit-row">
        <?php if(is_array($specData[0])){
            foreach ($specData as $key => $value){ ?>
              
       <div class="type-item">
            <a><?=$value['specname']?></a><input type="hidden" id="spec_type" name="spec_type[]" value="<?=$value['id']?>" />
            <img class="deleteItem" src="/newui/static/h-ui.admin/images/ic_close.png">
        </div>
       
                
        <?php } }else{ ?>
        <div class="type-item">
            <a><?=$specData['specname']?></a><input type="hidden" id="spec_type" name="spec_type[]" value="<?=$specData['id']?>" />
            <img class="deleteItem" src="/newui/static/h-ui.admin/images/ic_close.png">
        </div>
        <?php } ?>
        <a class="add add_spec"  _page="2">+</a>
    </div>
  <!--   <h4 class="font-gold">扩展属性</h4>
    <div class="type-edit-row border-none">
         <?php if(is_array($attrData[0])){
            foreach ($attrData as $key => $value) { ?>
          
            <div class="type-item">
                <a><?=$value['attr_name']?></a><input type="hidden" id="attr_type" name="attr_type[]" value="<?=$value['id']?>" />
                <img class="deleteattrItem" src="/newui/static/h-ui.admin/images/ic_close.png">
            </div>
        <?php  } }else{ ?>
        <div class="type-item">
            <a><?=$attrData['attr_name']?></a><input type="hidden" id="attr_type" name="attr_type[]" value="<?=$attrData['id']?>" />
            <img class="deleteattrItem" src="/newui/static/h-ui.admin/images/ic_close.png">
        </div>
        <?php } ?>
        <a class="add add_attr" _page="2">+</a>
    </div> -->
    <input type='hidden' name="id" id="id" value="<?=$moduleData['id']?>" />
   <!--  <div class="opreation display-flex">
        <a class="btn-primary" href="/Product/procategory/product_type_list">取消</a>
        <button type="submit" class="btn btn-danger" style="width:100px;">保存</button>
    </div> -->
</div>
</form>
{include file="Pub/footer" /}
<script type="text/javascript" src="/newui/lib/zTree/v3/js/jquery.ztree.all-3.5.min.js"></script>
<script>
    $(function () {
        $("#type-edit").on("click", ".add_spec", function () {

            var url = "/Product/Module/chose_produc_spec";
            window.dindex = layer.open({
                type: 2,
                title: '添加规格',
                maxmin: true,
                shadeClose: false, //点击遮罩关闭层
                area : ['600px' , '500px'],
                content: url,
            });
  
            // var page = $('.add_spec').attr('_page');
            
            //     $.ajax({
            //         type: "post", // 发送的请求类型 GET ， POST ， 默认 get
         
            //         url: "/Product/procategory/get_produc_spec" , // 发送请求的地址
         
            //         timeout: "5000", // 请求超时时间  毫秒
         
            //         async: false, //  请求是异步还是同步 ，  默认异步 true
         
            //         cache: true, // 默认 true, 当dataType为script时，默认为false 设置为false将不会从浏览器缓存中加载请求信息
         
            //         dataType: "json", // 返回类型：  xml ， html , script ， json,， text 、
            //         beforeSend: function(){
            //             // $("body").modalmanager("loading",'数据加载中。。');
            //         },
            //         complete:  function() {
                        
            //         },  // 请求完成后调用此函数
         
            //         data: {page:page}, // 参数  get 请求: Response.Querystring[""]   post 请求： Response.QueryForm[""]
            //        // 要求为Object或String类型的参数，发送到服务器的数据。如果已经不是字符串，将自动转换为字符串 式。get请求中将附加在url后。防止这种自动转换，可以查看processData选项。对象必须为key/value格式，例如{foo1:"bar1",foo2:"bar2"}转换为&foo1=bar1&foo2=bar2。如果是数组，JQuery将自动为不同 值对应同一个名称。例如{foo:["bar1","bar2"]}转换为&foo=bar1&foo=bar2。
         
         
            //         success: function (data) {
                       
            //             if(data){
                           
            //                 var itemHtml = '<div class="type-item"><a>'+ data['specname'] + '</a>' + data['input'] + '<img class="deleteItem" src="/newui/static/h-ui.admin/images/ic_close.png"></div>';
            //                 $('.add_spec').before(itemHtml);
            //                 $('.add_spec').attr('_page',data['page']);
            //             }else{
            //                  alert('无可加规格');
            //             }
                       
            //         }, // 请求成功后，调用的函数
         
            //         error: function(){
            //            alert("Get Data Error");
            //         }
         
            //     });
           
        });
        $("#type-edit").on("click", ".deleteItem", function () {
           var page = parseInt($('.add_spec').attr('_page'))-1;
           $('.add_spec').attr('_page',page);
           $(this).parent().remove();
        });

        $("#type-edit").on("click", ".add_attr", function () {
            var url = "/Product/Module/chose_produc_attr";
            window.dindex = layer.open({
                type: 2,
                title: '添加规格',
                maxmin: true,
                shadeClose: false, //点击遮罩关闭层
                area : ['600px' , '500px'],
                content: url,
            });
            // var page = $('.add_attr').attr('_page');
            
            //     $.ajax({
            //         type: "post", // 发送的请求类型 GET ， POST ， 默认 get
         
            //         url: "/Product/procategory/get_produc_attr" , // 发送请求的地址
         
            //         timeout: "5000", // 请求超时时间  毫秒
         
            //         async: false, //  请求是异步还是同步 ，  默认异步 true
         
            //         cache: true, // 默认 true, 当dataType为script时，默认为false 设置为false将不会从浏览器缓存中加载请求信息
         
            //         dataType: "json", // 返回类型：  xml ， html , script ， json,， text 、
            //         beforeSend: function(){
            //             // $("body").modalmanager("loading",'数据加载中。。');
            //         },
            //         complete:  function() {
                        
            //         },  // 请求完成后调用此函数
         
            //         data: {page:page}, // 参数  get 请求: Response.Querystring[""]   post 请求： Response.QueryForm[""]
            //        // 要求为Object或String类型的参数，发送到服务器的数据。如果已经不是字符串，将自动转换为字符串 式。get请求中将附加在url后。防止这种自动转换，可以查看processData选项。对象必须为key/value格式，例如{foo1:"bar1",foo2:"bar2"}转换为&foo1=bar1&foo2=bar2。如果是数组，JQuery将自动为不同 值对应同一个名称。例如{foo:["bar1","bar2"]}转换为&foo=bar1&foo=bar2。
         
         
            //         success: function (data) {
                       
            //             if(data){
                           
            //                 var itemHtml = '<div class="type-item"><a>'+ data['attr_name'] + '</a>' + data['input'] + '<img class="deleteattrItem" src="/newui/static/h-ui.admin/images/ic_close.png"></div>';
            //                 $('.add_attr').before(itemHtml);
            //                 $('.add_attr').attr('_page',data['page']);
            //             }else{
            //                 alert('无可加属性');
            //             }
                       
            //         }, // 请求成功后，调用的函数
         
            //         error: function(){
            //            alert("Get Data Error");
            //         }
         
            //     });
           
        });

       

        $("#type-edit").on("click", ".deleteattrItem", function () {
           var page = parseInt($('.add_attr').attr('_page'))-1;
           $('.add_attr').attr('_page',page);
           $(this).parent().remove();
        });


        var validate = $("#form").validate({
            debug: true, //调试模式取消submit的默认提交功能   
            //errorClass: "label.error", //默认为错误的样式类为：error   
            focusInvalid: false, //当为false时，验证无效时，没有焦点响应  
            onkeyup: false,   
            submitHandler: function(form){   //表单提交句柄,为一回调函数，带一个参数：form   
               form.submit();   //提交表单   
            },   
            
            rules:{
                categoryid:{
                    required:true
                },
                attr_type:{
                    required:true
                },
                spec_type:{
                    required:true
                }
            },
            messages:{
                categoryid:{
                    required:"请选择关联分类",
                },
                modulename:{
                    required:"请填写分类名称"
                },  
                attr_type:{
                    required:'请选择关联属性'
                },
                spec_type:{
                    required:'请选择关联规格'
                }       
            }
                          
        });   

    })
</script>


