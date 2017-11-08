<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>我的订单</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="renderer" content="webkit">
        <meta name="author" content="talon">
        <meta name="application-name" content="niuniuhui-wap">
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <meta name="format-detection" content="telephone=no" />
        {include file="Pub/assetcss" /}
        {include file="Pub/assetjs" /}
    </head>
    <body >
        <div id="app">
            <header class="page-header">    
                <div class="page-bar">  
                    <a href="javascript:history.go(-1)">
                        <span class="back-ico"></span>
                    </a>
                    <span class="bar-title">确认订单</span>
                </div>
            </header>
            
            
            
            <section class="order-bolck address-line">
                <div class="receipt">
                    <div>收货人：<span v-html="loginstics.realname"></div>
                    <div><span v-html="loginstics.mobile"></span></div>
                </div>
                <a href=""><div class="receipt-address tl-ellipsis-2 pos-r" style="padding-right: 18px;">
                    <span v-html="addres_detail"></span>
                    <i></i>
                </div>
                </a>
                <div class=""></div>
            </section>
            
            <!--没有收货地址-->
            <!--<section class="order-bolck address-line">
                <a href="#">
                <div class="receipt-address tl-ellipsis-2 pos-r" style="padding-right: 18px;">
                    <div class="c-666" style="margin-top: 5px;">暂无地址</div>
                    <i></i>
                </div>
                <div class=""></div>
                </a>
            </section>-->
            
            <section class="order-bolck goods-order" style="padding: 0;">
                
                <div class="order-body">
                    <div class="order-list">
                        <!--已付款-->
                        <div class="one-order" v-for="(item,index) in orderlist">
                            <div class="shop-stuas">
                                <a href="" class="pos-r"><!-- <img src="../../img/icon/shop@2x.png" style="width: 25px;margin-right: 10px;"/> --><span v-html="item.businessname"></span><i></i></a>
                                <!--<div class="order-stuas">已付款</div>-->
                            </div>
                            
                            <div v-for="product in item.productlist">
                                <div class="order-info-box">
                                    <div class="order-img">
                                        <img :src="product.productimage" />
                                    </div>
                                    <div class="order-info">
                                        <div class="name tl-ellipsis-2" v-html="product.productname"></div>
                                        <div :id="product.skuid" class="c-999 sepcifi" v-html="product.skudetail"></div>
                                        <div class="text-r">X<span v-html="product.productnum">0</span></div>
                                        <div class="order-time"><span v-html="product.prouctprice">0.00</span>元</div>
                                    </div>
                                    
                                </div>
                                <div>
                                    <div class="sure-item" style="-webkit-box-pack: justify;border-bottom: 1px solid #DDDDDD;">
                                        <div>购买数量</div>
                                        <div class="">
                                            <span class="minus" @click="minus(index,product.skuid)">-</span>
                                            <span class="number" v-html="product.productnum"></span>
                                            <span class="plus" @click="plus(index,item.skuid)">+</span>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                          
                            
                            <div>
                                <div class="sure-item" style="-webkit-box-pack: justify;">
                                    <div>店铺邮费</div>
                                    <div><span v-html="item.actualfreight">0.00</span>元</div>
                                </div>
                                <div class="sure-item">
                                    <div>买家留言</div>
                                    <div><input type="text" class="remark" :id="'product-businessid-remark-'+item.businessid" placeholder="选填，对本次交易的说明"/></div>
                                </div>
                                
                                
                            </div>
                            
                            
                            <div class="goods-order-total" style="-webkit-box-pack: end;">
                                
                                <div>共<span v-html="item.productnum">0</span>件商品&nbsp;&nbsp;小计： <span class="red" v-html="totalPrice">0</span>元</div>
                            </div>
                        </div>
                        
                        
                        
                    </div>
                </div>
            </section>
            
            <footer class="goods-order-oper" style="-webkit-box-pack: end;padding: 0;">
                            合计：<span class="red" v-html="totalPrice">0</span>元
                            <a href="#" class="sub-btn" @click="suborder">提交订单</a>
            </footer>
            
            
            
            
            
            
        </div>
        
        
        <script>
            var Vm = new Vue({
                el:'#app',
                data:{
                    checktoken:"<?=$checktoken?>",
                    cartitemids:"<?=$cartitemids?>",
                    skuid:"<?=$skuid?>",
                    productnum:"<?=$productnum?>",
                    logisticsid:"<?=$logisticsid?>",
                    loginstics:{},
                    orderlist:[],
                    total:"",
                    totalactualfreight:"",
                    totalamount:"",
                    totalcount:"",
                    addres_detail:"",
                    addorderkey:"<?=$addorderkey?>",
                    sign:"",
                    items:"",
                    cho_index:0
                },
                mounted:function(){
                    
                    $(".plus").click(function(){
                        var num=parseInt($(".number").html());
                        num++;
                        $(".number").html(num);
                        
                    });
                    $(".minus").click(function(){
                        var num=parseInt($(".number").html());
                        if(num>1){
                            num--;
                            $(".number").html(num);

                        }
                        
                        
                    });

                    this.getshoworder();
                },
                computed:{
                    //合计总价
                    totalPrice:function(){
                        var _this=this;
                        var total = 0;
                        
                        this.orderlist[_this.cho_index].productlist.forEach(function(good){
                            
                    
                            total += good.prouctprice * good.productnum;


                        });
                        return total;
                    },
                    //结算商品数量
                    checkNum:function(){
                        var num = 0;
                        
                        this.orderlist[_this.cho_index].productlist.forEach(function(good){
                            
                            
                            num+=good.productnum;
                            

                        });
                        return num;
                    }
                
                },
                methods:{
                    minus:function(idx,skuid){
                        this.cho_index = idx;
                        if(this.orderlist[idx].productlist){
                            //判断是否是1
                            if(this.orderlist[idx].productlist[idx].productnum>1){
                                this.orderlist[idx].productlist[idx].productnum--;
                               
                            }
                            
                        }
                    },
                    plus:function(idx,skuid){

                        this.cho_index = idx;
                        if(this.orderlist[idx].productlist){
                            //判断库存
                            if(this.orderlist[idx].productlist[idx].productnum<this.orderlist[0].productlist[idx].productstorage){
                                this.orderlist[idx].productlist[idx].productnum++;

                            }
                            
                        }
                    },
                    getshoworder:function() {
                        var _this = this;
                        //loadtip({content:'提交中'});

                        var addUrl = "/order/index/getshoworder";
                        _this.$http.post(addUrl,{
                            cartitemids:_this.cartitemids,
                            skuid:_this.skuid,
                            productnum:_this.productnum,
                            logisticsid:_this.logisticsid,
                            checktoken:_this.checktoken
                        }).then(
                            function(res) {
                                data = cl(res);
                                //console.log(data);
                                if(data.code == "200") {
                                    
                                    _this.loginstics            = data.data.loginstics;
                                    _this.orderlist             = data.data.orderlist;
                                    _this.total                 = data.data.total;
                                    _this.totalactualfreight    = data.data.totalactualfreight+"元";
                                    _this.totalamount           = data.data.totalamount;
                                    _this.totalcount            = data.data.totalcount;

                                    _this.addres_detail         = data.data.loginstics.city+data.data.loginstics.address;


                                    //console.log(_this.loginstics.address_id);
                                    
                                    _this.logisticsid           = _this.loginstics.address_id;
                                    
                                    //console.log(_this.logisticsid);
                                } else {
                                    loadtip({
                                        close:true,
                                        alert:data.msg
                                    });
                                    // toast(data.msg);
                                }
                            }, function(res) {
                                loadtip({
                                    close:true,
                                    alert:'操作有异'
                                });
                                // toast("操作有异");
                            }
                        );
                    },
                    suborder:function(){
                        var _this = this;
                        loadtip({content:'提交中'});

                        var items = [];

                        for(var i in _this.orderlist){

                            for(var j in _this.orderlist[i].productlist){
                                items.push({
                                    productid:_this.orderlist[i].productlist[j].productid,
                                    skuid:_this.orderlist[i].productlist[j].skuid,
                                    productnum:_this.orderlist[i].productlist[j].productnum,
                                    remark:$("#product-businessid-remark-"+_this.orderlist[i].productlist[j].businessid).val()
                                });
                            }
                        }

                        _this.items = JSON.stringify(items);
                        //console.log(_this.items);
                        console.log({
                            address_id:_this.logisticsid,
                            items:_this.items,
                            addorderkey:_this.addorderkey
                        });
                        
                        var addUrl = "/order/index/getsign";
                        _this.$http.post(addUrl,{
                            address_id:_this.logisticsid,
                            items:_this.items,
                            addorderkey:_this.addorderkey
                        }).then(
                            function(res) {
                                data = cl(res);
                                
                                if(data.code == "200") {
                                    
                                    _this.sign = data.data.sign;
                                    // 提交订单
                                    _this.addorder();

                                } else {
                                    loadtip({
                                        close:true,
                                        alert:data.msg
                                    });
                                    // toast(data.msg);
                                }
                            }, function(res) {
                                loadtip({
                                    close:true,
                                    alert:'操作有异'
                                });
                                // toast("操作有异");
                            }
                        );
                    },
                    addorder:function(){
                        var _this = this;
                        //loadtip({content:'提交中'});

                        var addUrl = "/order/index/addorder";
                        _this.$http.post(addUrl,{
                            sign:_this.sign,
                            address_id:_this.logisticsid,
                            items:_this.items,
                            addorderkey:_this.addorderkey,
                            checktoken:_this.checktoken
                        }).then(
                            function(res) {
                                //console.log(res);
                                data = cl(res);
                                //console.log("----------");
                                console.log(data);
                                if(data.code == "200") {
                                
                                    // 跳转到支付页
                                    window.location.href="/sys/pay/paymethod?orderno="+data.data.orderids;

                                } else {
                                    loadtip({
                                        close:true,
                                        alert:data.msg
                                    });
                                    // toast(data.msg);
                                }
                            }, function(res) {
                                loadtip({
                                    close:true,
                                    alert:'操作有异'
                                });
                                // toast("操作有异");
                            }
                        );
                    }
                }
            });
        </script>
    </body>
</html>
