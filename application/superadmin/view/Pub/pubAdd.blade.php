<style type="text/css">
    .page-container{padding-top: 0px;}
</style>
<div class="main-div">


<div class="admin">
<article class="page-container page-container-add" id="tab-set">
    <form class="form form-horizontal" id="fModi" name="fModi" method="post" action="<?php echo !empty($action)?$action:urldecode($_GET['popurl']);?>" class="frm" rel="iframe-form"><!---->
    <?php //echo csrf_field(); ?>
    <?php 
        $rules_str = isset($rules_str)?$rules_str:"";
        $messages_str = isset($messages_str)?$messages_str:"";
        $hidden_str = isset($hidden_str)?$hidden_str:"";
        $submit_arr = array();
        if(!empty($field)){

            foreach($field as $key=>$value){
                if($value['type']=='hidden' ){
                    $hidden_str.=Html::hidden(array("name"=>$key,"value"=>($info[$key]!=''?$info[$key]:$value['value'])));
               }elseif($value['type']=='submit'){
                   $submit_arr = $value; 
               }else{ 
    ?>
    <div class="row cl" <?php if(!empty($value['tr_style'])){ ?><?php echo $value['tr_style']; ?><?php } ?> >
        <?php if($value['type']=='title'){?>
        <label class="form-label col-xs-2 col-sm-2" <?=$value['head_style']?>>
            <?=$value['name']?>
        </label>
        <?php }else{?>
        <label class="form-label col-xs-3 col-sm-4" <?php echo !empty($value['head_width'])?"width='".$value['head_width']."'":""?> <?=$value['head_style']?>>
            <?=$value['name']?>:
        </label>
        <div class="formControls col-xs-9 col-sm-8" <?php echo !empty($value['data_width'])?"width='".$value['data_width']."'":""?> <?=$value['data_style']?>>
        <?php 
            if(isset($value['data']) || $field_type=='detail'){
                if($value['type']=='thumb'){
                    $val = ($info[$key]!=''?$info[$key]:$value['data']);
                    $value['width'] = $value['width'] ? $value['width'] : 100;
                    $value['height'] = $value['height'] ? $value['height'] : 100;
                    $newval = ShowThumb($val, $value['width'], $value['height']);
                    echo "<img src='".$newval."' onClick='javascript:window.open(\"".ShowImage($val)."\")'>";
                }elseif($value['type']=='checkbox'){
                    echo Html::checkbox(
                        array(
                            "name"=>$key,
                            "terms"=>$value['terms'],
                            "other"=>$value['other'],
                            "value"=>($info[$key]!=''?$info[$key]:$value['value'])
                        )
                    );
                }elseif($value['type']=='a'){
                     echo Html::a(
                        array("type"=>$value['pagetype'],"name"=>"button","value"=>$value['name'],"_width"=>$value['width'],"_height"=>$value['height'],"_title"=>$value['name'],"_url"=>$value['data'])
                    );
                }else{
                    if(isset($value['data'])){
                        echo $value['data'];
                    }else{
                        echo $info[$key];
                    }
                }
                
            }else{
                if($value['type']=='text'){
                    echo Html::text(
                        array(
                            "name"=>$key,
                            "placeholder"=>$value['name'],
                            "class"=>$value['class']!=''?$value['class']:"add-width",
                            "other"=>$value['other'],
                            "disabled" => $value["disabled"],
                            "value"=>($info[$key]!=''?$info[$key]:$value['value'])
                        )
                    );
                }elseif($value['type']=='password'){
                     echo Html::password(
                        array(
                            "name"=>$key,
                            "placeholder"=>$value['name'],
                            "other"=>$value['other'],
                            "class"=>$value['class']!=''?$value['class']:"width150",
                            "value"=>($info[$key]!=''?$info[$key]:$value['value'])
                        )
                    );
                }elseif($value['type']=='select'){
                    echo Html::select(
                        array(
                            "name"=>$key,
                            "option"=>$value['option'],
                            "top_option"=>$value['top_option'],
                            "other"=>$value['other'],
                            "value"=>($info[$key]!=''?$info[$key]:$value['value'])
                        )
                    );
                }elseif($value['type']=='selectpicker'){
                    echo Html::selectpicker(
                        array(
                            "name"=>$key,
                            "option"=>$value['option'],
                            "top_option"=>$value['top_option'],
                            "other"=>$value['other'],
                            "value"=>($info[$key]!=''?$info[$key]:$value['value'])
                        )
                    );
                }elseif($value['type']=='time'){
                    echo Html::time(
                        array(
                            "name"=>$key,
                            "dateFmt"=>(!empty($value['dateFmt'])?$value['dateFmt']:1),
                            "placeholder"=>$value['name'],
                            "class"=>$value['class']!=''?$value['class']:"width150",
                            "other"=>$value['other'],
                            "value"=>($info[$key]!=''?$info[$key]:$value['value'])
                        )
                    );
                }elseif($value['type']=='times'){
                    echo Html::times(
                        array(
                            "start"=>"start_".$key,
                            "dateFmt"=>(!empty($value['dateFmt'])?$value['dateFmt']:1),
                            "minDate"=>(!empty($value['minDate']) ? $value['minDate']: ""),
                            "maxDate"=>(!empty($value['maxDate']) ? $value['maxDate']: ""),
                            "start_value"=>(!empty($info["start_".$key])?$info["start_".$key]:$value['start_value']),
                            "end"=>"end_".$key,
                            "end_value"=>(!empty($info["end_".$key])?$info["end_".$key]:$value['end_value']),
                            "maxminflag"=>$value['maxminflag'],
                            "other"=>$value['other'],
                            "class"=>$value['class']!=''?$value['class']:"width150",
                            "placeholder"=>$value['name']
                        )
                    );
                }elseif($value['type']=='area'){
                    echo Html::area(
                        array(
                            "name"=>$key,
                            "other"=>$value['other'],
                            "value"=>($info[$key]!=''?$info[$key]:$value['value'])
                        )
                    );
                }elseif($value['type']=='imgupload'){
                    echo Html::imgupload(
                        array(
                            'id'=>$key,
                            'name'=>($value['valuename']!=''?$value['valuename']:$key),
                            "value"=>($info[$key]!=''?$info[$key]:$value['value']),
                            "other"=>$value['other'],
                            "options"=>$value['options']
                        )
                    );
                }elseif($value['type']=='radio'){
                    echo Html::radio(
                        array(
                            "name"=>$key,
                            "terms"=>$value['terms'],
                            "other"=>$value['other'],
                            "value"=>($info[$key]!=''?$info[$key]:$value['value'])
                        )
                    );
                }elseif($value['type']=='checkbox'){
                    echo Html::checkbox(
                        array(
                            "name"=>$key,
                            "terms"=>$value['terms'],
                            "other"=>$value['other'],
                            "value"=>($info[$key]!=''?$info[$key]:$value['value'])
                        )
                    );
                }elseif($value['type']=='textarea'){
                    echo Html::textarea(
                        array(
                            "name"=>$key,
                            "value"=>($info[$key]!=''?$info[$key]:$value['value']),
                            "rows"=>$value['rows'],
                            "other"=>$value['other'],
                            "cols"=>$value['cols']
                        )
                    );
                }elseif($value['type']=='edit'){
                    echo Html::edit(
                        array(
                            "name"=>$key,
                            "value"=>($info[$key]!=''?$info[$key]:$value['value']),
                            "width"=>$value['width'],
                            "height"=>$value['height'],
                            "other"=>$value['other'],
                            "config"=>$value['config']
                        )
                    );
                }elseif($value['type']=='thumb'){
                    $val = ($info[$key]!=''?$info[$key]:$value['value']);
                    $value['width'] = $value['width'] ? $value['width'] : 100;
                    $value['height'] = $value['height'] ? $value['height'] : 100;
                    $val = ShowThumb($val, $value['width'], $value['height']);
                    echo "<img src='{$val}'>";
                }elseif($value['type']=='button'){
                     echo Html::button(
                        array(
                            "name"=>$key,
                            "value"=>($info[$key]!=''?$info[$key]:$value['value']),
                            "width"=>$value['width'],
                            "height"=>$value['height'],
                            "other"=>$value['other'],
                            "config"=>$value['config'],
                            "onclick"=>$value['onclick'],
                            "class"=>$value['class']
                        )
                    );
                }


                if(!empty($value['validator'])){
                    $rules = '';
                    $messages = ''; 
                    if(is_array($value['validator'])){
                        if(!empty($value['validator']['rules']))
                            $rules = $value['validator']['rules'];
                        if(!empty($value['validator']['messages']))
                            $messages = $value['validator']['messages'];
                    }else{
                        $rules = $value['validator'];
                        $messages = $value['messages'];
                    }
                    if(strpos($rules,"required:true")!==false)
                        echo '&nbsp;<span class="red">*</span>';

                    if($value['type']=='imgupload')
                        $rules = str_replace('required', 'img_upload',$rules);
                    
                    if($value['type']=='edit') 
                        $rules = str_replace('required', 'ueditor',$rules);

                    if($value['type']=='times'){
                        $rules_str.="start_".$key.':{'.$rules.'},';
                        $rules_str.="end_".$key.':{'.$rules.'},';
                    }else{
                        $rules_str.=$key.':{'.$rules.'},';
                    }
                    if(!empty($messages)){
                        if(is_array($messages)){
                            $temp='';
                            foreach($messages as $ms_key=>$ms_value){
                                $temp.=$ms_key.':"'.$ms_value.'",';
                            }
                            $messages_str.=$key.':{'.substr($temp,0,-1).'},';
                        }else{
                            $messages_str.=$key.': "'.$value['messages'].'",';
                            //$messages_str.=$key.': {remote:jQuery.format("'.$value['messages'].'")},';
                        }
                    }
                    
                }
            }
            echo $value['append_str'];
        ?>   
        </div>
    </div>
    <?php }}}}?>
    <?=$formtoken;?>
    <?php if(!empty($submit_arr)){?>
   <div class="row cl">
        <div class="col-xs-8 col-sm-6 col-xs-offset-4 ">
            <button class="btn btn-gold "  type="submit"  >&nbsp;<?php echo $submit_arr['name']!=''?$submit_arr['name']:'确定';?>&nbsp;</button>
        &nbsp;<?=$submit_arr['other']?>
        </div>
    </div>

    <?php }?>
