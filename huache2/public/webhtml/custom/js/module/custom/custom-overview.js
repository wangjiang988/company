define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left") 
    require("/webhtml/common/js/vendor/time.jquery")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            startTime:"",
            startTimeTemp:"",
            endTime:"",
            endTimeTemp:"",
            maxDate:"",
            month:1,
            simpleTimeList:[
                {txt:'\u5f53\u65e5' ,val:0 ,select:!1},
                {txt:'\u0031\u4e2a\u6708',val:1 ,select:!1},
                {txt:'\u0033\u4e2a\u6708',val:3 ,select:!1},
                {txt:'\u4e00\u5e74' ,val:12,select:!1}
            ]
        },
        mounted:function(){
            setTimeout(function(){
                if (app.endTimeTemp == "")
                    app.setSeachTime()
            })
        }
        ,
        methods:{
            timeInit: function (obj,countdwontype,startTime,endTime,call) {
                obj.CountDown({
                      startTime:startTime,
                      endTime :endTime,
                      timekeeping:countdwontype || 'countdown',
                      callback:function(){
                          if (call) {call()}
                      }
                }) 
            },
            setBaseMonth:function(time){
                this.month = time.val
                this.clearSimpleTimeSelect()
                time.select = !time.select
                this.setDefaultStartTime()
                setTimeout(function(){
                    $(".detial").click();
                })
            },
            clearSimpleTimeSelect:function(time){
                this.simpleTimeList.forEach(function(item){
                    item.select = !1
                })
            },
            setSeachTime:function(){
                this.setDefaultEndTime()
                if (this.startTimeTemp!="") {
                    this.setStartTime()
                }else
                    this.setDefaultStartTime()
            },
            setStartTime:function(){
                var _newstrattime = new Date(Date.parse(this.startTimeTemp))
                this.startTime = this.formateZhTime(_newstrattime)
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
                    _this.clearSimpleTimeSelect()
                }})

            },
            selectEndTime:function(event){
                var _this = this
                WdatePicker({el:event.target,dateFmt:'yyyy年MM月dd日',realDateFmt:'yyyy年MM月dd日',minDate:this.formatDefDate(this.startTime),maxDate:this.maxDate,errDealMode:2,onpicking:function(dp){
                    var _newdate = dp.cal.newdate
                    var _date = _newdate.y + "-" + _newdate.M + "-" + _newdate.d
                    _this.endTime = _this.formateZhTime(new Date(_date))
                    _this.clearSimpleTimeSelect()
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
            }
        },
        watch:{

        }

    })

    module.exports = {
        initEndTime:function(endtime,startime){
            app.endTimeTemp = endtime
            app.startTimeTemp = startime || ""
            app.setSeachTime()
        },
        init:function(obj,countdwontype,startTime,endTime,call){
           app.timeInit(obj,countdwontype,startTime,endTime,call)
        }
    }
})