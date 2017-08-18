define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left")
    require("/webhtml/custom/js/module/custom/dropdown-components")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            intoWayList:[{name:"全部",id:1},{name:"正在核实",id:2},{name:"已入账",id:3},{name:"无此款项",id:4}],
            startTime:"",
            endTime:"",
            endTimeTemp:"",
            startTimeTemp:"",
            startTimeTemp2:"",
            maxDate:"",
            startTime2:"",
            endTime2:"",
            endTimeTemp2:"",
            maxDate2:""
        },
        mounted:function(){
            setTimeout(function(){
                if (app.endTimeTemp == "")
                    app.setSeachTime()
            })
        }
        ,
        methods:{
            getInfoWayStatus:function(id,val){
                console.log(id,val)
            },
            getBillingStatus:function(id,val){
                console.log(id,val)
            },
            setSeachTime:function(){
                if (this.startTimeTemp!="" && this.startTimeTemp2!="") {
                    this.startTime = this.startTimeTemp;
                    this.startTime2 = this.startTimeTemp2;
                }else
                    this.setDefaultStartTime();

                if (this.endTimeTemp!="" && this.endTimeTemp2!="") {
                    this.endTime  = this.endTimeTemp;
                    this.endTime2 = this.endTimeTemp2;
                }else
                    this.setDefaultEndTime()
            },
            setDefaultStartTime:function(){
                var _newstrattime = new Date(Date.parse(this.endTimeTemp))
                if (this.month === 1)
                    _newstrattime.setMonth(this.endTimeTemp.getMonth() - 1)
                this.startTime = this.formateZhTime(_newstrattime)
                this.startTime2 = this.startTime
            },
            setDefaultEndTime:function(){
                var _curdate = new Date(Date.parse(this.endTimeTemp))
                _curdate = _curdate == "Invalid Date" ? new Date() : _curdate
                this.endTimeTemp = _curdate
                this.endTime = this.formateZhTime(_curdate)
                this.maxDate = this.formatDefDate(this.endTime)
                //
                this.endTimeTemp2 = this.endTimeTemp
                this.endTime2 = this.endTime
                this.maxDate2 = this.maxDate
            },

            selectStartTime: function (event) {
                var _this = this
                WdatePicker({el:event.target,dateFmt:'yyyy年MM月dd日',realDateFmt:'yyyy年MM月dd日',maxDate:this.endTimeTemp,errDealMode:2,onpicking:function(dp){
                    var _newdate = dp.cal.newdate
                    var _date = _newdate.y + "-" + _newdate.M + "-" + _newdate.d
                    _this.startTime = _this.formateZhTime(new Date(_date))

                }})
            },
            selectStartTime2: function (event) {
                var _this = this
                WdatePicker({el:event.target,dateFmt:'yyyy年MM月dd日',realDateFmt:'yyyy年MM月dd日',maxDate:this.endTimeTemp,errDealMode:2,onpicking:function(dp){
                    var _newdate = dp.cal.newdate
                    var _date = _newdate.y + "-" + _newdate.M + "-" + _newdate.d
                    _this.startTime2 = _this.formateZhTime(new Date(_date))
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
            selectEndTime2:function(event){
                var _this = this
                WdatePicker({el:event.target,dateFmt:'yyyy年MM月dd日',realDateFmt:'yyyy年MM月dd日',minDate:this.formatDefDate(this.startTime),maxDate:this.maxDate,errDealMode:2,onpicking:function(dp){
                    var _newdate = dp.cal.newdate
                    var _date = _newdate.y + "-" + _newdate.M + "-" + _newdate.d
                    _this.endTime2 = _this.formateZhTime(new Date(_date))
                    _this.endTimeTemp2 = _date
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
        initEndTime:function(endTime,startTime,endTimeTemp2,startTimeTemp2){
            app.endTimeTemp         = endTime;
            app.endTimeTemp2        = endTimeTemp2;
            app.startTimeTemp       = startTime;
            app.startTimeTemp2      = startTimeTemp2;
            app.setSeachTime();
        }
    }
})
