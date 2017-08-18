
define(function (require, exports, module) {

    var app = new Vue({
        el: '#vue',
        data: { 
           url:{
                brandListUrl:"/js/vendor/brand.js",             //获取品牌列表的请求地址
                carSeriesListUrl:"/js/vendor/car-info.js",      //获取车系列表的请求地址
                vehicleModeListUrl:"/js/vendor/car-info.js",    //获取车型列表的请求地址
           },
           form:{
              phone:"",
              code:"" 
           },
           codeStatus:0,
           isCodeError:!1,
           isLoading:!1,
           time:{
                hours:[0,0],
                minites:[0,0],
                seconds:[0,0],
                hour:0,
                minite:0,
                second:0 
            },
            totalCount:10,
            errorCount:0,
            countDownNum:5,
            isSimpleLoading:!1,
            countDownObj:{},
            brandList:[],
            seriesList:[],
            modelsList:[],
            brandId:0,
            carSeriesId:0,
            modelId:0,
            isSelectModel:!1,
            isInput:!1,
            answerError:!1,
            cardNum:"",
            isSendLoading:!1,
            isSelectBrand:!1,
            isValite:!1         //是否验证过

          
        },
        mounted:function(){
           //param 当前时间,结束时间
           //this.countDown('2017-2-20 14:24:00','2017-2-20 14:40:06')
           this.getBrandList()
        }
        ,
        methods:{ 
            send:function(event){

                var _flag = !0
                this.isSendLoading = !0
                if (this.errorCount >= 10) {
                   app.isLoading = !0
                   return
                }
                if (this.brandId === 0 || this.carSeriesId === 0 || this.modelId === 0 ) {
                   this.isSelectModel = !0
                   _flag = !1
                }
                if (this.isValite) {
                    if (this.cardNum === "") {
                       this.isInput = !0
                       _flag = !1
                    }
                }
                if (_flag){
                    require("vendor/jquery.form")
                    var _form  = $("#password-step-3")
                    var options = {
                        type: 'post' ,
                        beforeSend: function(data) {
                            app.isLoading = !0
                        },
                        success: function(data) {
                            app.isLoading = !1
                            if (data.code == 0) {
                               app.answerError = !0
                               app.errorCount++
                            }
                            else if (data.code == 1) {
                              window.loaction.href = ""
                            }
                        },
                        error: function(msg) {
                            app.answerError = !0
                            app.isLoading = !1 
                            app.errorCount++
                        }
                    }
                    _form.ajaxForm(options).ajaxSubmit(options)
                     
                }
            }, 
            getBrandList:function(){
                var _this = this
                $.ajax({
                     type: "GET",
                     url:_this.url.brandListUrl,
                     data: {},
                     dataType: "json",
                     success: function(data){
                        app.brandList = data      
                     }
                })   
            },
            getCarSeriesList:function(){
                var _this = this 
                $.ajax({
                     type: "GET",
                     url:_this.url.carSeriesListUrl,
                     data: {},
                     dataType: "json",
                     success: function(data){
                         var _data = []
                         for(key in data) if(key == _this.brandId) _data = data[key] 
                         _this.seriesList = _data  
                     }
                }) 

            }, 
            getVehicleModeList:function(){
                var _this = this 
                $.ajax({
                     type: "GET",
                     url:_this.url.vehicleModeListUrl,
                     data: {},
                     dataType: "json",
                     success: function(data){
                         var _data = []
                         for(key in data) if(key == _this.carSeriesId) _data = data[key]
                         _this.modelsList = _data  
                     }
                }) 

            },
            countDown:function(curTime,endTime){
                this.doCountDown(curTime,endTime,function(){
                    //倒计时回调
                    window.location.href="6.1.1.超时修改失败.html"
                })  
            },
            doCountDown:function(curTime,endTime,callback){
                var _this = this
                var _start = new Date(curTime) , _end = new Date(endTime)
 
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
            }
            ,
            setCode:function() { 
                this.codeStatus = 0 
            }
            ,
            resetSelectError:function(){
                this.isSelectModel = !1
                this.isSendLoading = !1
            },
            resetInputError:function(){
                this.isInput = !1
                this.isSendLoading = !1
            }, 

        }
        ,
        watch:{
            'errorCount':function(n,o){
                 
            },
            'brandId':function(n,o){
                this.seriesList = []
                this.modelsList = []
                this.carSeriesId = 0
                this.modelId = 0
                if (n!=0){
                    this.getCarSeriesList()
                }
                this.resetSelectError()
            },
            'carSeriesId':function(n,o){
                this.modelsList = []
                this.modelId = 0
                if (n!=0){
                    this.getVehicleModeList()
                }
                this.resetSelectError()
            },
            'modelId':function(n,o){
                this.resetSelectError()
            },
             
        }

    })
  
    module.exports = {
        init : function(nowTime,endTime){
             app.countDown(nowTime,endTime)
        },
        initValiteOption : function(isValite){
            app.isValite = isValite
        } 
    }
});