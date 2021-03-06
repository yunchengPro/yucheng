{include file="Pub/header" /}
<script src="/js/transport.js?v=<?php echo date('YmdHis')?>"></script>
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
.nscs-table-handle a:hover.btn-green { background-color: #5BB75B; border-color: #52A452 #52A452 #448944 #52A452;color: #fff;}


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
<div class="page-container">
  <form  id="fModi" method="post"  rel="iframe-form"     id="transport" action="<?=$action?>">
            <div class="fareTplWraper">
                <div class="panel panel-default">
                    <div class="panel-header">满包邮设置</div>
                    <div class="panel-body">
                        <div class="fareTplMain">
                                <div class="row cl ">
                                    <ul>
                                        <li>
                                            <span>包邮方式：</span>
                                            <label>

                                                <input class="sp-on" name="freight_type" <?php if($Freight['freight_type'] == 1){ ?> checked="checked" <?php } ?> value="1"  type="radio">满件包邮
                                            </label>
                                            <label>
                                                <!-- sp-on checked  一起出现-->
                                              
                                                 <input class="sp-on" name="freight_type" <?php if($Freight['freight_type'] == 2){ ?> checked="checked" <?php } ?> value="2"  type="radio">满金额包邮
                                            </label>
                                        </li>
                                        <li>
                                            <span>包邮条件：</span>
                                            满&nbsp;<input name="freight" value="<?=$Freight['freight']?>" class="input-text width250" size="20" type="text"> &nbsp;件/金额
                                           
                                        </li>
                                        <li>
                                            <span>启用状态：</span>
                                            <label>
                                              
                                                  <input class="sp-on" name="enable" <?php if($Freight['enable'] == -1){ ?> checked="checked" <?php } ?> value="-1"  type="radio">禁用
                                            </label>
                                            <label>
                                                <!-- sp-on checked  一起出现-->
                                                <input class="sp-on" name="enable" <?php if($Freight['enable'] == 1){ ?> checked="checked" <?php } ?> value="1"  type="radio">启用
                                            </label>
                                        </li>
                                    </ul>
                            </div>
                            
                        </div>

                    </div>
                </div>
                
                <div class="row cl text-c" style="margin-top: 30px;">
                    <?=$formtoken?>
                    <button class="submit-btn" type="submit" style="margin-right: 20px;">提交</button>
                </div>
            </div>
        </form>

</div>







