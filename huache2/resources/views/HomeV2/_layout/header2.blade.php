<div class="head-wrapper">
    <div class="head-container">
        <div class="head-logo-wrapper psr">
            <a class="logo" href="/">华车</a>
            @if(Route::currentRouteName() =='/')
            <div class="index-area-wrapper">
                <span class="location pos-abt hide" :class="{display:city!='','location-hover':isShowCityList}" @click="showCityList" >
                    <label>@{{city}}</label>
                    <input type="hidden" name="area" v-model="cityId">
                    <i id="i-show" @click="showCityList" ></i>
                </span>
                <div id="citylist" v-cloak class="loca-c psa" v-show="isShowCityList">
                    <a v-for="city in cityList" href="javascript:;" @click="selectCity(city.area_id,city.area_name)">@{{city.area_name}}</a>
                    <div v-cloak class="box clear block text-right">
                        <a href="javascript:;"  @click.stop="setCityValIndex(0)" @click="showProvince">更多城市&gt;&gt;</a>
                    </div>
                </div>

                <div v-cloak v-show="isShowProvince" id="SelectAreaWin" class="popupbox2 p-city-wrapper">
                    <div class="popup-title popup-title-white psr">
                        <span class="fl fs16"><b>选择城市</b></span>
                        <dl class="letter-nav fl">
                            <dd>
                                <a @click="position" v-for="(letter,tag) in areaList" :data-obj="'#NAV-'+tag" href="javascript:;"><span :data-obj="'#NAV-'+tag" class="">@{{tag}}</span></a>
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
                                                    <li @click.stop="selectCity(city.area_id,city.area_name)" @click="setProvince(province.area_name)"  v-for="city in province.child" class="city" :data-id="city.area_id" :data-value="city.area_name">
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
            </div>
            @endif

        </div>

        <ul class="head-login-wrapper">
            <!--客户登录后-->
             @if (Auth::check())
                <li class="loginout">
                    <label>欢迎您，<a href="{{ route('user.home') }}" class="juhuang">
                        {{ getNikeName(Auth::user()->id,Auth::user()->phone) }}
                    </a>
                    <a href="{{ route('user.home') }}" class="hc-status inline-block">我的华车</a> </label>
                    <a @click="loginOutConfirm" href="javascript:;" class="hc-status-loginout">下车<em>(退出)</em>
                    </a>
                    <div v-cloak v-show="isShowLoginOut" class="login-out-comfirm">
                        <p>退出登录状态将重新回到<br>华车首页，是否继续？</p>
                        <div class="confirm">
                            <a @click="sureLoginOut(0)" href="javascript:;">确认退出</a>
                            <a @click="canceLoginOut" href="javascript:;" class="cancel">取消</a>
                        </div>
                    </div>
                </li>
            @else
                <li>
                    <a class="login-link" @click="login" href="javascript:;">快速登录</a>
                    <a href="{{ route('user.getReg') }}"  class="ml20">快捷注册</a>
                </li>
            @endif

        </ul>
        <div class="clear"></div>
    </div>
</div>