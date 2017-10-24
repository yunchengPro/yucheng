            <li>
                <dl class="ncsc-region">
                    <dt class="ncsc-region-title">
                    <span>
                        <input type="checkbox" id="J_Group_1" class="J_Group" value="">
                        <label for="J_Group_1">华东</label>
                    </span>
                    </dt>

                <dd class="ncsc-province-list">
                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$zejiang['id']?>" value="<?=$zejiang['id']?>"/>
                        <label for="J_Province_<?=$zejiang['id']?>"><?=$zejiang['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($zejiang['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>
                    
                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$jiangxi['id']?>" value="<?=$jiangxi['id']?>"/>
                        <label for="J_Province_<?=$jiangxi['id']?>"><?=$jiangxi['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($jiangxi['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$anhui['id']?>" value="<?=$anhui['id']?>"/>
                        <label for="J_Province_<?=$anhui['id']?>"><?=$anhui['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($anhui['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>
    
                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$jiangsu['id']?>" value="<?=$jiangsu['id']?>"/>
                        <label for="J_Province_<?=$jiangsu['id']?>"><?=$jiangsu['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($jiangsu['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$shanghai['id']?>" value="<?=$shanghai['id']?>"/>
                        <label for="J_Province_<?=$shanghai['id']?>"><?=$shanghai['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($shanghai['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>
                  
        
                </dd>
            </dl>
        </li>

        <li class="even">
                <dl class="ncsc-region">
                    <dt class="ncsc-region-title">
                    <span>
                        <input type="checkbox" id="J_Group_2" class="J_Group" value="">
                        <label for="J_Group_2">华北</label>
                    </span>
                </dt>

                <dd class="ncsc-province-list">

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$neimeng['id']?>" value="<?=$neimeng['id']?>"/>
                        <label for="J_Province_<?=$neimeng['id']?>"><?=$neimeng['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($neimeng['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$shanxi['id']?>" value="<?=$shanxi['id']?>"/>
                        <label for="J_Province_<?=$shanxi['id']?>"><?=$shanxi['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($shanxi['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$beijing['id']?>" value="<?=$beijing['id']?>"/>
                        <label for="J_Province_<?=$beijing['id']?>"><?=$beijing['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($beijing['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>
        
                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$hebei['id']?>" value="<?=$hebei['id']?>"/>
                        <label for="J_Province_<?=$hebei['id']?>"><?=$hebei['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($hebei['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>
                    

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$tianjin['id']?>" value="<?=$tianjin['id']?>"/>
                        <label for="J_Province_<?=$tianjin['id']?>"><?=$tianjin['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($tianjin['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                     <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$shandong['id']?>" value="<?=$shandong['id']?>"/>
                        <label for="J_Province_<?=$shandong['id']?>"><?=$shandong['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($shandong['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>


                </dd>
            </dl>
        </li>
        <li class="even">
                <dl class="ncsc-region">
                    <dt class="ncsc-region-title">
                    <span>
                        <input type="checkbox" id="J_Group_3" class="J_Group" value="">
                        <label for="J_Group_3">华中</label>
                    </span>
                    </dt>

                    <dd class="ncsc-province-list">
                       
                        <div class="ncsc-province">
                            <span class="ncsc-province-tab">
                    
                            <input type="checkbox" class="J_Province" id="J_Province_<?=$hubei['id']?>" value="<?=$hubei['id']?>"/>
                            <label for="J_Province_<?=$hubei['id']?>"><?=$hubei['shortname']?></label>
                            <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                            <div class="ncsc-citys-sub">
                                <?php foreach($hubei['city'] as $value){ ?>
                                    <span class="areas">
                                        <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                        <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                    </span>
                                <?php } ?>
                            </div>
                            </span>
                        </div>

                        <div class="ncsc-province">
                            <span class="ncsc-province-tab">
                    
                            <input type="checkbox" class="J_Province" id="J_Province_<?=$henan['id']?>" value="<?=$henan['id']?>"/>
                            <label for="J_Province_<?=$henan['id']?>"><?=$henan['shortname']?></label>
                            <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                            <div class="ncsc-citys-sub">
                                <?php foreach($henan['city'] as $value){ ?>
                                    <span class="areas">
                                        <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                        <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                    </span>
                                <?php } ?>
                            </div>
                            </span>
                        </div>

                        <div class="ncsc-province">
                            <span class="ncsc-province-tab">
                    
                            <input type="checkbox" class="J_Province" id="J_Province_<?=$hunan['id']?>" value="<?=$hunan['id']?>"/>
                            <label for="J_Province_<?=$hunan['id']?>"><?=$hunan['shortname']?></label>
                            <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                            <div class="ncsc-citys-sub">
                                <?php foreach($hunan['city'] as $value){ ?>
                                    <span class="areas">
                                        <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                        <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                    </span>
                                <?php } ?>
                            </div>
                            </span>
                        </div>


                </dd>
            </dl>
    </li>

    <li class="even">
                <dl class="ncsc-region">
                    <dt class="ncsc-region-title">
                    <span>
                        <input type="checkbox" id="J_Group_4" class="J_Group" value="">
                        <label for="J_Group_4">华南</label>
                    </span>
                </dt>
                <dd class="ncsc-province-list">

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$guangxi['id']?>" value="<?=$guangxi['id']?>"/>
                        <label for="J_Province_<?=$guangxi['id']?>"><?=$guangxi['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($guangxi['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$hainan['id']?>" value="<?=$hainan['id']?>"/>
                        <label for="J_Province_<?=$hainan['id']?>"><?=$hainan['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($hainan['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$guangdong['id']?>" value="<?=$guangdong['id']?>"/>
                        <label for="J_Province_<?=$guangdong['id']?>"><?=$guangdong['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($guangdong['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                  <!--   <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$zejiang['id']?>" value="<?=$zejiang['id']?>"/>
                        <label for="J_Province_<?=$zejiang['id']?>"><?=$guangdong['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($guangdong['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div> -->


                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$fujian['id']?>" value="<?=$fujian['id']?>"/>
                        <label for="J_Province_<?=$fujian['id']?>"><?=$fujian['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($fujian['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>


                </dd>
            </dl>
        </li>

        <li class="even">
                <dl class="ncsc-region">
                    <dt class="ncsc-region-title">
                    <span>
                        <input type="checkbox" id="J_Group_5" class="J_Group" value="">
                        <label for="J_Group_5"> 东北</label>
                    </span>
                </dt>

                <dd class="ncsc-province-list">

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$jilin['id']?>" value="<?=$jilin['id']?>"/>
                        <label for="J_Province_<?=$jilin['id']?>"><?=$jilin['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($jilin['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$heilong['id']?>" value="<?=$heilong['id']?>"/>
                        <label for="J_Province_<?=$heilong['id']?>"><?=$heilong['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($heilong['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$liaonin['id']?>" value="<?=$liaonin['id']?>"/>
                        <label for="J_Province_<?=$liaonin['id']?>"><?=$liaonin['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($liaonin['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>


            </dd>
        </dl>
    </li>

    <li class="even">
                <dl class="ncsc-region">
                    <dt class="ncsc-region-title">
                    <span>
                        <input type="checkbox" id="J_Group_6" class="J_Group" value="">
                        <label for="J_Group_6">西北</label>
                    </span>
                </dt>
                <dd class="ncsc-province-list">
                    
                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$ninxia['id']?>" value="<?=$ninxia['id']?>"/>
                        <label for="J_Province_<?=$ninxia['id']?>"><?=$ninxia['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($ninxia['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$xinjiang['id']?>" value="<?=$xinjiang['id']?>"/>
                        <label for="J_Province_<?=$xinjiang['id']?>"><?=$xinjiang['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($xinjiang['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>


                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$qinhai['id']?>" value="<?=$qinhai['id']?>"/>
                        <label for="J_Province_<?=$qinhai['id']?>"><?=$qinhai['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($qinhai['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$gansu['id']?>" value="<?=$gansu['id']?>"/>
                        <label for="J_Province_<?=$zejiang['id']?>"><?=$gansu['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($gansu['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$shangxi['id']?>" value="<?=$shangxi['id']?>"/>
                        <label for="J_Province_<?=$shangxi['id']?>"><?=$shangxi['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($shangxi['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>
                </dd>
            </dl>
        </li>

    <li class="even">
                <dl class="ncsc-region">
                    <dt class="ncsc-region-title">
                    <span>
                        <input type="checkbox" id="J_Group_7" class="J_Group" value="">
                        <label for="J_Group_7">西南</label>
                    </span>
                </dt>
                <dd class="ncsc-province-list">
                    
                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$guizhou['id']?>" value="<?=$guizhou['id']?>"/>
                        <label for="J_Province_<?=$guizhou['id']?>"><?=$guizhou['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($guizhou['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$xizang['id']?>" value="<?=$xizang['id']?>"/>
                        <label for="J_Province_<?=$xizang['id']?>"><?=$xizang['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($xizang['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$yunan['id']?>" value="<?=$yunan['id']?>"/>
                        <label for="J_Province_<?=$yunan['id']?>"><?=$yunan['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($yunan['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$sichuan['id']?>" value="<?=$sichuan['id']?>"/>
                        <label for="J_Province_<?=$sichuan['id']?>"><?=$sichuan['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($sichuan['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$chongqi['id']?>" value="<?=$chongqi['id']?>"/>
                        <label for="J_Province_<?=$chongqi['id']?>"><?=$chongqi['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($chongqi['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                </dd>
            </dl>
        </li>
        <li class="even">
                <dl class="ncsc-region">
                    <dt class="ncsc-region-title">
                    <span>
                        <input type="checkbox" id="J_Group_8" class="J_Group" value="">
                        <label for="J_Group_8">港澳台</label>
                    </span>
                </dt>

                <dd class="ncsc-province-list">

                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$aomen['id']?>" value="<?=$aomen['id']?>"/>
                        <label for="J_Province_<?=$aomen['id']?>"><?=$aomen['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($aomen['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>
                    
                    <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$xianggang['id']?>" value="<?=$xianggang['id']?>"/>
                        <label for="J_Province_<?=$xianggang['id']?>"><?=$xianggang['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($xianggang['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>

                     <div class="ncsc-province">
                        <span class="ncsc-province-tab">
                
                        <input type="checkbox" class="J_Province" id="J_Province_<?=$taiwan['id']?>" value="<?=$taiwan['id']?>"/>
                        <label for="J_Province_<?=$taiwan['id']?>"><?=$taiwan['shortname']?></label>
                        <span class="check_num"></span><i class="icon-angle-down trigger"></i>
                        <div class="ncsc-citys-sub">
                            <?php foreach($taiwan['city'] as $value){ ?>
                                <span class="areas">
                                    <input type="checkbox" class="J_City" id="J_City_<?=$value['id']?>" value="<?=$value['id']?>"/>
                                    <label for="J_City_<?=$value['id']?>"><?=$value['areaname']?></label>
                                </span>
                            <?php } ?>
                        </div>
                        </span>
                    </div>
        </dd>
    </dl>
</li>