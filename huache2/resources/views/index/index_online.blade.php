<!DOCTYPE html>
<html>
<head>
    <title>华车</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta name="description" content="华车" />
    <meta name="keywords" content="华车" />
    <meta name="author" content="llm" />
    <link href="themes/bootstrap.css" rel="stylesheet" />
    <link href="/webhtml/common/css/common.css" rel="stylesheet" />
    <link href="themes/theme.online.css" rel="stylesheet" />
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    @include('HomeV2._layout.header2')
    <div class="container m-t-86 pos-rlt" id="vue">
        <form name="indexSearchForm" action="{{ route('searchQuery') }}" data-txt="请选择">
            <div class="i-search pos-abt text-center-xs">
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
                            <button v-cloak  type="button" @click="searchForm"  title="点击搜索"></button>
                        </div>
                    </li>
                    <div class="clear"></div>
                </ul>
            </div>
        </form>

        <div class="tipWin">
            @if (Auth::check())
            <div class="win">
                <h4></h4>
                <p>客官，您将在平台开放购买时获得 <span class="weight juhuang price">500</span> 元不限品类代金券，敬请持续关注华车～～    </p>
                <p class="tac notice-wrapper">
                    <a @click="exit" href="javascript:;" class="sure">确认</a>
                </p>
            </div>
            @else
            <div class="win">
                <h2>好消息，华车已向您开放在线注册啦！</h2>
                <p>现在注册的客官，将在平台开放购买时获得 <span class="weight juhuang price">500</span> 元不限品类代金券，买车更实惠！注册要赶早，不容错过哦～    </p>
                <h3></h3>
                <p class="tac notice-wrapper">
                    <a @click="exit" href="javascript:;" class="exit">暂不注册</a>
                    <a @click="toreg" href="javascript:;" class="toreg">去注册</a>
                </p>
            </div>
            @endif
            
        </div>
        <div class="txt-wrapper">
           <h3 class="tac">华车宣言</h3>
           <p>2017年7月1日，《汽车销售管理办法》正式实施，中国汽车行业迈入新时代！</p>
           <p>华车（<span class="juhuang">www.hWache.com</span>）作为汽车电商新物种应运而生，为用户提供全国范围一手新车购买相关的车源搜索、综合比价、在线预定、线下提车的高透明一站式+高保障一条龙服务。</p>
           <p>以靠谱电商为己任，以加信社会为愿景！我们致力于互联网技术优化中国汽车市场，以求化解诸如信息朦胧、诚信缺失、区域壁垒等现存问题，全面提升消费者购车体验，促进潜在购买力的释放，为提振内需、繁荣经济作出贡献。</p>
           <p>华车愿成为一块试金石，让消费者可以发现把诚信经营落到实处的优质商家。华车愿成为一个舞台，让优秀汽车品牌及优质经销商尽展风采，与真实消费者建立联系，收获更大的市场份额。</p>
           <p>路漫漫其修远兮，华车将在中国汽车电商之路上不断求索，知行合一，自主未来......</p>

        </div>
        <div class="clear"></div>
    </div>

    <div class="footer">
        <p class="tel">0512-68782885</p>
        <p class="qr">
            <span class="wx"><span class="qrcode"></span></span>

        </p>
        <p>&copy;CopyRight 2015-2017,苏州华车网络科技有限公司 版权所有</p>
        <p>工信部备案号：<a href="http://www.miitbeian.gov.cn" target="_blank" class="blue">苏ICP备16001420号</a></p>
    </div>
    
    @include('HomeV2._layout.login')
    <script src="{{asset('js/sea.js')}}" ></script>
    <script src="{{asset('js/config.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["vendor/vue","module/index/index.adv.online", "module/common/common", "bt"],function(a,b,c,d){
           b.init("江苏省","苏州市",166)
        });
    </script>

</body>
</html>
