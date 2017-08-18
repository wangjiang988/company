define(function (require, exports, module) {
    
    var app = new Vue({
        el: '.head-wrapper',
        data: {
            isShowLoginOut:!1,
            url:{
                areaListUrl:"/js/vendor/front-area.js"
            },
            areaList:[],
            cityList:[],
            province :"",
            city:"",
            cityId:0,
            cityValIndex:0,
            isShowCityList:false,
            isShowProvince:false,
            isInit:!1
        },
        mounted:function(){
            this.initData()
            this.getAreaList()
            
        }
        ,
        methods:{
            initEvent:function(){
                var _brand = document.getElementById("brandObj")
                var _car   = document.getElementById("carSeriesObj")
                var _model = document.getElementById("modelObj")
                //console.log($(_brand).css("display"),$(_car).css("display"),$(_model).css("display"))
                if (_brand) {
                    if ($(_brand).css("display") == "block")
                        $(_brand).prev().click()
                    if ($(_car).css("display") == "block")
                        $(_car).prev().click()
                    if ($(_model).css("display") == "block")
                        $(_model).prev().click()
                }
                this.isInit = !0
            },
            login:function(){
                //console.log("login")
                //$(this).blur();
                var _scrollTop = $(document).scrollTop();
                var _winWidth = $(window).width();
                var _loginWin = $('#login');
                var _winLeft = (_winWidth / 2) - (_loginWin.width() / 2);
                _loginWin.css(
                                {
                                    "left": _winLeft + "px",
                                    "top":  "20%"
                                }
                            )
                        .show();
                $('.zm')/*.css({"top": _scrollTop +"px"})*/.fadeIn('300');

            }, 

            initData:function(p,c,cityid){
                this.province = p || "江苏省"
                this.city = c || "苏州市"
                this.cityId = cityid || 166
            },
            setProvince:function(province){
                this.province = province
                this.showProvince()
            },
            setCityValIndex:function(index){
                this.cityValIndex = index
            },
            position:function(event){

                var _target = event.target
                var _obj = $(_target.getAttribute("data-obj"))

                if (_target.tagName === "A")
                    _target = _target.getElementsByTagName('span')[0]
                $(_target).addClass("cur").parent().siblings().find("span").removeClass('cur')
                //console.log(_obj.position().top,$("#province-city-select").scrollTop(),_obj.position().top+$("#province-city-select").scrollTop())
                //animate scrollTop 值
                //_obj.position().top 相对于父容器的高度
                //$("#province-city-select").scrollTop() 父容器滚动条高度
                //38 父容器 标题头高度 ABCFG等等
                //10 补差值
                $("#province-city-select").animate({scrollTop:_obj.position().top + $("#province-city-select").scrollTop() - 38 - 10},300)
                return !1
            },
            showProvince:function(){
                this.isShowProvince = !this.isShowProvince
                this.isShowCityList = !1
            },
            selectCity:function(cityid,city){
                this.city = city
                this.cityId = cityid
                this.isShowCityList = !1
            },
            showCityList:function(){
                this.isShowCityList = this.isShowCityList ? !1 : !0
                if (!this.isInit)
                    this.initEvent()
            },
            checkSelfCityList:function(event){
                if (event.target.value == ""){
                    var _this = this
                    setTimeout(function(){
                        _this.search.option.area.display = !1
                    },200)
                }
            },
            getAreaList:function(){
                var _this = this
                $.ajax({
                     type: "GET",
                     url:_this.url.areaListUrl,
                     data: {},
                     dataType: "json",
                     success: function(data){
                         _this.areaList = data
                     }
                })

            },
            setCityList:function(){
                var _this = this
                _this.cityList = []
                $.each(_this.areaList,function(aindex,aitem){
                    $.each(aitem,function(pindex,pitem){
                        if (pitem.area_name == _this.province) {
                            _this.cityList = pitem.child
                            return false
                        }
                    })
                })
            },
            loginOutConfirm:function(){
                this.isShowLoginOut = !this.isShowLoginOut
                return false
            },
            sureLoginOut:function(params){
                if (!store) require("/webhtml/common/js/module/store.legacy.min")
                store.remove('api_token')
                this.isShowLoginOut = !1
                window.location.href = params == 0 ? "/member/logout/" : "/dealer/loginout/"
                return false
            },
            canceLoginOut:function(){
                this.isShowLoginOut = !1
                return false
            }
        }
        ,
        watch:{
           'areaList':function(){
                this.setCityList()
            },
            'province':function(news,olds){
                var _this = this
                 setTimeout(function(){
                    _this.setCityList()
                 },1000)
            },
        }

    })

    module.exports = {
        initData : function(p,c,cityid){
            app.initData(p,c,cityid)
        },
        cloneCityList:function(){
            return app.areaList
        }
    }
})
