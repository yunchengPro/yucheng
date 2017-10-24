{include file="Pub/header" /}
<link rel="stylesheet" href="/jQueryFileUpload/css/fileupload.css">
<script type="text/javascript" src="/pinture/js/jquery.js"></script>
<script type="text/javascript" src="/pinture/js/jquery-ui.min.js"></script>

<script src="/jQueryFileUpload/js/vendor/jquery.ui.widget.js"></script>
<script src="/jQueryFileUpload/js/load-image.all.min.js"></script>
<script src="/jQueryFileUpload/js/canvas-to-blob.min.js"></script>
<script src="/jQueryFileUpload/js/jquery.iframe-transport.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload-process.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload-image.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload-validate.js"></script>
<script src="/jQueryFileUpload/js/fileupload.js"></script>	
<style type="text/css">
#f_thumbnailurl_upload {
	margin-top: 20px;
	margin-left: 20px;
}
#uploadfiles_f_thumbnailurl{
	margin-left: 20px;
}
</style>
<?=Html::imgupload(array('id'=>'f_thumbnailurl',"options"=>array("maxNumberOfFiles"=>30,'maxFileSize'=>4194304)))?>

<script type="text/javascript">
	
	function inserimg(){
	    //editor.execCommand('insertHtml','测试8899999999');
	    var obj = $('#f_thumbnailurl');
	    var html = '';
	    //console.log(obj.val());
	    url = obj.val().split(","); //字符分割
	    //console.log(url);
		for (i=0 ; i<url.length ; i++ ){
			html = html + "<img src='<?=$imgurl?>/"+url[i]+"'  width='100%' >";
		}
	   	html = html + "<br/>&nbsp;";
	    return html;
  
	}
</script>
{include file="Pub/footer" /}