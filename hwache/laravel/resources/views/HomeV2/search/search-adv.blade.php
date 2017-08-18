@extends('HomeV2._layout.base')
@section('nav') @include('_layout.nav') @endsection
@section('content')

    <form class="SearchPageForm" name="SearchPageForm">

        <!--省份城市 start-->
        <div v-cloak v-show="search.isShowProvince" id="SelectTimeWin" class="popupbox p-city-wrapper">
            <div class="popup-title popup-title-white psr">
                <span class="fl fs16"><b>选择城市</b></span>
                <dl class="letter-nav fl">
                    <dd>
                        <a @click="position" v-for="(letter,tag) in search.areaList" :href="'#NAV-'+tag"><span class="">@{{tag}}</span></a>
                    </dd>
                </dl>
                <i @click="showProvince"></i>
                <div class="clear inline"></div>
            </div>
            <div class="popup-wrapper notopbottompadding">
                <div class="popup-content nopadding">
                    <div class="clear"></div>
                    <div id="province-city-select">

                        <dl v-for="(letter,tag) in search.areaList" class="province-city-select-wrapper" >
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
                                            <li @click.stop-3="showProvince" @click.stop-2="setProvince(province.area_name)" @click.stop-1="SelectCardArea(search.cityValIndex,city.area_id,city.area_name,city.area_xianpai)" v-for="city in province.child" class="city" :data-id="city.area_id" :data-value="city.area_name">
                                                <span class="fl ml5">@{{city.area_name}}</span>
                                                <span class="clear"></span>
                                            </li>
                                        </ul>
                                    </dd>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </dl>
                        <p :class="{hasnone:false,red:false}">不含港澳台</p>
                        <br v-for="i in 17">
                    </div>
                     
                </div>
                <div class="popup-control">
                    <div class="clear mt10"></div>
                </div>
            </div>
        </div>

        <!--省份城市 end-->

        <div class="search-panel-box">
            <div class="container m-t-86 pos-rlt" >

                <div class="search-def-option">
                    <ul>
                        <li v-cloak>
                            <label class="ml-31">新车上牌地区</label>
                            <dl v-cloak>
                                <dt class="s-area area-dt">
                                    <i @click="dropdown(0)" :class="{trans:search.mainOption.area.display}"></i>
                                    <p v-cloak class="area-txt-p"><span class="span" v-show="isShowProvinceTxt">@{{search.province}}</span>@{{search.mainOption.area.text}}<span v-show="search.mainOption.area.isLimit">(限牌)</span></p>
                                </dt>
                                <dd v-cloak v-show="search.mainOption.area.display" class="first-auto inline-block">
                                    <ol v-cloak class="area-city-layout"> 
                                        <li v-for="city in search.cityList" @click.stop-2="setCityValIndex(0)" @click.stop-1="SelectCardArea(0,city.area_id,city.area_name,city.area_xianpai)">@{{city.area_name}}</li>
                                        <div class="clear"></div>
                                    </ol>
                                    <div v-cloak class="box clear block text-right">
                                        <a href="javascript:;"  @click.stop-2="setCityValIndex(0)" @click.stop-1="showProvince">更多城市&gt;&gt;</a>
                                    </div>
                                    <input type="hidden"  name="area"  value="search.mainOption.area.id"/>
                                </dd>
                            </dl>
                        </li>

                        <li v-cloak>
                            <label class="ml-31">品牌</label>
                            <dl>
                                <dt class="s-area">
                                    <i @click="dropdown(1)" :class="{trans:search.mainOption.brand.display}"></i>
                                    <p class="brand-txt-p">
                                        <img :src="search.mainOption.brand.image" alt="">
                                        <span>@{{search.mainOption.brand.text}}</span>
                                    </p>
                                </dt>
                                <dd v-cloak v-show="search.mainOption.brand.display" class="auto inline-block">
                                    <ol class="brand-display-ol">
                                        <li v-for="(brand,id) in search.brandList" @click="selectBrand(brand.gc_id,brand.gc_name,url.brandImageUrl+brand.detail_img,id)"><img height="20" :src="url.brandImageUrl+brand.detail_img" alt=""><span>@{{brand.gc_name}}</span></li>
                                    </ol>
                                    <input v-model="search.mainOption.brand.id" :value="search.mainOption.brand.id" type="hidden" name="carbrand"  />
                                </dd>
                            </dl>
                        </li>

                        <li v-cloak>
                            <label class="ml-31">车系</label>
                            <dl>
                                <dt class="s-area">
                                    <i @click="dropdown(2)" :class="{trans:search.mainOption.carSeries.display}"></i>
                                    <p v-cloak v-show="!search.mainOption.carSeries.isClear" class="area-txt-p">@{{search.mainOption.carSeries.text}}</p>
                                    <p v-cloak v-show="search.mainOption.carSeries.isClear" class="area-txt-p">--请选择--</p>
                                </dt>

                                <dd v-cloak v-show="search.mainOption.carSeries.display" class="auto inline-block">
                                    <ol class="car-series-ol">
                                        <li v-for="(cars,id) in search.carSeriesList" @click="selectCar(cars.gc_id,cars.gc_name)"><span class="overhide">@{{cars.gc_name}}</span></li>
                                    </ol>
                                    <input v-model="search.mainOption.carSeries.id" :value="search.mainOption.carSeries.id" type="hidden" name="chexi"  />
                                </dd>
                            </dl>
                        </li>

                        <li v-cloak>
                            <label class="ml-31">车型规格</label>
                            <dl>
                                <dt class="s-chexing">
                                    <i @click="dropdown(3)" :class="{trans:search.mainOption.vehicleModel.display}"></i>
                                    <p v-cloak v-show="!search.mainOption.vehicleModel.isClear" class="vehicle-model-txt-p">@{{search.mainOption.vehicleModel.text}}</p>
                                    <p v-cloak v-show="search.mainOption.vehicleModel.isClear" class="vehicle-model-txt-p">--请选择--</p>
                                </dt>
                                <dd v-cloak v-show="search.mainOption.vehicleModel.display" class="auto inline-block">
                                    <ol class="car-vehicle-model-ol">
                                        <li v-for="(cars,id) in search.vehicleModeList" @click="selectModel(cars.gc_id,cars.gc_name,id)"><span class="overhide">@{{cars.gc_name}}</span></li>
                                    </ol>
                                    <input name="car" type="hidden"  v-model="search.mainOption.vehicleModel.id" />
                                </dd>
                            </dl>
                        </li>
                        <li class="clear"></li>
                        <li>
                            <label>厂商指导价</label>
                            <dl>
                                <dt class="s-chexing">
                                    <p v-cloak v-show="search.mainOption.isLoadOk">@{{ formatMoney(search.mainOption.guidePrice,2,"￥")}}</p>
                                </dt>
                                <dd>
                                    <input type="hidden" name="price" value="" />
                                </dd>
                            </dl>
                        </li>

                        <li>
                            <label>座位数</label>
                            <dl>
                                <dt class="s-chexing">
                                    <p v-show="search.mainOption.isLoadOk" v-cloak>@{{search.mainOption.seating}}</p>
                                </dt>
                            </dl>
                        </li>
                        <li>
                            <label>生产国别</label>
                            <dl>
                                <dt class="s-chexing">
                                    <p v-show="search.mainOption.isLoadOk" v-cloak>@{{search.mainOption.country}}</p>
                                </dt>
                            </dl>
                        </li>
                        <li>
                            <label>基本配置</label>
                            <dl>
                                <dt class="s-chexing">
                                    <p v-cloak>权威参数来自官网 <a v-show="search.mainOption.guidePrice" :href="search.mainOption.configuration.href"><span>查看</span></a></p>
                                </dt>
                            </dl>
                        </li>
                        <li class="clear"></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container  pos-rlt search-select-option" >
            
            <div v-cloak class="box slide-wrapper">
                <dl>
                   <dt>
                       <label class="ml27">已选</label>
                   </dt>
                   <dd>
                       <ul>
                           
                            <li class="wauto mr10 mt5 fl"><em class="limit select">国五</em></li>
                            <div class="w900 selected-conditions" v-show="!isSlideOption" v-cloak>
                               <li class="wauto def mr10 mt5" v-show="search.option.area.id"><em class="limit select">@{{search.option.area.text}}</em></li>
                               <li class="wauto def mr10 mt5" v-show="search.option.bodyColorTxt"><em class="limit select">@{{search.option.bodyColorTxt}}</em></li>
                               <li class="wauto def mr10 mt5" v-show="search.option.interiorColorTxt"><em class="limit select">@{{search.option.interiorColorTxt}}</em></li>
                               <li class="wauto def mr10 mt5" v-show="search.option.mileage!=-1 && search.option.mileage!=0"><em class="limit select">@{{search.option.mileageTxt}}</em></li>
                               <li class="wauto def mr10 mt5" v-show="search.option.factoryMonthly!=-1 && search.option.factoryMonthly!=0"><em class="limit select">@{{search.option.factoryMonthlyTxt}}</em></li>
                               <li class="wauto def mr10 mt5" v-show="search.option.carCycle!=0"><em class="limit select">@{{search.option.carCycle}}个月</em></li>
                               <div class="clear"></div>
                           </div> 
                           <div class="clear"></div>
                            
                       </ul>
                   </dd>
                   <a v-cloak @click="slideOptionWithJQ" class="toggle-slide hover">@{{SlideOptionTxt}}<i :class="{'slide-up':isSlideOption,'slide-down':!isSlideOption}"></i></a>
                   <div class="clear"></div>
                </dl>
            </div>
            <div v-cloak class="box slide-wrapper" id="slide-wrapper">

                <dl>
                   <dt>
                       <label>车源范围：</label>
                       <input name="juli" type="hidden" v-model="search.option.area.id" :value="search.option.area.id" class="">
                   </dt>
                   <dd>
                       <ul>
                           <li v-for="(d,i) in search.option.distanceList" :class="{wauto:i==0,ml70:d.isCenter}"><em @click.stop-3="clearCenterCity" @click.stop-2="setClass(d,0)" @click.stop="setOptionVal(0,d.id)" :class="{limit:i==0,select:d.isSelect}">@{{d.txt}}</em></li>
                           <li class="center-city psr">
                                <input @focus.stop-1="setCityValIndex(9)" @focus.stop-2="showSelfCityList" type="text" placeholder="特定地区" readonly="" v-model="search.specifiedArea" :value="search.specifiedArea" :class="{juhuangborder:search.specifiedArea!=''}" />
                                <dl v-cloak class="center-city-dl" v-show="search.option.area.display">
                                    <dd v-cloak  class="auto inline-block">
                                        <ol v-cloak class="area-city-layout">
                                            <li v-for="city in search.specificCityList" @click.stop-3="showSelfCityList" @click.stop-2="setCityValIndex(9)" @click.stop-1="SelectCardArea(9,city.area_id,city.area_name,city.area_xianpai)">@{{city.area_name}}</li>
                                            <div class="clear"></div>
                                        </ol>
                                        <div v-cloak class="box clear block text-right">
                                            <a href="javascript:;" @click.stop-3="showSelfCityList"  @click.stop-2="setCityValIndex(9)" @click.stop-1="showProvince">更多城市&gt;&gt;</a>
                                        </div>
                                        <input type="hidden"  name="area"  :value="search.mainOption.area.id"/>
                                    </dd>
                                </dl>
                           </li>
                       </ul>
                   </dd>
                   <div class="clear"></div>
                </dl>
                <dl>
                    <dt>
                        <label>车身颜色：</label>
                        <input name="body_color"  type="hidden"  :value="search.option.bodyColor" class="">
                    </dt>
                    <dd class="wp93">
                        <ul>
                            <li v-if="i==0" :class="{fl:true,'text-left':i>0,'wauto':i==0,'all':i==0}" v-for="(color,i) in search.option.bodyColorList">
                                <label v-if="i>0" @click.stop="setColorClass(color,0)"  class="checkbox-inline i-checks-me">
                                    <i :class="{'checks-me':color.isSelect}"></i>
                                </label>
                                <em v-if="i==0" @click.stop="selectEm(color,0)" :class="{'limit':i==0,'color-em':i>0,'selected':color.isSelect}">@{{color.color}}</em>
                                <em v-if="i>0" :class="{'limit':i==0,'color-em':i>0,'juhuang':color.isSelect}">@{{color.color}}</em>
                            </li>
                            <div class="fl color-list">
                                <li v-if="i > 0" :class="{'text-left':i>0,'wauto':i==0,'all':i==0}" v-for="(color,i) in search.option.bodyColorList">
                                    <label v-if="i>0" @click.stop="setColorClass(color,0)"  class="checkbox-inline i-checks-me">
                                        <i :class="{'checks-me':color.isSelect}"></i>
                                    </label>
                                    <em v-if="i==0" @click.stop="selectEm(color,0)" :class="{'limit':i==0,'color-em':i>0,'selected':color.isSelect}" :title="color.color">@{{color.color}}</em>
                                    <em v-if="i>0" :class="{'limit':i==0,'color-em':i>0,'juhuang':color.isSelect}" :title="color.color">@{{color.color}}</em>
                                </li>
                            </div>
                            <div class="clear"></div>
                        </ul>
                    </dd>
                    <div class="clear"></div>
                </dl>
                <dl>
                    <dt>
                        <label>内饰颜色：</label>
                        <input name="interior_color" type="hidden"  value="" class="">
                    </dt>
                    <dd class="wp93">
                        <ul>
                            <li v-if="j == 0" :class="{fl:true,'text-left':j>0,'wauto':j==0,'all':j==0}" v-for="(c,j) in search.option.interiorColorList">
                                <label v-if="j>0" @click.stop="setColorClass(c,1)"  class="checkbox-inline i-checks-me">
                                    <i :class="{'checks-me':c.isSelect}"></i>
                                </label>
                                <em v-if="j==0" @click.stop="selectEm(c,1)" :class="{'limit':j==0,'color-em':j>0,'selected':c.isSelect}" :title="c.color">@{{c.color}}</em>
                                <em v-if="j>0" :class="{'limit':j==0,'color-em':j>0,'juhuang':c.isSelect}" :title="c.color">@{{c.color}}</em>
                            </li>
                            <div class="fl color-list">
                                <li v-if="j > 0" :class="{'text-left':j>0,'wauto':j==0,'all':j==0}" v-for="(c,j) in search.option.interiorColorList">
                                    <label v-if="j>0" @click.stop="setColorClass(c,1)"  class="checkbox-inline i-checks-me">
                                        <i :class="{'checks-me':c.isSelect}"></i>
                                    </label>
                                    <em v-if="j==0" @click.stop="selectEm(c,1)" :class="{'limit':j==0,'color-em':j>0,'selected':c.isSelect}" :title="c.color">@{{c.color}}</em>
                                    <em v-if="j>0" :class="{'limit':j==0,'color-em':j>0,'juhuang':c.isSelect}" :title="c.color">@{{c.color}}</em>
                                </li>
                            </div>
                            <div class="clear"></div>
                        </ul>
                    </dd>
                    <div class="clear"></div>
                </dl>
                <dl>
                    <dt>
                        <label>行驶里程：<br><span class="block juhuang text-center fs12">现车</span></label>
                        <input name="licheng" type="hidden"  v-model="search.option.mileage" :value="search.option.mileage"  />
                    </dt>
                    <dd>
                        <ul>
                            <li v-for="(d,i) in search.option.mileageList" :class="{wauto:i==0,def:i>0,ml38:i==1,'disabled-gray': search.option.isSelectCarCycle}"><em  @click.stop="selectMileageStatus(d)" :class="{limit:i==0,select:d.isSelect && !search.option.isSelectCarCycle}">@{{d.txt}}</em></li>
                        </ul>
                    </dd>
                    <div class="clear"></div>
                </dl>
                <dl>
                    <dt>
                        <label>出厂年月：<br><span class="block juhuang text-center fs12">现车</span></label>
                        <input name="chuchang" type="hidden" v-model="search.option.factoryMonthly" :value="search.option.factoryMonthly" class="">
                    </dt>
                    <dd>
                        <ul>
                            <li v-for="(d,i) in search.option.factoryMonthlyList" :class="{wauto:i==0,def:i>0,ml38:i==1,'disabled-gray':search.option.isSelectCarCycle}"><em  @click.stop="selectFactoryMonthlyStatus(d)" :class="{limit:i==0,select:d.isSelect && !search.option.isSelectCarCycle}">@{{d.txt}}</em></li>
                        </ul>
                    </dd>
                    <div class="clear"></div>
                </dl>
                <dl>
                    <dt>
                        <label>交车周期：<br><span class="block juhuang text-center fs12">非现车</span></label>
                        <input name="jiaochezhouqi" type="hidden" v-model="search.option.carCycle" :value="search.option.carCycle" />
                    </dt>
                    <dd>
                        <ul>
                            <li v-for="(d,i) in search.option.carCycleList" :class="{wauto:i==0,def:i>0,ml38:i==1,'disabled-gray':search.option.isSelectMileage || search.option.isSelectFactoryMonthly }"><em @click.stop="selectCarCycleStatus(d)" :class="{limit:i==0,select:d.isSelect && !search.option.isSelectMileage && !search.option.isSelectFactoryMonthly}">@{{d.txt}}</em></li>
                        </ul>
                    </dd>
                    <div class="clear"></div>
                </dl>
                <dl>
                    <dt>
                        <label>排放标准：</label>
                        <input name="biaozhun" type="hidden" v-model="search.option.emissionStandards" :value="search.option.emissionStandards" class="">
                    </dt>
                    <dd>
                        <ul>
                            <li v-cloak v-cloak v-for="(d,i) in search.option.emissionStandardsList" :class="{wauto:i==0}"><em @click.stop-2="setClass(d,3)" @click.stop="setOptionVal(3,d.id)" :class="{limit:i==0,select:d.isSelect}">@{{d.txt}}</em></li>
                        </ul>
                    </dd>
                    <div class="clear"></div>
                </dl>
            </div>
            

            <dl>
                <dt>
                    <label>付款方式：</label>
                </dt>
                <dd v-cloak>
                    <ul>
                        <li v-cloak v-for="(d,i) in search.option.paymentList" :class="{wauto:i==0}"><em @click.stop-2="setClass(d,4)" @click.stop="setOptionVal(4,d.id)" :class="{limit:i==0,boldbg:d.isSelect}">@{{d.txt}}</em></li>
                    </ul>
                    
                </dd>
                <div class="clear"></div>
            </dl>
            <dl class="nobd">
                <dt>
                    <label>上牌用途：</label>
                    <input name="yongtu" type="hidden" v-model="search.option.registrationApplication" :value="search.option.registrationApplication" class="">
                </dt>
                <dd v-cloak>
                    <ul>
                        <li @mouseover="showTip(d)" @mouseout="showTip(d)" v-cloak v-for="(d,i) in search.option.registrationApplicationList" :class="{wauto:i==0,psr:true}">
                            <em @click.stop-2="setClass(d,5)" @click.stop="setOptionVal(5,d.id)" :class="{limit:i==0,boldbg:d.isSelect}">@{{d.txt}}</em>
                            <div v-cloak :class="{'in':d.isHover,'tooltip':true, 'fade':true ,'top':true , 'psa':true ,'psa-reg-app-item':true }" >
                                <div class="tooltip-arrow"></div>
                                <div class="tooltip-inner">
                                    <p class="m0i">@{{d.showTxt}}</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </dd>
                <div class="clear"></div>
            </dl>
        </div>
        <div class="container pos-rlt car-list">
            <div class="order-opt">
                <ul class="order-list-opt">
                    <li @click="orderList('def',$event)" class="order-selected"><span>默认排序</span></li>
                    <li @click="orderList('up',$event)"><span>华车车价</span><i class="order order-up"></i></li>
                    <li @click="orderList('down',$event)"><span>华车车价</span><i class="order order-down"></i></li>
                    <div class="clear"></div>
                </ul>
                <div class="search-btn-area" v-show="search.option.isCanSearch">
                    <a @click="searchValite" href="javascript:;" class="btns btn-search">查找</a>
                    <a @click="resetSearchOption" href="javascript:;" class="btns btn-def">默认条件</a>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>

            <!--search result list-->
             
            <table id="tbl-list" class="search-result" cellpadding="0" cellspacing="0" width="100%">
                <thead>
                    <!--标题 start-->
                    <tr>
                        <td rowspan="2" class="noborder whitebg" width="56">
                            <label v-cloak v-show="!isCanContrast && !isCanceContrast" class="label bg-primary m-l-xs">对比</label>
                            <label v-cloak v-show="isCanContrast" @click="sureContrast" class="label bg-danger m-l-xs">确定</label>
                            <label v-cloak v-show="isCanceContrast" @click="canceContrast" class="label bg-primary m-l-xs">解除</label>
                        </td>
                        <td rowspan="2" class="th" width="102">
                            <div class="psr tbl-tip">
                                <div class="box" >
                                    <p class="m0">可售</p>
                                    <p class="m0">车源</p>
                                    <p class="m0">位置
                                        <span class="psr" @mouseover.prevent.stop="tip(0)" @mouseout.prevent.stop="tip(0)"><i>i</i></span>
                                    </p>
                                </div>
                                <div v-cloak :class="{'in':tips[0].isShow,'tooltip':true, 'fade':true ,'right':true , 'psa':true ,'psi-1':true}" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">交车将在该范围内该品牌厂商授权经销商的合法、正规营业场所内进行，具体地点在后续预约交车步骤前另行告知。</p>
                                        <p class="m0 red">请注意：本范围中所示地区为直辖市、地级市、自治州的行政区划级别，包含了下辖的所有区、县级市或自治县！</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="th" rowspan="2" width="112">
                            <div class="psr tbl-tip">
                                <p class="m0">华车车价<span class="psr" @mouseover.prevent.stop="tip(1)" @mouseout.prevent.stop="tip(1)"><i>i</i></span></p>
                                <p class="m0"><small>(含服务费)</small></p>
                                <div v-cloak :class="{'in':tips[1].isShow,'tooltip':true, 'fade':true ,'right':true , 'psa':true ,'psi-2':true}" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">包含下面两部分金额：</p>
                                        <p class="m0">1.经销商车辆开票金额（在经销商处支付）；</p>
                                        <p class="m0">2.华车平台强力保障购车的服务费（订单顺利完成后从买车担保金中扣除）。</p>
                                        <p class="m0">两项的具体金额在后续预约交车步骤前另行告知。</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="th" width="136">
                            <span>车身颜色</span>
                        </td>
                        <td class="th" width="123">
                            <span>内饰颜色</span>
                        </td>
                        <td class="th" width="124">
                            <span>排放标准</span>
                        </td>
                        <td class="th" rowspan="2" width="111">
                            <div class="psr tbl-tip">
                                <p class="m0">商业保险限定<span class="psr" @mouseover.prevent.stop="tip(2)" @mouseout.prevent.stop="tip(2)"><i>i</i></span></p>
                                <div v-cloak :class="{'in':tips[2].isShow,'tooltip':true, 'fade':true ,'right':true , 'psa':true ,'psi-3':true}" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">指车辆首年商业保险是否必须在该经销商处购买，若是则显示为“指定投保”，如否显示为“自由投保”。</p>
                                    </div>
                                </div>
                                <p class="m0">和参考保费<span class="psr" @mouseover.prevent.stop="tip(3)" @mouseout.prevent.stop="tip(3)"><i>i</i></span></p>
                                <div v-cloak :class="{'in':tips[3].isShow,'tooltip':true, 'fade':true ,'right':true , 'psa':true ,'psi-4':true}" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">首年商业保险参考保费报价，包括了下列商业保险项目组合（这些项目的组合再加上交强险，可理解为某种常规概念上的全险）：</p>
                                        <p class="m0">机动车损失保险；</p>
                                        <p class="m0">机动车盗抢险；</p>
                                        <p class="m0">第三者责任保险（赔付额度50万元）；</p>
                                        <p class="m0">车上人员责任险（驾驶人1万元 限额+乘客座位每座各1万元限额）；</p>
                                        <p class="m0">玻璃单独破碎险（进口车为进口玻璃，国产车为国产玻璃）、车身划痕损失险（赔付额度2万元）、不计免赔特约险（包含机动车损失保险、机动车盗抢险、第三者责任保险、车上人员责任险、车身划痕损失险）。</p>
                                        <p class="m0">如果您确认投保项目内容与上述项目内容完全一致，我们承诺您提车时在经销商处支付的保费不高于上述金额。</p>
                                        <p class="m0">您也可以在收到交车通知后根据需要选择投保项目和内容，我们亦承诺定价标准不变。</p>
                                    </div>
                                </div>

                            </div>

                        </td>
                        <td class="th" rowspan="2" width="112">
                            <div class="psr tbl-tip">
                                <p class="m0">上牌服务限定<span class="psr" @mouseover.prevent.stop="tip(4)" @mouseout.prevent.stop="tip(4)"><i>i</i></span></p>
                                <div v-cloak :class="{'in':tips[4].isShow,'tooltip':true, 'fade':true ,'left':true , 'psa':true ,'psi-5':true}" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">上牌服务：指经销商为购车方代办机动车注册登记手续的服务，仅按车管所相关规则办理，不对牌号结果负责，也不含在某些限牌地区通过摇号、拍卖、转让等方式取得牌照资源指标的服务和费用。</p>
                                        <p class="m0">购车方上牌服务必须委托经销商办理，则显示为“指定上牌”，下方金额为此项服务的价格；如显示“自选上牌”，下方的报价为您可选择该经销商代办上牌的参考价格，当然，您也可选择不代办而亲自去办理；</p>
                                        <p class="m0">如显示“本人上牌”，指您需本人亲自办理上牌，经销商不提供异地上牌服务；</p>
                                        <p class="m0">如显示“接受安排”，因可能涉及异地上牌，我们将在预约交车前告知您上牌是由经销商代办还是由您亲自办理，您须完全接受我们所作的安排。</p>
                                        <p class="m0">如告知由经销商代办，则您将向其支付下方标准的服务费。</p>
                                    </div>
                                </div>
                                <p class="m0">和参考费用</p>
                            </div>
                        </td>
                        <td class="th" rowspan="2" width="112">
                            <div class="psr tbl-tip">
                                <p class="m0">上临时牌限定<span class="psr" @mouseover.prevent.stop="tip(5)" @mouseout.prevent.stop="tip(5)"><i>i</i></span></p>
                                <div v-cloak :class="{'in':tips[5].isShow,'tooltip':true, 'fade':true ,'left':true , 'psa':true ,'psi-6':true}" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">上临时牌仅指办理车辆临时移动牌照，而非机动车注册登记。</p>
                                        <p class="m0">限定条件显示“指定服务”的，要办理临时移动牌照必须委托经销商代办，包括下列两种情形：</p>
                                        <p class="m0">1.当您本人亲自上牌（办理注册登记手续），必须先委托经销商代办临时移动牌照，并向其支付下方标准的服务费；</p>
                                        <p class="m0">2.由经销商代办上牌的，如您也选择办理临时移动牌照以避免车辆无任何牌照阶段上路的风险，该临时移动牌照仍须委托经销商代办，并向其支付下方标准的服务费。</p>
                                        <p class="m0">限定条件显示“自选服务”的，指您在后续预约交车步骤中，可选择是否委托经销商代办临时移动牌照，如委托则须向其支付下方标准的服务费。</p>
                                    </div>
                                </div>
                                <p class="m0">和参考费用</p>
                            </div>

                        </td>
                        <td class="th" rowspan="2" width="112">
                            <div class="psr tbl-tip">
                                <p class="m0">买车担保金<span class="psr" @mouseover.prevent.stop="tip(6)" @mouseout.prevent.stop="tip(6)"><i>i</i></span></p>
                                <div v-cloak :class="{'in':tips[6].isShow,'tooltip':true, 'fade':true ,'left':true , 'psa':true ,'psi-7':true}" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">买车担保金将冻结在华车平台加信宝，订单执行完毕将扣除华车服务费和其他违约赔偿（如产生）后，多余金额将返还给您。</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        </td>
                        <td class="th" rowspan="2" width="79">
                            <div class="psr tbl-tip">
                                <p class="m0">诚意金<span class="psr" @mouseover.prevent.stop="tip(7)" @mouseout.prevent.stop="tip(7)"><i>i</i></span></p>
                                <div v-cloak :class="{'in':tips[7].isShow,'tooltip':true, 'fade':true ,'left':true , 'psa':true ,'psi-8':true}" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">诚意金支付至华车平台加信宝后，若非售方原因终止订单或平台规定的其他情形，诚意金是无法退还的，所以支付诚意金证明您是具有强烈购车意愿的客户。</p>
                                        <p class="m0">收到诚意金后，售方将在规定时间内对订单内容再次确认。</p>
                                        <p class="m0">若售方无违约确认行为，则已付诚意金的金额499元自动转为买车担保金的一部分，您继续支付买车担保金余款；若售方违约，不仅由华车平台自动退还诚意金，您还可获得歉意金499元等作为补偿（见平台保障政策）。</p>
                                    </div>
                                </div>
                                <p class="m0">￥499</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="th">
                            <span>行驶里程</span>
                        </td>
                        <td class="th">
                            <p class="m0">出厂年月</p>
                            <p class="m0">或交车周期</p>
                        </td>
                        <td class="th">
                            <p class="m0">可选原厂选装</p>
                            <p class="m0">参考折扣</p>
                        </td>
                    </tr>
                    <!--标题 end-->
                </thead>

                <tbody v-cloak v-show="car.isTrShow" :class="{'td-bg': car.isTrandBg == 1}" v-for="(car,i) in search.searchList">
                    <!--循环内容 start-->
                    <tr>
                        <td rowspan="3" class="noborder" width="56"> 
                            <label @click.stop-1="contrast(car)" :class="{'checkbox-inline':true,'i-checks-me':true,'disabled':isCanceContrast}">
                                <i :class="{'checks-me':car.isSelect}"></i>
                            </label>
                        </td>
                        <td rowspan="3" class="td" width="102">
                            <div class="ml5 text-left"> 
                                <span class="m0 text-left" v-for="(scope,index) in car.car_scope.toString().split(',')">
                                    <span v-if="index != 0">或 </span>@{{scope}}
                                </span> 
                            </div>
                        </td>
                        <td rowspan="3" class="td" width="112">
                            <p class="m0">￥@{{ formatMoney(car.hwache_price,2,"")}}</p>
                        </td>
                        <td class="td" width="136">
                            <p class="m0 overhide w135" :title="car.bj_body_color">@{{car.bj_body_color}}</p>
                        </td>
                        <td class="td" width="123">
                            <p class="m0 overhide w122" :title="car.interior_color">@{{car.interior_color}}</p>
                        </td>
                        <td class="td" width="124">
                            <p class="m0">@{{car.standard}}</p>
                        </td>
                        <td rowspan="2" class="td" width="111">
                            <div class="psr tbl-tip">
                                <p class="m0" @mouseover.prevent.stop="tblTip(car,0)" @mouseout.prevent.stop="tblTip(car,0)">@{{commercialInsuranceLimited(car.bj_baoxian)}}</p>
                                <div v-cloak :class="{'in':car.commercialInsurance,'tooltip':true, 'fade':true ,'right':true , 'psa':true ,'psi-9':true}" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner" v-if="car.bj_baoxian == 1">
                                        <p class="m0">您的车辆首年商业保险必须在该经销商处购买。但是，投保项目和内容您在后续步骤中可根据需要自己选择，华车承诺定价标准不变。</p>
                                    </div>
                                    <div class="tooltip-inner" v-if="car.bj_baoxian == 0">
                                        <p class="m0">您的车辆首年商业保险可以选择在当地其他渠道购买，也可以在后续步骤中根据需要选择经销商处投保，华车承诺定价标准不变。</p>
                                    </div>
                                </div>
                                <p class="m0">￥@{{ formatMoney(car.insurance.count,2,"")}}</p>
                            </div>
                        </td>
                        <td rowspan="2" class="td" width="112">
                            <div class="psr tbl-tip">
                                <p class="m0"  @mouseover.prevent.stop="tblTip(car,1)" @mouseout.prevent.stop="tblTip(car,1)">@{{registrationWay(car.bj_shangpai)}}</p>
                                <div v-if="car.bj_shangpai == 1" v-cloak :class="{'in':car.registrationService,'tooltip':true, 'fade':true ,'right':true , 'psa':true ,'psi-15':true}" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">您需本人亲自办理上牌，经销商不提供异地上牌服务，<span class="red">请务必在下单前做好您亲自上牌的各项准备！</span></p>
                                    </div>
                                </div>
                                <div v-if="car.bj_shangpai == 2" v-cloak :class="{'in':car.registrationService,'tooltip':true, 'fade':true ,'right':true , 'psa':true ,'psi-10':true}" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">您的上牌服务必须委托经销商办理。</p>
                                    </div> 
                                </div>
                                <div v-if="car.bj_shangpai == 3" v-cloak :class="{'in':car.registrationService,'tooltip':true, 'fade':true ,'right':true , 'psa':true ,'psi-13':true}" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">您可选择该经销商代办上牌，也可选择不代办而亲自去办理。</p>
                                    </div>
                                </div> 
                                <div v-if="car.bj_shangpai == 4" v-cloak :class="{'in':car.registrationService,'tooltip':true, 'fade':true ,'right':true , 'psa':true ,'psi-14':true}" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">我们将在后续预约交车步骤之前，告知您上牌是由经销商代办还是由您亲自办理，您须完全接受我们所作的安排。因可能涉及您本人亲自上牌，<span class="red">请务必在下单前做好您亲自上牌的各项准备！</span></p>
                                    </div>
                                </div> 
                                <p class="m0">￥@{{formatMoney(car.agent_numberplate_price,2,"")}}</p>
                            </div>
                        </td>
                        <td rowspan="2" class="td" width="112">
                            <div class="psr tbl-tip">
                                <p class="m0" @mouseover.prevent.stop="tblTip(car,2)" @mouseout.prevent.stop="tblTip(car,2)">@{{temporaryCardLimit(car.bj_linpai)}}</p>
                                <div v-if="car.bj_linpai == 1" v-cloak :class="{'in':car.temporaryCard,'tooltip':true, 'fade':true ,'right':true , 'psa':true ,'psi-12':true}" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">要办理临时移动牌照必须委托经销商代办，包括下列两种情形：</p>
                                        <p class="m0">1.当您本人亲自上牌（办理注册登记手续），必须先委托经销商代办临时移动牌照；</p>
                                        <p class="m0">2.由经销商代办上牌的，如您也选择办理临时移动牌照以避免车辆无任何牌照阶段上路的风险，该临时移动牌照仍须委托经销商代办。</p>
                                    </div> 
                                </div>
                                <div v-if="car.bj_linpai == 0" v-cloak :class="{'in':car.temporaryCard,'tooltip':true, 'fade':true ,'right':true , 'psa':true ,'psi-11':true}" >
                                    <div class="tooltip-arrow"></div> 
                                    <div class="tooltip-inner">
                                        <p class="m0">您在后续预约交车步骤中，可选择是否委托经销商代办临时移动牌照。</p>
                                    </div>
                                </div>


                                <p class="m0">￥@{{formatMoney(car.agent_temp_numberplate_price,2,"")}}</p>
                            </div>
                        </td>
                        <td rowspan="2" class="td" width="112">
                            <p class="m0">￥@{{formatMoney(car.client_sponsion_price,2,"")}}</p>
                        </td>
                        <td rowspan="3" class="td" width="79">
                            <p class="m0">
                                <a class="btn btn-s-md btn-danger qg" :href="'/show/'+car.url+'/'+search.option.registrationApplication">抢订</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="td"><p class="m0">@{{car.bj_licheng == null ? "/" : car.bj_licheng + '公里'}}</p> </td>
                        <td class="td"><p class="m0">@{{car.bj_producetime}}</p> </td>
                        <td class="td"><p class="m0">@{{car.bj_xzj_zhekou == null ? "/" : car.bj_xzj_zhekou + "%"}}</p> </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="td">
                            <div class="m10">
                                <table class="info">
                                    <tr>
                                        <td class="noborder text-right" valign="top" width="150">已装原厂选装精品：</td>
                                        <td v-if="car.select" class="noborder text-left">@{{car.select.xzj_titles}}，合计价值@{{formatMoney(car.select.xzj_totalPrice,2,"")}}元（按厂商指导价计）。</td>
                                        <td class="noborder text-left" v-if="!car.select">无</td>
                                    </tr>
                                    <tr>
                                        <td class="noborder text-right" valign="top" width="150">免费礼品或服务：</td>
                                        <td v-if="car.gifts" class="noborder text-left">@{{car.gifts}}。</td>
                                        <td class="noborder text-left" v-if="!car.gifts">无</td>
                                    </tr>
                                    <tr>
                                        <td class="noborder text-right" valign="top" width="150">其他收费项目和金额：</td>
                                        <td v-if="car.other" class="noborder text-left">
                                        <span v-for="(other,idx) in car.other">
                                            @{{other.other_name}}@{{formatMoney(other.sub_total,2,"")}}元
                                            <span v-if="idx < car.other.length - 1">、</span>
                                        </span>
                                        ，共计
                                        <span >
                                            @{{formatMoney(subProjectPrice(car.other),2,"")}}
                                        </span>
                                        元。
                                       
                                        </td>
                                        <td class="noborder text-left" v-if="!car.other">无</td>
                                    </tr>
                                    <tr v-if="car.bj_sn">
                                        <td class="noborder text-right" valign="top" width="150">测试编号：</td>
                                        <td class="noborder text-left">@{{car.bj_sn}}</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <!--循环内容 end-->
                </tbody>
                <!-- <tfoot v-show="isEmpty && isLoading">
                    <tr>
                        <td class="noborder"></td>
                        <td colspan="10">
                            <br>
                            <p class="text-center">数据加载中...</p>
                        </td>
                    </tr>
                </tfoot> -->
                <tfoot v-show="isEmpty && !isLoading && search.searchList.length == 0 && !search.isSelectOption">
                    <tr>
                        <td class="noborder"></td>
                        <td colspan="10">
                            <br>
                            <div class="empty">
                                <table class="margin-auto" v-cloak>
                                    <tr>
                                        <td valign="top" class="noborder">
                                            <img src="../themes/images/pay/pay-error.gif">
                                        </td>
                                        <td align="left" class="noborder">
                                            <p class="weight juhuang fs25">客官大人，非常抱歉。</p>
                                            <p>当前心仪车型暂无合适车源向您推荐。</p>
                                            <p>我们正在张罗，常来看看或许会有惊喜哦~</p>
                                            <p>您还有木有其他心仪车型？换个车型试试吧~</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tfoot>
                <tfoot v-show="isEmpty && !isLoading && search.searchList.length == 0 && search.isSelectOption">
                    <tr>
                        <td class="noborder"></td>
                        <td colspan="10">
                            <br>
                            <div class="empty">
                                <table class="margin-auto" v-cloak>
                                    <tr>
                                        <td valign="top" class="noborder">
                                            <img src="../themes/images/pay/pay-error.gif">
                                        </td>
                                        <td align="left" class="noborder">
                                            <p class="weight juhuang fs25">客官大人,不好意思。</p>
                                            <p>您的筛选条件可能太多了,还请高抬贵手~</p> 
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
 
            <div v-cloak v-show="!isCanceContrast" class="toolbar">
                <page-info :currents="pageinfo.current" :show-item="pageinfo.showItem" :allpage="pageinfo.allpage" :input="pageinfo.input"  v-on:increment="incrementCurrent"></page-info>
            </div>
            <div v-cloak v-show="isCanceContrast" class="toolbar">
                <br v-for="i in 10">
            </div>
        </div>

        <div v-cloak  class="loading" v-show="isLoading">
            
        </div>
    </form>


    <script type="text/x-template" id="vue-page">
        <ul class="pagination vue-page" >
            <li class="prev" v-show="current != 1" @click="current-- && goto(current--)"><a href="javascript:;">上一页</a></li>
            <li v-for="pageindex in pages" @click="goto(pageindex)" :class="{'active':current == pageindex}" :key="pageindex">
              <a href="javascript:;" >@{{pageindex}}</a>
            </li>
            <li class="last-w" v-show="allpage != current && allpage != 0 " @click.stop-1="current++ && goto(current++)" @click.stop-2="increment"><a href="javascript:;" class="next">下一页</a></li>
            <li>
                <span class="p-info">共@{{allpage}}页<!-- ,到第<input :disabled="allpage==1" v-model="input"  @blur.stop-1="goto(input,1)" @keydown.13="goto(input,1)" @keyup.13="selectThis($event)" type="text" class="form-control">页 --></span>
            </li>
        </ul>
    </script>


    @endsection

    @section('footer')
        @include('HomeV2._layout.footer')
    @endsection

    @section('login')
        @include('HomeV2._layout.login')
    @endsection

    @section('js')

    <script type="text/javascript">
        
        seajs.use(["vendor/vue","module/search/search.adv", "module/common/common", "bt"],function(a,b,c,d){
            //初始化数据
            //页面加载的时候的初始化方法
            //加载新车上牌地区,品牌,车系,车型规格
            //厂商指导价,座位数,生产国别,基本配置
            //车源距离,车身颜色,内饰颜色,行驶里程,出厂年月,
            //排放标准,付款方式,上牌用途
            //车源列表
            //参数说明
            //brandId:品牌ID , carSeriesId:车系ID,vehicleModelId:车型ID
            //brand:品牌名称,carSeries:车系名称,vehicleModel:车型名称
            //brandImage:品牌图片
             
            b.init({
                brandId:{{$brands['gc_id']}},
                carSeriesId:{{$brands['xi_id']}},
                vehicleModelId:{{$brands['xin_id']}},
                brand:"{{$brands['xi_name']}}",
                carSeries:"{{$brands['xin_name']}}",
                vehicleModel:"{{$brands['gc_name']}}",
                brandImage:"{{$brands['detail_img']}}"
            })
            //设置省份和城市
            //初始化简易城市列表选择框
            b.initArea({province:'{{$region['parent_name']}}',city:'{{$region['name']}}',cityId:'{{$region['area_id']}}'})
            b.initUploadPath("http://upload.hwache.cn/")
        });
    </script>
@endsection
