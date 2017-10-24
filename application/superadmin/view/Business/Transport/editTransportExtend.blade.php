{include file="Pub/header" /}
<script src="/js/transport.js"></script>
<style type="text/css">
    
/*运费模板选择地区弹出层*/


a.ncsc-btn { font: normal 12px/20px "microsoft yahei"; color: #777; background-color: #F5F5F5; text-align: center; vertical-align: middle; display: inline-block; padding: 4px 12px; border: solid 1px; border-color: #DCDCDC #DCDCDC #B3B3B3 #DCDCDC; cursor: pointer;}
.ks-ext-mask {filter:progid:DXImageTransform.Microsoft.gradient(enabled='true',startColorstr='#BFFFFFFF', endColorstr='#BFFFFFFF');background:rgba(255,255,255,0.75);}
.dialog-areas, .dialog-batch { background-color: #FFF; width: 640px; margin-left: -320px; border: 1px solid #CCC; position: fixed; z-index: 9999; top: 1%; left: 50%;}
.dialog-batch { top: 40%;}
.ks-contentbox { display: block; }
.ks-contentbox .title { font-size: 14px; line-height: 20px; font-weight: bold; color: #555; background-color: #FFF; padding: 10px; border-bottom: solid 1px #E6E6E6; position: relative; z-index: 1;}
a.ks-ext-close { font: lighter 14px/20px Verdana; color: #999; text-align: center; display: block; width: 20px; height: 20px; position: absolute; z-index: 1; top: 10px; right: 10px; cursor: pointer;}
a:hover.ks-ext-close { text-decoration: none; color: #27A9E3;}
.dialog-areas ul { display: block; padding: 10px;}
.dialog-areas li { display: block; width: 100%; clear: none;}
.dialog-areas li.even { background-color: #F7F7F7;}
.ncsc-region { font-size: 0; *word-spacing:-1px/*IE6、7*/; overflow: visible!important;}
.ncsc-region-title { font-size: 12px; line-height: normal!important; vertical-align: top; letter-spacing: normal; word-spacing: normal; text-align: left!important; display: inline-block; padding: 0!important; width:100px!important; }
.ncsc-region-title span { line-height: 20px; color: #333; font-weight: bold; display: block; height: 20px; padding: 5px 0 4px 10px; }
.ncsc-province-list { font-size: 0!important; *word-spacing:-1px/*IE6、7*/; vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; width: 500px!important; padding: 0!important;}
.ncsc-province { font-size: 12px; vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; width: 100px; height: 30px; position: relative; z-index: 1;}
.ncsc-province-tab { line-height: 20px; display: block; height: 20px; padding: 4px; margin: 1px 1px 0 1px;}
.ncsc-province-tab input, .ncsc-province-tab label { vertical-align: middle;}
.ncsc-province-tab .check_num { font: 12px/16px Verdana, Geneva, sans-serif; color: #28B779; letter-spacing: -1px; vertical-align: middle; padding-right: 1px;}
.ncsc-province-tab i { font-size: 12px; color: #CCC; margin-left: 4px; cursor: pointer;}
.ncsc-province-tab:hover i { color: #555;}
.showCityPop { z-index: 2;}
.showCityPop .ncsc-province-tab { background-color: #FFFEC6; margin: 0; border-style: solid; border-width: 1px 1px 0 1px; border-color: #F7E4A5 #F7E4A5 transparent #F7E4A5;}
.ncsc-citys-sub { background-color: #FFFEC6; white-space: normal; display: none; width: 240px; border: 1px solid #F7E4A5; position: absolute; z-index: -1; top: 28px; left: 0;}
.showCityPop .ncsc-citys-sub  { font-size: 0; *word-spacing:-1px/*IE6、7*/; display: block;}
.ncsc-citys-sub .areas { font-size: 12px; line-height: 20px; vertical-align: middle; letter-spacing: normal; word-spacing: normal; display: inline-block; padding: 4px; margin-right: 4px;}
.ks-contentbox .bottom { padding: 10px;text-align: center;padding: 10px;}
.ks-contentbox .batch { line-height: 30px; background-color: #FFF; text-align: center; height: 30px; padding: 20px 0; border-bottom: solid 1px #E6E6E6;}
.checkbox { padding: 0; vertical-align: middle;}
.hidden { display: none;}
a.ncsc-btn-green,
.nscs-table-handle a:hover.btn-green { background-color: #5BB75B; border-color: #52A452 #52A452 #448944 #52A452;}


#J_CityList ul {
    display: block;
    padding: 10px;
}
#J_CityList li {
    clear: none;
    display: block;
    width: 100%;
}
#J_CityList dl {
    border-bottom: 1px dotted #e6e6e6;
    clear: both;
    font-size: 0;
    line-height: 20px;
    margin: 0;
    overflow: hidden;
    padding: 0;
}
.ncsc-region{
    font-size: 0;
    overflow: visible !important;
}
#J_CityList dl dt {
    display: inline-block;
    font-size: 12px;
    letter-spacing: normal;
    line-height: 32px;
    margin: 0;
    padding: 10px 1% 10px 0;
    text-align: right;
    vertical-align: top;
    width: 19%;
    word-spacing: normal;
}
.ncsc-region-title span {
    color: #333;
    display: block;
    font-weight: bold;
    height: 20px;
    line-height: 20px;
    padding: 5px 0 4px 10px;
}
#J_CityList dl dd {
    display: inline-block;
    font-size: 12px;
    letter-spacing: normal;
    line-height: 32px;
    padding: 10px 0;
    vertical-align: top;
    width: 70%;
    word-spacing: normal;
}
.ncsc-province {
    display: inline-block;
    font-size: 12px;
    height: 30px;
    letter-spacing: normal;
    position: relative;
    vertical-align: top;
    width: 100px;
    word-spacing: normal;
    z-index: 1;
}
.ncsc-province {
    display: inline-block;
    font-size: 12px;
    height: 30px;
    letter-spacing: normal;
    position: relative;
    vertical-align: top;
    width: 100px;
    word-spacing: normal;
    z-index: 1;
}
.check_num {
    color: #28b779;
    font: 12px/16px Verdana,Geneva,sans-serif;
    letter-spacing: -1px;
    padding-right: 1px;
    vertical-align: middle;
}
.ncsc-citys-sub {
    background-color: #fffec6;
    border: 1px solid #f7e4a5;
    display: none;
    left: 0;
    position: absolute;
    top: 28px;
    white-space: normal;
    width: 240px;
    z-index: -1;
}
.ncsc-citys-sub .areas {
    display: inline-block;
    font-size: 12px;
    letter-spacing: normal;
    line-height: 20px;
    margin-right: 4px;
    padding: 4px;
    vertical-align: middle;
    word-spacing: normal;
}
#J_CityList li.even {
    background-color: #f7f7f7;
}
.showCityPop {
    z-index: 2;
}
.showCityPop .ncsc-province-tab {
    background-color: #fffec6;
    border-color: #f7e4a5 #f7e4a5 transparent;
    border-style: solid;
    border-width: 1px 1px 0;
    margin: 0;
    display: block;
    height: 20px;
    line-height: 20px;
    margin: 1px 1px 0;
    padding: 4px;
}
.tr {
    text-align: right !important;
}
.close_button{
    background-color: #faa732;
    border-color: #e1962d #e1962d #bb7d25 !important;
    border-style: solid;
    border-width: 1px;
    margin: 0;
    border-color: #dcdcdc #dcdcdc #b3b3b3;
    border-style: solid;
    border-width: 1px;
    color: #FFF;
    text-decoration: none;
    cursor: pointer;
    display: inline-block;
    font: 12px/20px arial;
    height: 20px;
    margin-right: 2px;
    padding: 0 10px;
    text-align: center;
    vertical-align: middle;
    
}




/* end */



.dialog-areas ul { display: block; padding: 10px;}
.dialog-areas li { display: block; width: 100%; clear: none;}
.dialog-areas li.even { background-color: #F7F7F7;}
.ncsc-region { font-size: 0; *word-spacing:-1px/*IE6、7*/; overflow: visible!important;}
.ncsc-region-title { font-size: 12px; line-height: normal!important; vertical-align: top; letter-spacing: normal; word-spacing: normal; text-align: left!important; display: inline-block; padding: 0!important; width:100px!important; }
.ncsc-region-title span { line-height: 20px; color: #333; font-weight: bold; display: block; height: 20px; padding: 5px 0 4px 10px; }
.ncsc-province-list { font-size: 0!important; *word-spacing:-1px/*IE6、7*/; vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; width: 500px!important; padding: 0!important;}
.ncsc-province { font-size: 12px; vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; width: 100px; height: 30px; position: relative; z-index: 1;}
.ncsc-province-tab { line-height: 20px; display: block; height: 20px; padding: 4px; margin: 1px 1px 0 1px;}
.ncsc-province-tab input, .ncsc-province-tab label { vertical-align: middle;}
.ncsc-province-tab .check_num { font: 12px/16px Verdana, Geneva, sans-serif; color: #28B779; letter-spacing: -1px; vertical-align: middle; padding-right: 1px;}
.ncsc-province-tab i { font-size: 12px; color: #CCC; margin-left: 4px; cursor: pointer;}
.ncsc-province-tab:hover i { color: #555;}
.showCityPop { z-index: 2;}
.showCityPop .ncsc-province-tab { background-color: #FFFEC6; margin: 0; border-style: solid; border-width: 1px 1px 0 1px; border-color: #F7E4A5 #F7E4A5 transparent #F7E4A5;}
.ncsc-citys-sub { background-color: #FFFEC6; white-space: normal; display: none; width: 240px; border: 1px solid #F7E4A5; position: absolute; z-index: -1; top: 28px; left: 0;}
.showCityPop .ncsc-citys-sub  { font-size: 0; *word-spacing:-1px/*IE6、7*/; display: block;}
.ncsc-citys-sub .areas { font-size: 12px; line-height: 20px; vertical-align: middle; letter-spacing: normal; word-spacing: normal; display: inline-block; padding: 4px; margin-right: 4px;}
.ks-contentbox .bottom { padding: 10px;}
.ks-contentbox .batch { line-height: 30px; background-color: #FFF; text-align: center; height: 30px; padding: 20px 0; border-bottom: solid 1px #E6E6E6;}
.checkbox { padding: 0; vertical-align: middle;}
.hidden { display: none;}

.trans-line{float: left;display: block;width: 100%}
</style>
<div class="admin">
    <div class="tab">
        <div class="tab-head">
            <ul class="tab-nav">
                <li class="active"><a href="#tab-set"><?=$this->actionname?>运费模板</a></li>
            </ul>
        </div>
        <div class="tab-body">
            <br />
            <div class="tab-panel active" id="tab-set">
                <form method="post" class="form-x" id="transport" action="/setup/transport/save">

                 

                    <div class="form-group">
                            <label>模板名称</label>
                        <div class="field">
                            <div class="button-group button-group-small radio">
                                <input type="text" class="input-text" size="50" id="title" name="title" value="<?=$info['title']?>">
                                <p class="J_Message" style="display: none;" error_type="title"><i class="icon-exclamation-sign"></i>必须填写模板名称</p>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label>计价方式</label>
                        <div class="field">
                            <div class="button-group button-group-small radio">

                                <input name="valuation_type" value="1" id="j_number" type="radio"><label for="j_number">&nbsp;按件数&nbsp;</label>
                                <input name="valuation_type" checked="" value="2" id="j_weight" type="radio"><label for="j_weight">&nbsp;按重量&nbsp;</label>
                                <input name="valuation_type" value="3" id="j_volume" type="radio"><label for="j_volume">&nbsp;按体积&nbsp;</label>

                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <label>运送方式</label>
                        <div class="field">
                            <div class="button-group button-group-small radio">
                                除指定地区外，其余地区的运费采用"默认运费"
                            </div>
                        </div>
                    </div>



                    <div class="form-group trans-line">
                        <div class="label">
                            <label></label>
                        </div>
                        <div class="field">
                            <div class="button-group button-group-small radio" id="express">

                                <script>

                                    showExpress(<?=$info['valuation_type']?>);

                                </script>

                            </div>

                            <div class="tbl-attach" style="margin: 0 10px;color:blue;">
                                <div class="J_SpecialMessage"></div>
                                <a href="javascript:;" id="j_addrule" style="color:blue;">为指定地区城市设置运费</a>
                            </div>

                        </div>
                    </div>

                    <div class="ks-ext-mask" style="position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 999; display:none"></div>

                    <div id="dialog_areas" class="dialog-areas" style="display:none">
                        <div class="ks-contentbox">
                            <div class="title">选择区域<a class="ks-ext-close" href="javascript:void(0)">X</a></div>
                            <ul id="J_CityList">
                                {include file="Business/Transport/format_area" /}
                            </ul>
                            <div class="bottom">
                                <a href="javascript:void(0);" class="J_Submit ncsc-btn ncsc-btn-green">
                                    确定
                                </a>
                                <a href="javascript:void(0);" class="J_Cancel ncsc-btn">
                                    取消
                                </a>
                            </div>
                        </div>
                    </div>


                    <div class="form-button">
                        <?=$this->formtoken?>
                        <input type="hidden" name="transport_id" value="<?=$info['id']?>" />
                        <input class="button bg-main" type="submit" />
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!--
<script type="text/javascript">
    var i = 0;
    $("#btn_add_clone").click(function(){
        var html = '<tr><td></td><td><input class="input-text" style="width:50px" type="text" name="at_value[s_'+i+'][sort]" value="0" placeholder="排序" /></td><td><input class="input-text" style="width:100px" type="text" name="at_value[s_'+i+'][name]" size="50" placeholder="属性名称" /></td><td><input class="input-text" style="width:350px" type="text" name="at_value[s_'+i+'][value]" size="100" placeholder="属性可选值，用英文逗号隔开" /></td><td><input style="width:20px" type="checkbox" checked value="1"  name="at_value[s_'+i+'][show]" /></td><td><a style="color:blue" class="btns">删除</a></td></tr>';

        $("#list-table").append(html);
        i++;
    });



    $('.btns').live('click',function(){
        $(this).parent().parent().remove();
    });

    $('.change_default_submit_value').change(function(){
        $(this).parents('tr:first').find("input[nc_type='submit_value']").val('ok');
    });

</script>
-->



<script>
    $(function(){

        <?php if (is_array($transport_extend)){ ?>

            <?php foreach($transport_extend as $tk=>$tv){ ?>

                <?php if($tv['is_default'] == 1){ ?>
                    var cur_tr = $('.tbl-except').prev();

                    $(cur_tr).find('input[data-field="start"]').val('<?php echo $tv['snum'];?>');
                    $(cur_tr).find('input[data-field="postage"]').val('<?php echo $tv['sprice'];?>');
                    $(cur_tr).find('input[data-field="plus"]').val('<?php echo $tv['xnum'];?>');
                    $(cur_tr).find('input[data-field="postageplus"]').val('<?php echo $tv['xprice'];?>');

                <?php }else{ ?>
                    r_html = rule();

                    $("#list-table").append(r_html);

                    var cur_tr = $('.tbl-except').find('table').find('tr:last');

                    $(cur_tr).find('.area-group>p').html('<?php echo $tv['area_name'];?>');

                    $(cur_tr).find('input[type="hidden"]').val('<?php echo trim($tv['area_id'],',');?>|||<?php echo $tv['area_name'];?>');

                    $(cur_tr).find('input[data-field="start"]').val('<?php echo $tv['snum'];?>');
                    $(cur_tr).find('input[data-field="postage"]').val('<?php echo $tv['sprice'];?>');
                    $(cur_tr).find('input[data-field="plus"]').val('<?php echo $tv['xnum'];?>');
                    $(cur_tr).find('input[data-field="postageplus"]').val('<?php echo $tv['xprice'];?>');

                <?php } ?>

            <?php } ?>

        <?php } ?>

    });
</script>



