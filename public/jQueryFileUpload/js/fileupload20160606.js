var file_number = 1;
var uploadButton = $('<button/>')
            .addClass('btn btn-primary')
            .prop('disabled', true)
            .text('Processing...')
            .on('click', function () {
                var $this = $(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('Abort')
                    .on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
            });
function upload_progress(id){
    var html = '<div  style="width:100px;height:10px;" aria-valuenow="0" aria-valuemax="100" aria-valuemin="0" role="progressbar" class="progress " id="progress_'+id+'"><div style="width:0%;" class="progress-bar progress-bar-success"></div></div><div id="progress_state_'+id+'"></div>';
    return html;
}

function fileupload_addfile(id,value){
    var file = $("#"+id).val();
    file+=","+value;
    if(file.substr(0,1)==',')
        file = file.substring(1);
    $("#"+id).val(file);
    return true;
}

function fileupload_deletefile(id,value){
    var file = $("#"+id).val();
    if(file.indexOf(value)!=-1){
        file = file.replace(value,'');
        file = file.replace(",,",",");
        if(file.substr(0,1)==',')
            file = file.substring(1);
        $("#"+id).val(file);
    }
    return true;
}

function fileupload_getfilecount(id){

}

function fileupload_removeupload(id,objid,url){
    if(confirm('是否确认删除该文件？')){
        //var value = $("#"+id).find("#url").html();
        fileupload_deletefile(objid,url);
        $("#"+id).remove();
    }
}

function compare(propertyName) {
    return function (object1, object2) {
        var value1 = object1[propertyName];
        var value2 = object2[propertyName];
        //value1 = parseint(value1);
        //value2 = parseint(value2);
        if (value2 > value1) {
            return -1;
        }else if (value2 < value1) {
            return 1;
        }else {
            return 0;
        }
    }
}

//重新排序
function fileupload_changesort(id){
    var sortarr = [];
    var count = 0;
    $("."+id+"_sort").each(function(){
        var value = $(this).val();
        var url   = $(this).attr("src");
        sortarr[count] = {"order":value,"url":url};
        count++;
    });

    sortarr.sort(compare("order"));

    var file ='';
    for(var t in sortarr){
        file+=","+sortarr[t].url;
    }
    if(file.substr(0,1)==',')
        file = file.substring(1);
    //alert(file);
    $("#"+id).val(file);
}



function fileuploadaction(id,op){

    var uploader = $('#'+id+"_upload").fileupload(op)
    .on('fileuploadadd', function (e, data) {


        //定义一个文件编号
        data.file_number = file_number;
        file_number++;
        if($("#uploadfiles_"+id).find(".filediv_"+id).length+1<=op.maxNumberOfFiles){
            data.context = $('<div/>').appendTo('#uploadfiles_'+id);
            data.context.css({"float":"left","width":"100px","margin-right":"10px"}).attr("id","file_"+id+"_"+data.file_number).attr("class","filediv_"+id);

            $.each(data.files, function (index, file) {
                var size_state ='';

                if(file.size<op.maxFileSize){
                    if(file.size<(1024*1024)){
                        var size=(file.size/1024);
                        size = size.toFixed(1);
                        size_state = size.toString()+"K";
                    }else{
                        var size=(file.size/(1024*1024));
                        size = size.toFixed(1);
                        size_state = size.toString()+"M";
                    }

                    var node = $('<div/>')
                            .append($('<span/>').css({"word-wrap":"break-word","word-break":"normal","font-size":"10px"}).text());
                    if (!index) {
                        node
                            .append('<br>');
                            //.append(uploadButton.clone(true).data(data));
                    }

                    node.append(upload_progress(data.file_number));
                    node.appendTo(data.context);

                    //uploader._setOption("server_type","member");
                    //$('#'+id+"_upload").fileupload('option',{"formData":{"server_type":"member"}});

                    if(typeof op.getParamUrl != 'undefined' && op.getParamUrl!='' ){
                        if(typeof op.formData != 'undefined' && typeof op.formData.server_type != 'undefined' && op.formData.server_type!='' ){
                            op.getParamUrl+="&server_type="+op.formData.server_type;
                        }
                        $.get(op.getParamUrl,function(policy){
                            if(typeof policy =='string' && policy!=''){
                                var policy = eval('(' + policy + ')');
                                var obj = policy.data;
                                var tmp = file.name.split(".");
                                var multipart_params = {
                                    'key' : obj.dir+'.'+tmp[1],
                                    'policy': obj.policy,
                                    'OSSAccessKeyId': obj.accessid,
                                    'success_action_status' : (typeof obj.success_action_status!='undefined')?obj.success_action_status:'200', //让服务端返回200,不然，默认会返回204
                                    'signature': obj.signature
                                };

                                //设置文件名
                                data.files[index].image_url = multipart_params.key;

                                //alert(multipart_params.key);
                                if(typeof obj.callback !='undefined' && obj.callback!='')
                                    multipart_params.callback = obj.callback;

                                //alert(multipart_params.key);
                                if(typeof op.formData == 'undefined'){
                                    op.formData = {};
                                    op.formData = multipart_params;
                                }else{
                                    op.formData = {};
                                    op.formData = multipart_params;
                                }
                                //op.domain = obj.ImgServerName;
                                //$('#'+id+"_upload").fileupload('option',{"formData":multipart_params});
                                //$('#'+id+"_upload").fileupload('option',{"url":obj.host});
                                data.formData = multipart_params;
                                data.url      = obj.host
                                //$('#'+id+"_upload").fileupload('option',{"domain":obj.ImgServerName});

                                //写入表单
                                fileupload_addfile(id,multipart_params.key);

                                setTimeout(function(){

                                   

                                    data.submit();
                                    
                                },300);
                                $("<a href='#' onclick=\"fileupload_removeupload('file_"+id+"_"+data.file_number+"','"+id+"','"+multipart_params.key+"')\"> 删除 </a>&nbsp;").appendTo('#progress_state_'+data.file_number);
                            }else{
                                alert('upload error, policy is empty');
                                return false;
                            }
                        });
                    }else{
                        data.submit();
                    }




                }else{
                    alert("文件大小不能超过："+(op.maxFileSize/(1024*1024)).toFixed(1).toString()+"M");
                    return false;
                }
            });
        }else{
            alert("文件最多只能上传"+op.maxNumberOfFiles+"份");
            return false;
        }

    }).on('fileuploadprocessalways', function (e, data) {
       // alert(file.preview);
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
        if (file.preview) {
            node
                .prepend('<br>')
                .prepend(file.preview);
        }
        if (file.error) {
            node
                .append('<br>')
                .append($('<span class="text-danger"/>').text(file.error));
        }
        if (index + 1 === data.files.length) {
            data.context.find('button')
                .text('Upload')
                .prop('disabled', !!data.files.error);
        }
       // alert("end");
    }).on('fileuploadprogress', function (e, data) {

        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress_'+data.file_number).find(".progress-bar").css(
            'width',
            progress + '%'
        );
        if(progress>=100){
            setTimeout(function(){
                $('#progress_'+data.file_number).remove();
            }, 500);

            for(var s in data.files){
                //alert(s+"----"+data.files[0][s]);
                var file = data.files[s];
                $('#progress_state_'+data.file_number).css({"font-size":"10px"}).html("<a href='#' onclick=\"fileupload_removeupload('file_"+id+"_"+data.file_number+"','"+id+"','"+file.image_url+"')\"> 删除 </a>&nbsp;");
                //$('#progress_state_'+data.file_number).css({"font-size":"10px"});
            }
        }


    }).on('fileuploaddone', function (e, data) {


        if(data._response.textStatus=="success"){
            //alert(op.formData.key);

            for(var s in data.files){
                //alert(s+"----"+data.files[0][s]);
                var file = data.files[s];

                data.result             = {};
                data.result.url         = file.image_url;  //op.formData.key
                data.result.original    = file.name; //原文件名
                data.result.size        = file.size;
                var tmp = file.name.split(".");
                data.result.type        = tmp[1];

                if (data.result.url) {

                    //上传时写入表单
                    //fileupload_addfile(id,data.result.url);

                    $('<span id="url" style="display:none">'+data.result.url+'</span>').appendTo("#file_"+id+"_"+data.file_number);

                    //$("<a href='#' onclick=\"fileupload_removeupload('file_"+id+"_"+data.file_number+"','"+id+"')\"> 删除 </a>&nbsp;").appendTo('#progress_state_'+data.file_number);
                    $("<a href='"+op.domain+data.result.url+"' target='_blank'> 预览</a>").appendTo('#progress_state_'+data.file_number);

                    $("<input type='text' style='width:20px;margin-left:5px;' class='"+id+"_sort' src='"+data.result.url+"' onChange=\"fileupload_changesort('"+id+"')\" value='"+data.file_number+"'>").appendTo('#progress_state_'+data.file_number);

                    if(op.isdefault != false){
                        $("<br><input type='radio' name='isdefault["+op.specid+"]' id='"+data.file_number+"' value='"+data.result.url+"'><label for='"+data.file_number+"'>设为首图</label>").appendTo('#progress_state_'+data.file_number);
                    }

                    $("#file_"+id+"_"+data.file_number).find("canvas").css("cursor","pointer").click(function(){
                        window.open(op.domain+data.result.url);
                    });
                    //记录上传的文件
                    $.post(op.savefileurl,{f_fileoriginal:data.result.original,f_filesize:data.result.size,f_fileurl:data.result.url,f_filetype:data.result.type},function(data){});
                } else if (file.error) {
                    var error = $('<span class="text-danger"/>').text(file.error);
                    $(data.context.children()[index])
                        .append('<br>')
                        .append(error);
                }
            }





        }
    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index) {
            var error = $('<span class="text-danger"/>').text('File upload failed.');
            $(data.context.children()[index])
                .append('<br>')
                .append(error);
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

}

function fileupload(id,options){
    //var url = '//10.70.40.250/uploadfile/jqueryfileupload.php';
    if(typeof options =='string')
        options = eval('(' + options + ')');

    var defaults = {
        url: '',
        domain:'',
        dataType: 'html',
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 2048000,
        maxNumberOfFiles:1, //最大文件数
        // Enable image resizing, except for Android and Opera,
        // which actually support image resizing, but fail to
        // send Blob objects via XHR requests:
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
        previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: false,
        isdefault: false,
		specid: 0
    };
    var op = $.extend(defaults, options);
    //alert(op.getParamUrl);
    fileuploadaction(id,op);
}
