define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")  
    require("/webhtml/custom/js/module/custom/dropdown-components") 
    //require("/webhtml/common/js/vendor/jquery.form") 

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            startTime:"",
            endTime:"",
            endTimeTemp:"",
            maxDate:"",
            month:1,
            tips:[{isShow:!1},{isShow:!1}],
            withdrawalStateList:[{name:"不限",id:1},{name:"正在办理",id:2},{name:"已完成",id:3},{name:"未成功",id:4},{name:"部分未成功",id:5}],
        },
        mounted:function(){
            setTimeout(function(){
                if (app.endTimeTemp == "") 
                    app.setSeachTime() 
            })
        }
        ,
        methods:{
            tip:function(index){ 
                this.tips[index].isShow = !this.tips[index].isShow
            },
            getWithdrawalState:function(id,val){
                console.log(id,val)
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
            }
        },
        watch:{
           
        }

    })  
  
    module.exports = {
        initEndTime:function(endTime,startTime){
            app.endTimeTemp   = endTime;
            app.startTimeTemp = startTime;
            app.setSeachTime()
        }
    }
})
