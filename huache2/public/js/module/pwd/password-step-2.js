
define(function (require, exports, module) {
    var app = new Vue({
        el: '#vue',
        data: {
           url:{
              valiteCodeUrl:"",
              countdownUrl:"",
              passwordRestUrl:""
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
            endTime:""
        },
        mounted:function(){
           //param 当前时间,结束时间
           //this.countDown('2017-02-28 15:00:00','2017-02-28 15:20:00');
        }
        ,
        methods:{
            send:function(event){
                if (this.errorCount >= 10) {
                   app.isLoading = !0
                   return
                }
                if (this.form.code === "") {
                   this.codeStatus = 2
                }else {
                    require("vendor/jquery.form")
                    var _form  = $("#password-step-2")
                    var options = {
                        type: 'post' ,
                        beforeSend: function(data) {
                            app.isLoading = !0
                        },
                        success: function(data) {
                            //alert(data.count)
                            /*if (data.count == 10) {
                                window.location.href = "/member/showPassword"
                            }*/
                            app.isLoading = !1
                            if (data.success == 0) {
                                app.isCodeError = !0
                                app.codeStatus = 3
                                app.errorCount = data.count;
                            }
                            else if (data.success == 1) {
                                window.location.href = app.url.passwordRestUrl;//"/member/password/reset";
                            }
                        },
                        error: function(msg) {
                            app.codeStatus = 3
                            app.isLoading = !1
                            app.errorCount++
                        }
                    }
                    _form.ajaxForm(options).ajaxSubmit(options);
                }
            },
            endSub:function(event){
               var event = event || window.event
               event.preventDefault()
               window.event.returnValue = false
               return false
            },
            reSend:function(){
               require("module/common/hc.popup.jquery")
               $("#reSendWin").hcPopup({'width':'420'})
            },
            sendCode:function(){

               $("#reSendWin").hide()
               $.ajax({
                    url: app.url.valiteCodeUrl,
                    type: "post",
                    dataType: "json",
                    data: {
                        phone:app.form.phone,
                        code:app.form.code,
                        template_code:'78575070',
                        code:1,
                        max:2,
                        endtime:app.endTime,
                        _token:$("input[name='_token']").val()
                    },
                    beforeSend: function () {
                        app.isSimpleLoading = !0
                    }
                    ,
                    success: function (data) {
                        app.isSimpleLoading = !1
                        if (data.success == 1) {
                            //
                             $("#sendCodeWin").hcPopup({'width':'450'})
                        }else{
                             $("#sendErrorWin").hcPopup({'width':'450'})
                        }
                        app.simpleCountDown()
                    },
                    error:function(msg){
                        app.isSimpleLoading = !1
                        /*app.simpleCountDown()
                        $("#sendErrorWin").hcPopup({'width':'450'})*/
                    }
                })

            },
            simpleCountDown:function(){
                var _time = setInterval(function () {
                    if (app.countDownNum <=0) {
                       $("#sendCodeWin").hide()
                       $("#sendErrorWin").hide()
                    }
                    app.countDownNum--
                },1000)
                this.countDownObj = _time
            },
            countDown:function(curTime,endTime,isTime,timeOUtUrl){
                if(isTime == 'false'){
                    //倒计时回调
                    window.location.href= app.url.countdownUrl;//"/member/pwdOver";
                }
                this.doCountDown(curTime,endTime,function(){
                    //倒计时回调
                    $.getJSON(timeOUtUrl,{
                        phone:app.form.phone,
                        template_code:'78575070'
                    },function(result){
                        //没有返回值直接fail了
                        window.location.href = app.url.countdownUrl
                    }).fail(function(){
                        window.location.href = app.url.countdownUrl
                    });
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
            changCode:function() {
            	this.isCodeError = !1
                if (this.form.code === "") {
                	this.codeStatus = 2
                	this.isCodeError = !0
                }
            }

        }
        ,
        watch:{
            'errorCount':function(n,o){
                if (n >= 10) {
                   app.isLoading = !0
                }
            }
            ,
            'countDownNum':function(n,o){
                if (n < 0) {
                  clearInterval(app.countDownObj)
                  app.countDownNum = 5
                }

            }
        }

    })

    module.exports = {
        init : function(phone,checkCodeUrl,countdownUrl,passwordRestUrl){
            app.form.phone = phone;
            app.url.valiteCodeUrl = checkCodeUrl;
            app.url.countdownUrl = countdownUrl;
            app.url.passwordRestUrl = passwordRestUrl;
        },
        initCountDown : function(nowTime,endTime,isTime,timeOutUrl){
             app.countDown(nowTime,endTime,isTime,timeOutUrl)
             app.endTime = endTime;
        },
        initErrorCount : function(count){
             app.errorCount = count == "" ? 10 : count
             //console.log(count,app.errorCount)
        }
    }
});