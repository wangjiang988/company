<!DOCTYPE html>
<html>
<head>
    <title>华车</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta name="description" content="华车网" />
    <meta name="keywords" content="华车网" />
    <meta name="author" content="llm" />
    <link href="themes/bootstrap.css" rel="stylesheet" />
    <link href="/webhtml/common/css/common.css" rel="stylesheet" />
    <link href="themes/theme.css" rel="stylesheet" />
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    

    <form name="indexSearchForm" action="{{ route('searchQuery') }}" data-txt="请选择">
        @include('HomeV2._layout.header2')
        <div class="container m-t-86 pos-rlt" id="vue">
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
                                <input data-mark="brand" @click.stop="initSearchOptionDisplay" @click="showBrand" name="" type="text" onfocus="this.blur()" v-model="search.brand" />
                                <input name="" type="hidden"  v-model="search.brandId"/>
                                <i data-mark="brand" @click.stop="initSearchOptionDisplay" @click="showBrand"></i>
                                <dl id="brandObj" v-cloak v-show="search.isShowBrand" class="auto">
                                    <dt :id="id" v-for="(brand,id) in search.brandList" @click="selectBrand(brand.gc_id,brand.gc_name,brand.gc_id)">
                                        <em><img src="/webhtml/common/images/loading-dian.gif" class="brand-img" onerror="this.src='themes/images/common/error-def.png'" :data-url="'{{env('UPLOAD_URL')}}/'+brand.detail_img" /></em>
                                        <a href="javascript:;" v-html="">@{{brand.gc_name}}</a>
                                    </dt>
                                </dl>
                            </div>
                        </li>

                        <li class="cars">
                            <div class="wapper"  v-cloak >
                                <label>车系:</label>
                                <span class="errors">@{{search.carSeriesError}}</span>
                                <input data-mark="car" @click.stop="initSearchOptionDisplay" @click="showCarSeries" name="" type="text" onfocus="this.blur() " v-model="search.carSeries" />
                                <input name="" type="hidden"  v-model="search.carSeriesId" />
                                <i data-mark="car" @click.stop="initSearchOptionDisplay" @click="showCarSeries"></i>
                                <dl id="carSeriesObj" v-cloak v-show="search.isShowCarSeries" class="auto">
                                    <dd v-for="(cars,id) in search.carSeriesList" @click="selectCar(cars.gc_id,cars.gc_name)"><a href="javascript:;" class="overhide" v-html="cars.gc_name"></a></dd>
                                </dl>
                            </div>
                        </li>

                        <li class="models">
                            <div class="wapper" v-cloak >
                                <label>车型:</label>
                                <span class="errors">@{{search.vehicleModeError}}</span>
                                <input data-mark="model" @click.stop="initSearchOptionDisplay" @click="showVehicleModel" name="" type="text" onfocus="this.blur()"  v-model="search.vehicleMode" />
                                <input name="car" type="hidden"  v-model="search.searchVehicleModelId" />
                                <i data-mark="model" @click.stop="initSearchOptionDisplay" @click="showVehicleModel"></i>
                                <dl id="modelObj" v-show="search.isShowVehicleModel" class="auto">
                                    <dd v-for="(cars,id) in search.vehicleModeList" @click="selectModel(cars.gc_id,cars.gc_name,id)"><a href="javascript:;">@{{cars.gc_name}}</a></dd>
                                </dl>

                            </div>
                        </li>

                        <li class="i-s-b">
                            <div class="wapper">
                                <button v-cloak  type="button" @click="searchForm" @mousedown="showHideCountDownBg" @mouseup="showHideCountDownBg" title="点击搜索"></button>
                            </div>
                        </li>
                        <div class="clear"></div>
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
                        在我们这里，采用透明比价模式，报价以一口价呈现在您面前，无须费力讨价还价即可找到满意价格。我们在行业中开创了新车车价透明化比价模式的先河，结合“汽车信用宝”双向担保体系，建立一站式新车预订平台，节约了大量的中间交易成本。
                    </p>
                    <a href="#" class="more">了解更多 &gt;&gt;</a>
                </div>
            </div>

            <div class="floor">
                <div class="loading"><img src="themes/images/index/info-2.gif" /></div>
                <div class="info info-2">
                    <h2>一站式新车预定平台</h2>
                    <p>
                        我们平台发布销售信息的车辆，均是来自正规渠道、手续合法齐全的新车，车源有保障。而且将给您的权益更多惊喜保障，助您牢牢把握购车主动权。我们更为您配备专属客服，以细心细致的专业服务，为您购车全程保驾护航。
                    </p>
                    <a href="#" class="more">了解更多 &gt;&gt;</a>
                </div>
            </div>

            <div class="floor">
                <div class="loading"><img src="themes/images/index/info-3.gif" /></div>
                <div class="info info-3">
                    <h2>异地购车</h2>
                    <p>
                        异地购车，是指消费者在本地以外区域购买汽车产品，回到本地上牌和使用的一种现象。只要所购车辆来源合法、手续齐全，购车人符合所在地上牌政策资格，就能顺利办理上牌。按照我国汽车三包条例和各汽车厂家公布的质保政策，只要通过正规渠道购买手续齐全的中规车就能享受相应的质保和维修服务。
                    </p>
                    <a href="#" class="more">了解更多 &gt;&gt;</a>
                </div>
            </div>
        </div>
        @include('HomeV2._layout.footer')
    </form>
    
    @include('HomeV2._layout.login')
    <script src="{{asset('js/sea.js')}}" ></script>
    <script src="{{asset('js/config.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["module/index/index.adv"],function(a,b,c,d){
           a.countDown({{$date}})
           a.init("{{ $ipAddress->region }}","{{ $ipAddress->city }}","{{ $ipAddress->city_id }}")
        });
    </script>

</body>
</html>