<!-- </table> -->
<!--<div style='height:42px'>&nbsp;</div>-->
<?php echo $hidden_str;?>

<?php
    if(!empty($rules_str)){
        $rules_str = substr($rules_str,-1,1)==','?substr($rules_str,0,-1):$rules_str;
        $messages_str = substr($messages_str,-1,1)==','?substr($messages_str,0,-1):$messages_str;
?>
<script type="text/javascript">
$(function(){
    
    //扩展方法
    $.validator.addMethod("img_upload",function(value, element, param){  
        var name=$(element).attr("name");
        if($("#"+name).val()!=''){
            return true;
        }else{
            return false;
        }
    },"图片不能为空");

    $.validator.addMethod("ueditor",function(value, element, param){  
        var name=$(element).attr("name");
        //alert($(element).attr("name"));
        eval("var content=ue_"+name+".getContent();");
        //alert("|"+content+"|");
        if(content!='' && content!=' '){
            return true;
        }else{
            //alert("#"+name);
            $("#"+name).after('<label class="error" for="">必填字段</label>');
            return false;
        }
        return false;
    },"内容不能为空");
    var validator = $("#fModi").validate({
            ignore: [],
            rules: {
                <?=$rules_str?>
            },
            messages: {
                <?=$messages_str?>
            }
        });

}); 
</script>
<?php }?>
</div>
</article>
<?php if(empty($submit_arr)){?>
<p>&nbsp;</p>
<div class="popwinbottom" >
    <?php if($field_type!='detail'){?>
    <button class="btn btn-gold bg-main button-small"  type="submit"  >&nbsp;确定&nbsp;</button>
    <?php }?>
    <?php if(!isset($noshowClose)||$noshowClose!=1){?>
        <button class="btn"  type="button"  id="closewin">&nbsp;关闭&nbsp;</button>
    <?php }?>
</div>
<?php }?>
</form>
</div>
<script>
 $("#closewin").click(function(){    
    parent.layer.close(parent.layer.getFrameIndex(window.name)); 
});             
</script>