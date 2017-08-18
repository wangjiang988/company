define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/vendor/time.jquery")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/common/js/vendor/jquery.form")
    require("/webhtml/user/js/module/user/user-code-count-down-component")
    require("/webhtml/custom/js/module/custom/dropdown-components")
    require("/webhtml/custom/js/module/custom/dropdown-date-components")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
          ampmlist:[{id:2,name:"上午"},{id:3,name:"下午"},{id:1,name:"上午/下午"}],
            ampmrevelist:["上午/下午","上午","下午"],
            dayArray:["日","一","二","三","四","五","六"],
            timeList:[],
            timeSelect:!0,
            margin:60,
            click:0,
            marginLeft:0,
            startTime:"",
            endTime:"",
            wdate:null,
            isEmpty:!1,
            isTimeEmpty:!1,
            isUseEmpty:!1,
            negotiationTime:"",
            ampm:"",
            countDownNum:5,
            countDownObj:{},
            agree:1,
            isError:!1,
            price:"",
            ampm:""


        },
        computed: {

        },
        mounted:function(){

        }
        ,
        methods:{
            simpleCountDown:function(call){
                var _time = setInterval(function () {
                    if (app.countDownNum <=0) {
                       clearInterval(app.countDownObj)
                       if (call) {call()}
                    }else
                      app.countDownNum--
                },1000)
                this.countDownObj = _time
            },
            selectTime: function (event) {
                this.agree = 0
                var _this = this
                this.wdate = new WdatePicker({
                  el:event.target,
                  highLineWeekDay:!1,
                  dateFmt:'yyyy年MM月dd日',
                  realDateFmt:'yyyy年MM月dd日',
                  //startDate:'2017-4-3',
                  minDate:app.startTime,
                  maxDate:app.endTime,
                  errDealMode:2,
                  onpicking:function(dp){
                    var _newdate = dp.cal.newdate
                    var _date = _newdate.y + "-" + _newdate.M + "-" + _newdate.d
                    _this.negotiationTime = _this.formateZhTime(new Date(_date))
                    $(event.target).parent().next().find(".disabled").removeClass('disabled').end().next().next().removeAttr('disabled')
                  }
                })

            },

            selectAmPm:function(id,val){
                this.ampm = val
                this.ampm_id = id
            },

            initPrice:function(){
                this.isError = !1
            },
            send:function(){
              //console.log(this.agree)
              app.isEmpty = !1
              app.isError = !1

              if (this.agree == 1) {
                  $("#tipWin").hcPopup({'width':'420'})
              }else if(this.agree == 0){
                  if (this.negotiationTime == "" || this.ampm == ""){
                     this.isEmpty = !0
                     setTimeout(function(){
                         app.isEmpty = !1
                     },6000)
                  }
                  else if (this.price!="" && isNaN(this.price)){
                     console.log("xx")
                     this.isError = !0
                     setTimeout(function(){
                         app.isError = !1
                     },6000)
                  }
                  else{
                      $("#negotiationWin").hcPopup({'width':'420'})
                  }
              }

            },
            doAgree:function(){
              _form = $("form")
              if (this.agree) {
            var datas = JSON.stringify( {
                  "_token": $("input[name='_token']").val(),
                  "is_feeback": this.agree,
                })
              } else {
            var datas = JSON.stringify( {
                  "_token": $("input[name='_token']").val(),
                  "is_feeback": this.agree,
                  "out_price": this.price,
                  "seller_data": this.negotiationTime.replace('年','-').replace('月','-').replace('日',''),
                  "seller_day":this.ampm_id
                })
              }
              $.ajax({
                url: _form.attr("action"),
                type: 'post',
                dataType: 'json',
                contentType: "application/json;charset=utf-8",
                data: datas
              })
              .done(function() {
                window.location.reload()
              })
            },
            doSend:function(){
                this.subForm()
            },
            reload:function(){
                window.location.reload()
            },
            subForm:function(){
                var _form  = $("form")
                var _this  = this
                var options = {
                  type: 'post' ,
                  beforeSend: function(data) {

                  },
                  success: function(data) {
                       if (data.success == 0 ) {
                           $("#errorWin").hcPopup({'width':'420'})
                       } else if (data.success == 1 ){
                           $("#successWin").hcPopup({'width':'420'})
                       }
                       app.simpleCountDown(function(){
                          app.reload()
                       })
                   },
                   error:function(){
                        $("#errorWin").hcPopup({'width':'420'})
                        app.simpleCountDown(function(){
                          app.reload()
                        })
                   }
                 }
                 _form.ajaxForm(options).ajaxSubmit(options)
            },
            selectMonth:function(time){
                this.timeSelect = !0
                time.selected = !time.selected
            },
            pushLeft:function(){
                if (this.marginLeft == 0) return
                this.click --
                this.marginLeft =  -this.margin * this.click
            },
            pushRight:function(){
                if (this.timeList.length - 15 == this.click) return
                this.click ++
                this.marginLeft =  -this.margin * this.click
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
          agree:function(n,o){
             if (n==1) {
                this.negotiationTime= ""
                this.price = ""
                this.isError = !1
                $(".btn-dropdown-default").addClass('disabled').find(".dropdown-label span").text("")
             }
          }

        }

    })

    module.exports = {
        initTimeList:function(array){
           app.timeList = array
        },
        initStartEndTime:function(startTime,endTime){
            app.startTime = startTime
            app.endTime = endTime
        }
    }
})
