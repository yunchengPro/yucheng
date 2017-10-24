$(function () {

    'use strict';
    // Change this to the location of your server-side upload handler:
    // var url = window.location.hostname === 'blueimp.github.io' ?
    //             '//jquery-file-upload.appspot.com/' : 'server/php/',
    var url  =  $("#fileupload").attr("_url"); 
    var num  =  $("#fileupload").attr('_num');
    var purl =  $("#fileupload").attr('_purl');

    var uploadButton = $('<button/>')
            .addClass('btn btn-primary')
            .prop('disabled', true)
            .text('Processing...')
            .on('click', function () {
                var $this = $(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('delete')
                    .on('click', function () {
                        //$this.parent().parent().remove();
                        $this.remove();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
            });
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 999000,
        // Enable image resizing, except for Android and Opera,
        // which actually support image resizing, but fail to
        // send Blob objects via XHR requests:
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
        previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: true
    }).on('fileuploadadd', function (e, data) {
        data.context = $('<div/>').appendTo('#files');
        $.each(data.files, function (index, file) {
            
            var pnum = $("#files > div").length;

            var node = $('<p/>')
                .append($('<span/>').text(file.name));

            if(pnum > num){
                alert('最多只能上传'+num+'张照片');
                return false;
            }else{

                if (!index) {
                    node
                    .append('<br>')
                    .append('<div  style="width:100px;height:10px;" aria-valuenow="0" aria-valuemax="100" aria-valuemin="0" role="progressbar" class="progress "><div style="width:0%;" class="progress-bar progress-bar-success"></div></div>');
                    //.append(uploadButton.clone(true).data(data));
                }
                node.appendTo(data.context);
                data.submit();
            }
        });
    }).on('fileuploadprocessalways', function (e, data) {
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
                //.text('Upload')
                .prop('disabled', !!data.files.error);
        }
    }).on('fileuploadprogressall', function (e, data) {
       
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css(
            'width',
            progress + '%'
        );
    }).on('fileuploaddone', function (e, data) {

        $.each(data.result.files, function (index, file) {

            //插入图片信息
            if(data.result.state == 'SUCCESS'){
                var state = data.result.state;
                var url   = data.result.url;
                var type = data.result.type;
                var size = data.result.size;
                var original = data.result.original;
           
                var purl  = '/demo/html/getfile';
               
                $.post(purl,{f_fileoriginal:original,f_filesize:size,f_fileurl:url,f_filetype:type},function (data){ 
                   
                });
            }

            var progress = parseInt(data.loaded / data.total * 100, 10);

            if (file.url) {
                //alert(progress);
                $('.progress-bar').css(
                    'width',
                    progress + '%'
                );
                var link = $('<a>')
                    .attr('target', '_blank')
                    .prop('href', file.url);

                $(data.context.children()[index])
                    .append('<button type="button"  _purl="'+file.url+'"  class="btn btn-primary delete">删除</button>');

            } else if (file.error) {
                var error = $('<span class="text-danger"/>').text(file.error);
                $(data.context.children()[index])
                    .append('<br>')
                    .append(error);
            }

        });

    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index) {
            var error = $('<span class="text-danger"/>').text('File upload failed.');
            $(data.context.children()[index])
                .append('<br>')
                .append(error);
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

    $('#files').on('click','.delete', function (){
        if(confirm("确认要删除吗")){
            
            var purl = $(this).attr("_purl");
            $.post('/demo/html/deletefile',{f_fileurl:purl}, function (data){ 
            });
            $(this).parent().parent().remove();

        }else{
            return false;
        }
       
    });
});



