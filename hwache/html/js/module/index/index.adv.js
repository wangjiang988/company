define(function (require, exports, module) {

    var app = new Vue({
        el: '#vue',
        data: {
            url:{
                areaListUrl:"/js/vendor/front-area.js",
                brandListUrl:"/js/vendor/brand.js",
                carSeriesListUrl:"/js/vendor/car-info.js",
                vehicleModeListUrl:"/js/vendor/car-info.js"
            }, 
            areaList:[],
            cityList:[],
            province :"",
            city:"",
            cityId:0,
            cityValIndex:0,
            isShowCityList:false,
            isShowProvince:false,
            time:{
                hours:[0,0],
                minites:[0,0],
                seconds:[0,0],
                hour:0,
                minite:0,
                second:0,
                timeFrame:[9,12,13,17]
            },
            isCountDown : !0,
            search:{
                isShowBrand:!1,
                isShowCarSeries:!1,
                isShowVehicleModel:!1,
                brandList:[],
                brand:"",
                brandId:-1,
                carSeriesList:[],
                carSeries:"",
                carSeriesId:-1,
                vehicleModelId:-1,
                vehicleModeList:[],
                vehicleMode:"",
                brandError:"",
                carSeriesError:"",
                vehicleModeError:""
            }
           
        },
        mounted:function(){
            this.getAreaList()
            this.getBrandList()
            this.maquee()
        }
        ,
        methods:{

            maquee:function(gc_id,vehicleMode,key){
                var _maquee = document.getElementById("maquee")
                var _li = _maquee.getElementsByTagName("li")
                var _lilength = _li.length
                var _maqueelength = 1
                var _top = -22
                var scrollTimer = setInterval(function(){  
                    if (_maqueelength == _lilength) {
                        _top = 0
                        _maqueelength = 1
                    }else{
                        _top =  _maqueelength * -22
                        _maqueelength++
                    }
                    $(_maquee).animate({marginTop:_top}, 1000) 
                },5000)
            },
            selectModel:function(gc_id,vehicleMode,key){
                 this.search.vehicleMode = vehicleMode
                 //this.search.carSeriesId = id 
                 //this.search.vehicleModelId = gc_id 
                 this.search.vehicleModeError = ""
                 this.showVehicleModel()
            },
            showVehicleModel:function(){
                this.search.isShowVehicleModel = !this.search.isShowVehicleModel
                this.search.vehicleModelError = ""
            },
            getVehicleModeList:function(){
                var _this = this 
                $.ajax({
                     type: "GET",
                     url:_this.url.vehicleModeListUrl,
                     data: {id:_this.search.vehicleModelId},
                     dataType: "json",
                     success: function(data){
                         var _data = []
                         for(key in data) if(key == _this.search.vehicleModelId) _data = data[key]
                         _this.search.vehicleModeList = _data  
                     }
                }) 

            },
            selectCar:function(id,carSeries){
                 this.search.carSeries = carSeries
                 this.search.vehicleModelId = id 
                 this.showCarSeries()
                 this.search.vehicleMode = ""  
            },
            showCarSeries:function(){
                this.search.isShowCarSeries = !this.search.isShowCarSeries
                this.search.carSeriesError = ""
            },
            getCarSeriesList:function(){
                var _this = this 
                $.ajax({
                     type: "GET",
                     url:_this.url.carSeriesListUrl,
                     data: {id:_this.search.carSeriesId},
                     dataType: "json",
                     success: function(data){
                         var _data = []
                         for(key in data) if(key == _this.search.carSeriesId) _data = data[key] 
                         _this.search.carSeriesList = _data  
                     }
                }) 

            },
            selectBrand:function(id,brand,carSeriesId){
                 this.search.brand = brand
                 this.search.brandId = id 
                 this.search.carSeriesId = carSeriesId 
                 this.showBrand()
                 this.search.vehicleMode = "" 
                 this.search.carSeries = "" 
            },
            getBrandList:function(){
                var _this = this 
                $.ajax({
                     type: "GET",
                     url:_this.url.brandListUrl,
                     data: {},
                     dataType: "json",
                     success: function(data){
                         _this.search.brandList = data      
                     }
                }) 

            },
            showBrand:function(){
                this.search.isShowBrand = !this.search.isShowBrand
                this.search.brandError = ""

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
                if (_target.tagName === "A")  
                    _target = _target.getElementsByTagName('span')[0]
                $(_target).addClass("cur").parent().siblings().find("span").removeClass('cur')
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
            initData:function(p,c,cityid){
                this.province = p || "江苏省"
                this.city = c || "苏州市"
                this.cityId = cityid
            }
            ,
            countDown:function(yyyy,mm,dd,h,m,s){
                var _this = this
                if ((h >= this.time.timeFrame[0] && h < this.time.timeFrame[1]) || (h >= this.time.timeFrame[2] && h < this.time.timeFrame[3]) ) {
                    this.clearZero()
                    this.doCountDown(yyyy,mm,dd,h,m,s,function(){
                        window.location.reload()
                    })
                    
                }else{
                    this.isCountDown = !0 //显示倒计时
                    this.doCountDown(yyyy,mm,dd,h,m,s,function(){
                        _this.isCountDown = !1
                    })
                }  
                
            },
            doCountDown:function(yyyy,mm,dd,h,m,s,callback){
                var _this = this
                var _start = new Date(yyyy,mm,dd,h,m,s) , _end = new Date(yyyy,mm,dd,h,m,s)
                _start.setHours(h) 
                _start.setMinutes(m) 
                _start.setSeconds(s) 
                var _endhour = h >= this.time.timeFrame[1] ? 
                       (
                           h >= this.time.timeFrame[3] ? (24 - h + this.time.timeFrame[0] + h) 
                           : 
                           (h < this.time.timeFrame[2] ?  this.time.timeFrame[2] : this.time.timeFrame[3])
                       ) 
                       : 
                       (h < this.time.timeFrame[0] ? this.time.timeFrame[0] : this.time.timeFrame[1])

                _end.setHours(_endhour) 
                _end.setMinutes(0) 
                _end.setSeconds(0)  
                //console.log(_start,_end)
                var _diff = (_end - _start) / 1000
                var _set = setInterval(function(){
                    if(_diff == 0) {
                        clearInterval(_set)
                        if (callback) {callback()}
                    }
                    var hh = parseInt(_diff / 60 / 60 % 24, 10)   
                    var mm = parseInt(_diff / 60 % 60, 10)   
                    var ss = parseInt(_diff % 60, 10) 
                   
                    _this.time.hours = [hh.toString().length == 2 ? hh.toString().slice(0,1) : 0 , hh.toString().length == 1 ? hh : hh.toString().slice(1)]
                    _this.time.minites = [mm.toString().length == 2 ? mm.toString().slice(0,1) : 0 , mm.toString().length == 1 ? mm : mm.toString().slice(1)]
                    _this.time.seconds = [ss.toString().length == 2 ? ss.toString().slice(0,1) : 0 , ss.toString().length == 1 ? ss : ss.toString().slice(1)]
                    _diff--
                },1000)
            },
            clearZero:function(){
                this.time.hours = [0,0] 
                this.time.minites = [0,0] 
                this.time.seconds = [0,0] 
                this.time.hour = 0 
                this.time.minite = 0
                this.time.second = 0 
                this.isCountDown = !1
                
            },
            searchForm:function(){
                this.search.brandError = ""
                this.search.carSeriesError = ""
                this.search.vehicleModeError = ""

                if (this.search.brand === "") {
                    this.search.brandError = "请选择"
                }
                else if (this.search.carSeries === "") {
                    this.search.carSeriesError = "请选择"
                }
                else if (this.search.vehicleMode === "") {
                    this.search.vehicleModeError = "请选择"
                }else{
                    //indexSearchForm
                     document.getElementsByName('indexSearchForm')[0].submit();
                }

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
            'search.carSeriesId':function(news,olds){
                this.getCarSeriesList()
            },
            'search.vehicleModelId':function(news,olds){
                this.getVehicleModeList()
            }
            
        }

    })
  
    module.exports = {
        init : function(p,c,cityid){
            app.initData(p,c,cityid)
        },
        countDown: function(yyyy,mm,dd,h,m,s){
            app.countDown(yyyy,mm,dd,h,m,s)
        }
    }
});