<!DOCTYPE html>
<html>
<head>
    <title>华车网</title> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta name="description" content="华车网" />
    <meta name="keywords" content="华车网" />
    <meta name="author" content="llm" />
    <link href="themes/bootstrap.css" rel="stylesheet" />
    <link href="themes/common.css" rel="stylesheet" />
    <link href="themes/theme.css" rel="stylesheet" />
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body ms-controller="web">
    <div id="vue">
        <!--省份城市 start-->
        <div v-cloak v-show="isShowProvince" id="SelectTimeWin" class="popupbox p-city-wrapper">
            <div class="popup-title popup-title-white psr">
                <span class="fl fs16"><b>选择城市</b></span>
                <dl class="letter-nav fl">
                    <dd>
                        <a @click="position" v-for="(letter,tag) in areaList" :href="'#NAV-'+tag"><span class="">@{{tag}}</span></a>
                    </dd>
                </dl>
                <i @click="showProvince"></i>
                <div class="clear inline"></div>
            </div>
            <div class="popup-wrapper notopbottompadding">
                <div class="popup-content nopadding">
                    <div class="clear"></div>
                    <div id="province-city-select">
                         
                        <dl v-for="(letter,tag) in areaList" class="province-city-select-wrapper" >
                            <span class="fl juhuang province inline-block weight" :id="'NAV-'+tag">@{{tag}}</span>
                            <div class="box inline-block">
                                <div class="box mb10" v-for="province in letter">
                                    <dt> 
                                        <label class="fl ml10">
                                            <span class="fl ml5 c72 province">@{{province.area_name}}</span>
                                            <div class="clear inline"></div>
                                        </label>
                                        <span class="clear"></span>
                                    </dt>
                                    <dd>
                                        <ul class="notype nopadding persent area-city-list">
                                            <li @click.stop-3="selectCity(city.area_id,city.area_name)" @click.stop-2="setProvince(province.area_name)"  v-for="city in province.child" class="city" :data-id="city.area_id" :data-value="city.area_name">
                                                <span class="fl ml5">@{{city.area_name}}</span>
                                                <span class="clear"></span>
                                            </li> 
                                        </ul>
                                    </dd>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </dl>
                        <p :class="{hasnone:false,fs14:true,red:false}">不含港澳台</p>
                        <br v-for="i in 15"> 
                    </div>
                    
                </div>
                <div class="popup-control">
                    <div class="clear mt10"></div>
                </div>
            </div>
        </div> 
        <!--省份城市 end-->
    <form name="indexSearchForm" action="{{ route('searchQuery') }}" data-txt="请选择">
        <nav class="navbar navbar-inverse navbar-fixed-top" >
            <div class="container">
                <div id="navbar" class="collapse navbar-collapse">
                    <div class="navbar-header pos-rlt">
                        <a class="navbar-brand logo" href="/">华车网</a>
                        <span v-cloak class="location pos-abt" @click="showCityList">
                            <label>@{{city}}</label>
                            <input type="hidden" name="area" v-model="cityId">
                            <i></i> 
                        </span>
                        <div v-cloak class="loca-c" v-show="isShowCityList">
                            <a v-for="city in cityList" href="javascript:;" @click="selectCity(city.area_id,city.area_name)">@{{city.area_name}}</a>
                            <div v-cloak class="box clear block text-right">
                                <a href="javascript:;"  @click.stop-2="setCityValIndex(0)" @click.stop-1="showProvince">更多城市&gt;&gt;</a>
                            </div>
                        </div>
                    </div>
                    <ul class="nav navbar-nav">
                        <li class=""><a href="#home">首页</a></li>
                        <li class=""><a href="#maiche">买车流程</a></li>
                        <li><a href="#baozhang">诚信保障</a></li>
                        <li><a href="#services">服务中心</a></li>
                    </ul>
                    <ul class="nav navbar-nav control">
                        <li class=""><a ms-click="login" href="javascript:;">快速登陆</a><i></i></li>
                        <li><a href="reg-phone.html">快捷注册</a></li>
                    </ul>
                </div>

            </div>
        </nav>


        <div class="container m-t-86 pos-rlt">
            <div class="banner">
                
            </div>
            <div class="i-search pos-abt text-center-xs">
                <div class="tip">
                    <ul id="maquee">
                        <li>有29,586人正在这里买车</li>
                        <li>有29,586人正在这里注册</li>
                        <li>有29,586人正在这里关注</li>
                        <li>有29,586人正在这里游玩</li>
                    </ul>
                </div>
                    <ul class="i-s-panel">
                        <li class="brand">
                            <div class="wapper" v-cloak >
                                <label>品牌:</label>
                                <span class="errors brand-error">@{{search.brandError}}</span>
                                <input name="" type="text" onfocus="this.blur()" v-model="search.brand" />
                                <input name="" type="hidden"  v-model="search.brandId" />
                                <i @click="showBrand"></i>
                                <dl v-cloak v-show="search.isShowBrand" class="auto">
                                    <dt :id="id" v-for="(brand,id) in search.brandList" @click="selectBrand(brand.gc_id,brand.gc_name,brand.gc_id)"><em><img :src="'{{env('UPLOAD_URL')}}/'+brand.detail_img" /></em><a href="javascript:;">@{{brand.gc_name}}</a></dt>
                                </dl>
                            </div>
                        </li>

                        <li class="cars">
                            <div class="wapper"  v-cloak >
                                <label>车系:</label>
                                <span class="errors">@{{search.carSeriesError}}</span>
                                <input name="" type="text" onfocus="this.blur() " v-model="search.carSeries" />
                                <input name="" type="hidden"  v-model="search.carSeriesId" />
                                <i @click="showCarSeries"></i>
                                <dl v-cloak v-show="search.isShowCarSeries" class="auto">
                                    <dd v-for="(cars,id) in search.carSeriesList" @click="selectCar(cars.gc_id,cars.gc_name)"><a href="javascript:;" class="overhide">@{{cars.gc_name}}</a></dd>
                                </dl>
                            </div>
                        </li>

                        <li class="models">
                            <div class="wapper" v-cloak >
                                <label>车型:</label>
                                <span class="errors">@{{search.vehicleModeError}}</span>
                                <input name="" type="text" onfocus="this.blur()"  v-model="search.vehicleMode" />
                                <input name="car" type="hidden"  v-model="search.searchVehicleModelId" />
                                <i @click="showVehicleModel"></i>
                                <dl v-show="search.isShowVehicleModel" class="auto">
                                    <dd v-for="(cars,id) in search.vehicleModeList" @click="selectModel(cars.gc_id,cars.gc_name,id)"><a href="javascript:;">@{{cars.gc_name}}</a></dd>
                                </dl>
                                
                            </div>
                        </li>

                        <li class="i-s-b">
                            <div class="wapper">
                                <button v-cloak  type="button" @click="searchForm" @mousedown="showHideCountDownBg" @mouseup="showHideCountDownBg" title="点击搜索"></button>
                            </div>
                        </li>
                    </ul>
                    <div class="search-info">
                        <p><span class="juhuang">温馨提示：</span>华车所示均为品牌官方授权4S店实时车辆报价 </p>
                        <p class="pl80">每日上架销售时段（北京时间）： 09:00-12:00 </p>
                        <p class="pl324">13:00-17:00</p>
                        <div :class="{countdown:true,disabled:isShowHideCountDownBg}" v-show="isCountDown">
                            <div class="time-wrapper" v-cloak >
                                <span class="text">距开始还有</span>
                                <span>@{{time.hours[0]}}</span>
                                <span>@{{time.hours[1]}}</span>
                                <span class="symbol"><span>:</span></span>
                                <span>@{{time.minites[0]}}</span>
                                <span>@{{time.minites[1]}}</span>
                                <span class="symbol"><span>:</span></span>
                                <span>@{{time.seconds[0]}}</span>
                                <span>@{{time.seconds[1]}}</span>
                                <div class="clear"></div>
                            </div>
                        </div> 
                    </div>
                </form>
            </div>


            <div class="floor">
                <div class="hight">
                    <ul>
                        <li class="hight-1"></li>
                        <li class="hight-2"></li>
                        <li class="hight-4"></li>
                        <li class="hight-3"></li>
                        <div class="clear"></div>
                    </ul>
                </div>
            </div>

            <div class="floor">
                <div class="loading"><img src="themes/images/index/info-1.gif" /></div>
                <div class="info info-1">
                    <h2>全新透明比价模式</h2>
                    <p>
                        在我们这里，采用透明比价模式，报价以一口价呈现在您面前，
                        无须费力讨价还价即可找到满意价格。我们在行业中开创了新
                        车车价透明化比价模式的先河，结合“汽车信用宝”双向担保
                        体系，建立一站式新车预订平台，节约了大量的中间交易成本。
                    </p>
                    <a href="#" class="more">了解更多 &gt;&gt;</a>
                </div>
            </div>

            <div class="floor">
                <div class="loading"><img src="themes/images/index/info-2.gif" /></div>
                <div class="info info-2">
                    <h2>一站式新车预定平台</h2>
                    <p>
                        我们平台发布销售信息的车辆，均是来自正规渠道、手续合法齐全的新车，车源
                        有保障。而且将给您的权益更多惊喜保障，助您牢牢把握购车主动权。我们更为
                        您配备专属客服，以细心细致的专业服务，为您购车全程保驾护航。
                    </p>
                    <a href="#" class="more">了解更多 &gt;&gt;</a>
                </div>
            </div>

            <div class="floor">
                <div class="loading"><img src="themes/images/index/info-3.gif" /></div>
                <div class="info info-3">
                    <h2>异地购车</h2>
                    <p>
                        异地购车，是指消费者在本地以外区域购买汽车
                        产品，回到本地上牌和使用的一种现象。只要所
                        购车辆来源合法、手续齐全，购车人符合所在地
                        上牌政策资格，就能顺利办理上牌。按照我国汽
                        车三包条例和各汽车厂家公布的质保政策，只要
                        通过正规渠道购买手续齐全的中规车就能享受相
                        应的质保和维修服务。
                    </p>
                    <a href="#" class="more">了解更多 &gt;&gt;</a>
                </div>
            </div>
        </div>

        <footer class="footer"> 
            <div class="footer-menu container">
                <ul>
                    <li>
                        <h3>用户指南</h3>
                        <a href="#">买车流程</a>
                        <a href="#">诚信保障</a>
                        <a href="#">注意事项</a>
                    </li>
                    <li>
                        <h3>服务中心</h3>
                        <a href="#">服务协议</a>
                        <a href="#">平台规则</a>
                        <a href="#">常见问题</a>
                    </li>
                    <li>
                        <h3>关于我们</h3>
                        <a href="#">平台简介</a>
                        <a href="#">联系方式</a>
                        <a href="#">发现职位</a>
                    </li>
                    <li>
                        <h3>商务合作</h3>
                        <a href="#">加盟方入口</a>
                        <a href="#">媒体合作</a>
                        <a href="#">友情链接</a>
                    </li>
                    <li>
                            <div  class="qrcode"><p>加微信关注我们</p></div>
                    </li>
                  
                </ul>
            </div>
            <div class="sp"></div>
            <div class="container pos-rlt foot-info foot-info-new">
                <p>@CopyRight 2014- 2015, 苏州华车网络科技有限公司   版权所有</p>
                <p> 工业信息化部信息备案：苏ICP备14017673号-1 </p>
                <img src="themes/images/common/beian.gif" class="beian" alt="">
            </div>
        </footer>

        <div class="zm">
            <div id="login" class="">
                <div class="l-wapper">
                    <div class="l-head">
                        <div class="l-h-bg">用户登录<a href="javascript:;" ms-click="closeLogin" class="login-close"></a></div>
                    </div>
                    <div class="l-c">
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon">账号:</span>
                            <input data-toggle="tooltip" data-placement="bottom" name="loginname" type="text" required class="form-control" placeholder="请输入手机号码/邮箱" title="请输入手机号码/邮箱" aria-describedby="basic-addon1">
                            <span class="input-group-addon hide error">请正确输入</span>
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon">密码:</span>
                            <input data-toggle="tooltip" type="password" data-placement="top" name="loginpwd" type="text" required class="form-control" placeholder="请输入密码" title="请输入密码" aria-describedby="basic-addon1">
                            <span class="input-group-addon hide error">请正确输入</span>
                        </div>
                        <br>
                        <div class="input-group code">
                            <span class="input-group-addon">请输入验证码:</span>
                            <input name="logincode" type="text" required class="form-control" placeholder="请输入验证码" title="请输入验证码" aria-describedby="basic-addon1">
                            <span class="input-group-addon hide error">请正确输入</span>
                            <span class="input-group-addon valite-code"><img  onclick="this.src='http://user.hwache.cn/index.php?act=seccode&amp;op=makecode&amp;nchash=521e168c&amp;r='+Math.random()"  src="http://user.hwache.cn/index.php?act=seccode&op=makecode&nchash=521e168c" /></span>
                        </div>
                        <div class="pos-rlt">
                            <button ms-on-click="SmipSubLoign" type="submit" class="btn btn-s-md btn-danger">立即登陆</button>
                            <div class="smip-login-loading"></div>
                        </div>
                        <p class="reg-help">
                            <a href="forget.html">忘记密码?</a>
                            <a href="javascript:;" class="simp-reg">快捷注册</a>
                        </p>
                        <!-- <div class="reg-method">
                            <span>其他账号登陆</span>
                            <a href="javascript:;" class="l-sina">新浪登陆</a>
                            <a href="javascript:;" class="l-qq">QQ登陆</a>
                        </div> -->

                    </div>
                </div>
            </div>
        </div>

    </div>
 
    <script src="./js/sea.js" ></script>
    <script src="./js/config.js"></script>
    <script type="text/javascript">
        seajs.use(["vendor/vue","module/index/index.adv", "module/common/common","bt"],function(a,b,c,d){
            b.init("江苏省","苏州市","0")
            b.countDown({{$date}})
        });
    </script>

</body>
</html>
