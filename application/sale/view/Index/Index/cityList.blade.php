{include file="Pub/header" /}
		
		<header class="city-toper">
			<a href="/" class="go-back"></a>
			<span>城市选择</span>
		</header>
		<section class="search-city-wrap">
			<input type="text" placeholder="输入城市名查询" v-model.trim="searchCity"/>
			<div class="current">
				<span>当前定位城市：</span>
				<a :href="'/index/index/index?city_id='+city_id" v-html="city"><i></i></a>
			</div>
			
		</section>
		<section class="hot-city-list">
			<div class="hot-tip">热门城市</div>
			<div class="hot-citys">
				<?php foreach($data['data']['hot'] as $value){ ?>
					<div class="hot-city-item">
						<a href="/index/index/index?city_id=<?=$value['id']?>"><?=$value['name']?></a>
					</div>
				<?php  } ?>
			
			</div>
		</section>
		
		<section class="sort-city">
		
				<div class="one-sort" v-for="(city,index) in cityList">
					<div class="letter" :id="index" v-html="index"></div>
					<div class="letter-city">
						
						 <div class="city-item" v-for="cityname in city">
							<a :href="'/index/index/index?city_id='+cityname.id">{{ cityname.name }}</a>
						</div> 
						
					</div>
				</div>
			<!-- <div class="one-sort">
				<div class="letter" id="A">A</div>
				<div class="letter-city">
					<div class="city-item">
						<a href="#">安庆市</a>
					</div>
				</div>
			</div>
			
			<div class="one-sort">
				<div class="letter" id="B">B</div>
				<div class="letter-city">
					<div class="city-item">
						<a href="#">保定市</a>
					</div>
					<div class="city-item">
						<a href="#">保定市</a>
					</div>
					
					<div class="city-item">
						<a href="#">保定市</a>
					</div>
				</div>
			</div>
			
			<div class="one-sort">
				<div class="letter" id="C">C</div>
				<div class="letter-city">
					<div class="city-item">
						<a href="#">安庆市</a>
					</div>
				</div>
			</div>
			<div class="one-sort">
				<div class="letter" id="D">D</div>
				<div class="letter-city">
					<div class="city-item">
						<a href="#">大同市</a>
					</div>
					<div class="city-item">
						<a href="#">大连市</a>
					</div>
					<div class="city-item">
						<a href="#">大庆市</a>
					</div>
				</div>
			</div>
			<div class="one-sort">
				<div class="letter" id="E">E</div>
				<div class="letter-city">
					<div class="city-item">
						<a href="#">大同市</a>
					</div>
					<div class="city-item">
						<a href="#">大连市</a>
					</div>
					<div class="city-item">
						<a href="#">大庆市</a>
					</div>
				</div>
			</div>
			<div class="one-sort">
				<div class="letter" id="F">F</div>
				<div class="letter-city">
					<div class="city-item">
						<a href="#">大同市</a>
					</div>
					<div class="city-item">
						<a href="#">大连市</a>
					</div>
					<div class="city-item">
						<a href="#">大庆市</a>
					</div>
				</div>
			</div>
			
			<div class="one-sort">
				<div class="letter" id="Z">Z</div>
				<div class="letter-city">
					<div class="city-item">
						<a href="#">大同市</a>
					</div>
					<div class="city-item">
						<a href="#">大连市</a>
					</div>
					<div class="city-item">
						<a href="#">大庆市</a>
					</div>
				</div>
			</div> -->
			
		</section>
		
		<div class="letters-list">
			<a href="#A">A</a>
			<a href="#B">B</a>
			<a href="#C">C</a>
			<a href="#D">D</a>
			<a href="#E">E</a>
			<a href="#F">F</a>
			<a href="#G">G</a>
			<a href="#H">H</a>
			<a href="#I">I</a>
			<a href="#J">J</a>
			<a href="#K">K</a>
			<a href="#L">L</a>
			<a href="#M">M</a>
			<a href="#N">N</a>
			<a href="#O">O</a>
			<a href="#P">P</a>
			<a href="#Q">Q</a>
			<a href="#R">R</a>
			<a href="#S">S</a>
			<a href="#T">T</a>
			<a href="#U">U</a>
			<a href="#V">V</a>
			<a href="#W">W</a>
			<a href="#X">X</a>
			<a href="#Y">Y</a>
			<a href="#Z">Z</a>
			
		</div>
		<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=dcc7a8bcf5dd15a02336fa3fa263d724"></script>
		<script>
            
            var Vm = new Vue({
                el: '#app',
                data: {
                  	searchCity:'',
                    city:'',
                    city_id:'',
                    cityList:[]
                },
                created:function(){
                   
                    

                },
                mounted: function() {
                    var _this=this;
                    // 页面初始化时执行的方法
                    
                    _this.maps();
                    //a-z 城市列表
                    _this.getCityjson();
                   
                },
                methods: {
                    
                    maps:function(){
                        var _this = this;
                       
                        //加载地图，调用浏览器定位服务
                        _this.map = new AMap.Map('container', {
                            resizeEnable: true
                        });
                        _this.map.plugin('AMap.Geolocation', function() {
                            _this.geolocation = new AMap.Geolocation({
                                enableHighAccuracy: true,//是否使用高精度定位，默认:true
                                timeout: 10000,          //超过10秒后停止定位，默认：无穷大
                                buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
                                zoomToAccuracy: true,      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
                                buttonPosition:'RB'
                            });
                            _this.map.addControl(_this.geolocation);
                            _this.geolocation.getCurrentPosition();

                            AMap.event.addListener(_this.geolocation, 'complete', _this.onComplete);//返回定位信息
                            AMap.event.addListener(_this.geolocation, 'error', _this.onError);      //返回定位出错信息
                        });
                    },
                    onComplete:function(data){
                       
                        var _this = this;
                        var str='定位成功';
                        
                        _this.lngx =  data.position.getLng();
                        _this.laty =  data.position.getLat();
                       
                        _this.getCityInfo();
                        
                    },
                    onError:function(data){
                        var str='定位失败';
                        var _this = this;
                        _this.lngx =  '113.94303';//data.position.getLng();
                        _this.laty =  '22.54023';//data.position.getLat();
                        if(_this.city_id ==''){
                            _this.city_id ='440305';
                        }
                        _this.getCityInfo();

                    },getCityInfo:function(){
                        var _this=this;
                       
                        _this.$http.post('/index/index/getLngLatAddress',{
                            
                            lngx:_this.lngx,
                            laty:_this.laty
                        }).then(
                            function (res) {
                                // 处理成功的结果
                                //console.log(res);
                               
                                data = eval("("+res.body+")");
                                //console.log(data);
                                if(data.code=='200'){

                                    if(_this.city ==''){
                                        _this.city = data.data.city;
                                    }
                                    if(_this.city_id ==''){
                                        _this.city_id = data.data.citycode;
                                    }
                                   
                                }else{
                                    layer.open({
                                        content: data.msg,
                                        skin: 'msg',
                                        time: 2 
                                    });
                                    return false;
                                }   
                               
                            },function (res) {
                                // 处理失败的结果
                                console.log(res);
                            }
                        );

                    },getCityjson:function(){
                    	var _this=this;
                       
                        _this.$http.post('/index/index/getCityjson',{
                            keyword : _this.searchCity
                          
                        }).then(
                            function (res) {
                                // 处理成功的结果
                                //console.log(res);
                               
                                data = eval("("+res.body+")");
                               // console.log(data);
                                if(data.code=='200'){

                                 	_this.cityList  = data.data;
                                 	//console.log(_this.cityList);

                                 	// for(var key in data.data){
                                 	// 	Vm.cityList[key]=data.data[key];
                                 	// } 
                                 	console.log(_this.cityList);
                                 	// _this.cityList=[
	                                 // 	{
	                                 // 		"id":1,
	                                 // 		"name":"北京"
	                                 // 	},
	                                 // 	{
	                                 // 		"id":2,
	                                 // 		"name":"上海"
	                                 // 	}
                                 	// ];
                                   
                                }else{
                                    layer.open({
                                        content: data.msg,
                                        skin: 'msg',
                                        time: 2 
                                    });
                                    return false;
                                }   
                               
                            },function (res) {
                                // 处理失败的结果
                                console.log(res);
                            }
                        );
                    }
                    
                },
				watch:{
					//监听
					 searchCity:{
						
					 	handler:function(val,oldVal){
					 		var _this = this;
							this.getCityjson();
							
					 	}
					 }
				},
				computed: {
				    /*searchData: function() {
				      var searchCity = this.searchCity;
				
				      if (searchCity) {
				        // return this.cityList.filter(function(city) {
				        //    return Object.keys(city).some(function(key) {
				        //    return String(city[key]).toLowerCase().indexOf(searchCity) > -1
				        //    })
				        //   //return String(city["name"]).indexOf(searchCity) > -1
				        // })
				      	return Vm.cityList.filter(function(item,index,arr){
  							
 								return String(item["name"]).indexOf(searchCity) > -1
 							
  						})

				        
				      }
				
				      return Vm.cityList;
				    }*/
			  }
            });
            
        </script>
{include file="Pub/tail" /}
