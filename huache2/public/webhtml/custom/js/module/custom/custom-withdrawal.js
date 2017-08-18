define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin");
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left")
    require("/webhtml/custom/js/module/custom/dropdown-components");
    require("/webhtml/user/js/module/user/user-code-count-down-component");
    require("/webhtml/common/js/vendor/hc.popup.jquery");

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            countDownNum:5,
            countDownObj:{},
            doWithdrawalURl:"",
            checkMsgCode:"",
            successUrl:"",
            withdrawalId:0,
            isEmtpy:!1,
            isError:!1,
            isCodeError:!1,
            code:"", 
            startTime:"",
            endTime:"",
            endTimeTemp:"",
            maxDate:"",
            month:1,
            tips:[{isShow:!1},{isShow:!1}],
            withdrawalStateList:[{name:"全部",id:1},{name:"正在办理",id:2},{name:"已完成",id:3},{name:"未完成",id:4}],
    
        },
        mounted:function(){
            setTimeout(function(){
                if (app.endTimeTemp == "")
                    app.setSeachTime()
            }) 
            
        }
        ,
        methods:{
            simpleCountDown:function(call) {
                var _time = setInterval(function() {
                    if (app.countDownNum <= 0) {
                        clearInterval(app.countDownObj)
                        if (call) { call() }
                    } else
                        app.countDownNum--
                }, 1000)
                this.countDownObj = _time
            },
            applyStop:function(_txId){
                app.withdrawalId = _txId
                $("#phoneValite").hcPopup({'width':'420'})

            },
             
            checkCode:function(){
                var _flag = !1;
                var _phone = $("input[name='phone']").val();
                $.ajax({
                    url: app.checkMsgCode,
                    type: "get",
                    data: {'phone':_phone,'code':this.code,'template_code':'78760078'},
                    dataType: "json",
                    async:false,
                    beforeSend: function() {

                    },
                    success: function(data) {
                        _flag = data.success
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        _flag = !1;
                    }
                })
                return _flag
            },
            doWithdrawal:function(){
                if (this.code == "") {
                    this.isEmtpy = !0
                    this.isError = !1
                    setTimeout(function(){
                        app.isEmtpy = !1
                    },3000)
                }
                else if(!app.checkCode()){
                    app.isEmtpy = !1
                    this.isError = !0
                    setTimeout(function(){
                        app.isError = !1
                    },3000)
                }
                else{
                    this.isEmtpy     = !1;
                    this.isCodeError = !1;
                    this.isError     = !1;

                    $.ajax({
                        url: app.doWithdrawalURl,
                        type: "get",
                        data: {dwb_id:app.withdrawalId},
                        dataType: "json",
                        beforeSend: function() {

                        },
                        success: function(data) {

                            //验证码错误
                            if (data.success == 0) {
                                app.isError = !0
                                setTimeout(function(){
                                    app.isError = !1
                                },3000)
                            }
                            //验证码失效
                            else if (data.success == 2) {
                                app.isCodeError = !0
                                setTimeout(function(){
                                    app.isCodeError = !1
                                },3000)
                            }
                            //已经接单
                            else if (data.success == 3) {
                                app.simpleCountDown(function() {
                                    window.location.reload()
                                })
                                $("#errorWin").hcPopup({'width':'420'})
                            }
                            //没有接单
                            else if (data.success == 1) {
                                app.simpleCountDown(function() {
                                    window.location.reload()
                                })
                                $("#successWin").hcPopup({'width':'420'})
                                //window.location.href = app.successUrl;//"k.2.8页面"
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            app.isCodeError = !0
                            setTimeout(function(){
                                app.isCodeError = !1
                            },3000)
                        }
                    })
                }
            },
            getCode:function(code){
                this.code = code
                if (code == "") {
                    this.isEmtpy = !0
                    setTimeout(function(){
                        app.isEmtpy = !1
                    },3000)
                }

            },
            tip:function(index){
                this.tips[index].isShow = !this.tips[index].isShow
            },
            getWithdrawalState:function(id,val){
                 
            },
            setSeachTime:function(){
                if(this.endTimeTemp !=''){
                    this.endTime = this.endTimeTemp;
                }else
                this.setDefaultEndTime();

                if(this.startTimeTemp !=''){
                    this.startTime = this.startTimeTemp;
                }else
                this.setDefaultStartTime();
            },
            setDefaultStartTime:function(){
                var _newstrattime = new Date(Date.parse(this.endTimeTemp))
                if (this.month === 1)
                    _newstrattime.setMonth(this.endTimeTemp.getMonth() - 1)
                if (this.month === 3)
                    _newstrattime.setMonth(this.endTimeTemp.getMonth() - 3)
                if (this.month === 12)
                    _newstrattime.setYear(this.endTimeTemp.getFullYear() - 1)
                this.startTime = this.formateZhTime(_newstrattime)
            },
            setDefaultEndTime:function(){
                var _curdate = new Date(Date.parse(this.endTimeTemp))
                _curdate = _curdate == "Invalid Date" ? new Date() : _curdate
                this.endTimeTemp = _curdate
                this.endTime = this.formateZhTime(_curdate)
                this.maxDate = this.formatDefDate(this.endTime)
            },

            selectStartTime: function (event) {
                var _this = this
                WdatePicker({el:event.target,dateFmt:'yyyy年MM月dd日',realDateFmt:'yyyy年MM月dd日',maxDate:this.endTimeTemp,errDealMode:2,onpicking:function(dp){
                    var _newdate = dp.cal.newdate
                    var _date = _newdate.y + "-" + _newdate.M + "-" + _newdate.d
                    _this.startTime = _this.formateZhTime(new Date(_date))
                }})

            },
            selectEndTime:function(event){
                var _this = this
                WdatePicker({el:event.target,dateFmt:'yyyy年MM月dd日',realDateFmt:'yyyy年MM月dd日',minDate:this.formatDefDate(this.startTime),maxDate:this.maxDate,errDealMode:2,onpicking:function(dp){
                    var _newdate = dp.cal.newdate
                    var _date = _newdate.y + "-" + _newdate.M + "-" + _newdate.d
                    _this.endTime = _this.formateZhTime(new Date(_date))
                    _this.endTimeTemp = _date
                }})
            },
            formatDefDate:function(time){
                return time.replace("年","-").replace("月","-").replace("日","")
            },
            formateZhTime:function(time){
                var month = time.getMonth().toString().length == 1 ? ("0" + (time.getMonth() + 1)) : (time.getMonth() + 1)
                var day = time.getDate().toString().length == 1 ? ("0" +  time.getDate()) :  time.getDate()
                return time.getFullYear() + "年" + month + "月" + day + "日"
            },
            
        },
        watch:{

        }

    })

    module.exports = {
        initEndTime:function(endTime,startTime){
            app.endTimeTemp   = endTime;
            app.startTimeTemp = startTime;
            app.setSeachTime()
        },
        initUrl:function(doWithdrawalURl,successUrl,checkMsgCode){
            app.doWithdrawalURl = doWithdrawalURl;
            app.checkMsgCode    = checkMsgCode;
            app.successUrl      = successUrl;
        }
    }
})
