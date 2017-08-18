define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
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
            bodyColorList:[],
            interiorColorList:[],
            yearList:[],
            monthList:[],
            kmList:[],
            year:null,
            month:null,
            isLoading:!1,
            checkboxModel:[],
            checked:!0,
            agree:!0,
            agreeList:["1"],
            isSend:!1,
            modifyOrStop:!0,
            noChange:!1,
            isClick:!1,
            code:"",
            error:!1,
            timeList:[],
            timeSelect:!0,
            margin:60,
            click:0,
            marginLeft:0,
            ckAll:!1,
            ckWork:!1,
            workList:[],
            selectDated:"",
            BodyColor:"",
            Interiorcolor:"",
            selectkm:"",
            Year:"",
            Month:""
        },
        mounted:function(){
            this.setTotalPrice()
        }
        ,
        methods:{

            init: function (startTime,endTime,call) {
                $(".countdown").CountDown({
                      startTime:startTime,
                      endTime :endTime,
                      timekeeping:'countdown',
                      callback:function(){
                          if (call) {call()}
                      }
                })
            },
            selectInterior:function(id,val){
              this.BodyColor = val
              //console.log(id,val)
            },
            selectBodyColor:function(id,val){
              this.Interiorcolor = val
               // console.log(id,val)
            },
            selectYear:function(val){
              this.monthList = []
              if (val.replace("\u5e74","") == this.year) {
                  for (var i = 1; i <= this.month; i++) {
                      this.monthList.push(i+"\u6708")
                  }
                  //dropdown是组件年月调用的是同一个组件..
                  //没办法自己调用自己 只能用jq延时点击
                  //
                  setTimeout(function(){
                      $("#month").find(".dropdown-menu li:last").click()
                  })
              }else{
                for (var i = 1; i <= 12; i++) {
                    this.monthList.push(i+"\u6708")
                }
              }
              this.Year = val
            },
            selectMonth2:function(val){
              this.Month = val

            },
            selectKm:function(val){
                this.selectkm =val
            },
            selectDate:function(val){
                 this.selectDated = val
                //console.log(val)
            },
            setValue:function(obj){
                var _this = $(obj)
                var _price = parseInt( _this.attr("data-price") )
                var _count = parseInt(_this.val())
                var _money = _price * _count
                _this.parents("td").next().text(this.formatMoney(_money,2,"￥"))
                this.setTotalPrice()
            }
            ,
            setTotalPrice:function(){
                var _total = 0
                var _tbl = $(".bgtbl:eq(0)")
                $.each(_tbl.find("tr").slice(1),function(index,item){
                    var _td = $(item).find("td:last").prev()
                    var _td_input = _td.find("input[readonly='readonly']")
                    _total += parseInt( _td_input.attr("data-price") ) * parseInt(_td_input.val())
                })
                _tbl.next().find("input[name='price']").val(_total)//后台取值对象
                _tbl.next().find("label").text(this.formatMoney(_total,2,"￥"))
            }
            ,
            prev:function(event){
                var _this = event.target
                var _input = $(_this).next()
                var _val = parseInt(_input.val())
                var _min = 0
                _input.val(_val == _min ? _min : (_val - 1))
                this.setValue(_input)
            }
            ,
            next:function(event,max){
                var _max = max || 1 //可在页面传参 注：此件数选择不超过单车可装件数，也不超过可供件数。
                var _this = event.target
                var _input = $(_this).prev()
                var _val = parseInt(_input.val())
                _input.val(_val == _max ? _max : (_val + 1))
                this.setValue(_input)
            }
            ,
            prev2:function(event){
                var _this = event.target
                var _input = $(_this).next()
                var _val = parseInt(_input.val())
                var _min = 0
                _input.val(_val == _min ? _min : (_val - 1))
            }
            ,
            next2:function(event,max){
                var _max = max || 0 //可在页面传参 注：此件数选择不超过单车可装件数，也不超过可供件数。
                var _this = event.target
                var _input = $(_this).prev()
                var _val = parseInt(_input.val())
                _input.val(_val == _max ? _max : (_val + 1))
            }
            ,
            modify:function(){
               $(".modifydiv").removeClass('hide')
               $("#btn-control-wapper").hide()
            }
            ,
            noModify:function(){
               $(".modifydiv").addClass('hide')
               $("#btn-control-wapper").show()
            }
            ,
            sureModify:function(){
                if (this.modifyOrStop == true) {
                    this.isSend = !0
                    if (!this.agree) {
                        return
                    }
                    this.noChange = !0
                    $.each($("#user-form-step-1 .dropdown-label"),function(){
                        if ($(this).find("span").hasClass('juhuang') || $(this).hasClass('juhuang') ) {
                            app.noChange = !1
                        }
                    })
                    $.each($("#user-form-step-1 .bgtbl"),function(idx,item){
                        $.each($(item).find("tr"),function(){
                            var _input = $(this).find("input[type='text']")
                            var _def = _input.attr("def-value")
                            var _select = _input.val()
                            if (_def != _select ) {
                                app.noChange = !1
                            }
                        })
                    })
                    if (!this.noChange)  $("#modifyWin").hcPopup({'width':'420'})
                }else{
                    $("#stopWin").hcPopup({'width':'420'})
                }
            }
            ,
            doModifySure:function(){
                $("#sendCodeWin").hcPopup({'width':'420'})
                $("#modifyWin").hide()
                $("#stopWin").hide()
                $(".btn-send-code").click().prev().val("")
            },
            doModify:function(){
                if (this.code === "") {
                    this.error = !0
                }
                else if (!this.checkNum(this.code)) {
                    this.error = !0
                }else{
                 //   $("#user-form-step-1").submit();
                  var _form  = $("#user-form-step-1")
                  var _this  = this
                  var options = {
                    type: 'post' ,
                    beforeSend: function(data) {

                    },
                    success: function(data) {
                         if (data.error_code == 200 ) {
                            location.reload();
                         } else {
                            _this.error = !0
                         }

                         },
                     error:function(){
                          _this.error = !0
                     }
                   }
                   _form.ajaxForm(options).ajaxSubmit(options)
              }
            },
            send:function(){
                var _flag = !1
                this.timeList.forEach(function(item){
                    if (item.monthSelect) _flag = !0
                })
                if(_flag)
                    $("#sendWin").hcPopup({'width':'420'})
                 else this.timeSelect = !1

            },
            doSend:function(){
                var _selectTimeList = []
                this.timeList.forEach(function(item){
                    if (item.amSelect && item.pmSelect)  _selectTimeList.push({month:item.month,day:item.day,select:1,week:item.week,year:item.year})
                    else if (item.amSelect)  _selectTimeList.push({month:item.month,day:item.day,select:2,week:item.week,year:item.year})
                    else if (item.pmSelect)  _selectTimeList.push({month:item.month,day:item.day,select:3,week:item.week,year:item.year})
                })
                $.ajax({
                   type: "POST",
                   url: "/dealer/order/jiaoche",
                   data: {
                      _token:$("input[name='_token']").val(),
                      id:$("input[name='order_id']").val(),
                      date:_selectTimeList
                   },
                   dataType: "json",
                   success: function(data){
                       location.reload()
                   },
                   error:function(){

                   }
                })
            },
            stopOrder:function(){
                this.doModifySure()
            },
            doStopOrder:function(){
                if (this.code === "") {
                    this.error = !0
                }
                else if (!this.checkNum(this.code)) {
                    this.error = !0
                }else{
            //     $("#stopOrder").submit();
                    var _this  = this
                    $.ajax({
                       type: "POST",
                       url: Config.routes.stopurl,
                       data: {
                          _token:$("input[name='_token']").val(),
                          code:$("input[name='code']").val(),
                          id:$("#id").val()
                       },
                       dataType: "json",
                       success: function(data){
                           if (data.error_code == 200) {
                            location.reload();
                           } else {
                             _this.error = !0
                           }
                       },

                    //    error:function(){
                    //         _this.error = !0
                    //    }
                     })
                }
            },
            doSendCode:function(){
               if (this.modifyOrStop == true) this.doModify()
               else  this.doStopOrder()
            },
            sendEnd:function(){
               $("#sendCodeWin").hide()
            },
            getCode:function(code,isError){
              this.code = code
              this.error = !isError
            },
            checkNum:function(num){
                if (num.length < 6 || isNaN(num)) {
                    return !1
                }
                return !0
            },
            selectMonth:function(time){
                this.timeSelect = !0
                if (time.disabled) return
                time.monthSelect = !time.monthSelect
                if (!time.monthSelect) {
                    time.amSelect = !1
                    time.pmSelect = !1
                }
            },
            selectAm:function(time){
                this.timeSelect = !0
                if (time.disabled) return
                time.amSelect = !time.amSelect
                if (!time.pmSelect && !time.amSelect) time.monthSelect = !1
                else time.monthSelect = !0
            }
            ,
            selectPm:function(time){
                this.timeSelect = !0
                if (time.disabled) return
                time.pmSelect = !time.pmSelect
                if (!time.pmSelect && !time.amSelect) time.monthSelect = !1
                else time.monthSelect = !0
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
            checkAll:function(event){
                this.ckWork = !1
                this.selectTime($(event.target).prop("checked"))
            },
            checkAllInvert:function(){
                this.selectTime(!1)
            },
            selectTime:function(flag){
                this.timeList.forEach(function(item){
                    if (!item.disabled) {
                        item.monthSelect = flag
                        item.pmSelect = flag
                        item.amSelect = flag
                    }
                })
            },
            checkWorkTime:function(event){
                this.ckAll = !1
                this.checkAllInvert()
                var _flag = $(event.target).prop("checked")
                this.timeList.forEach(function(item){
                    if (!item.disabled) {
                        app.workList.forEach(function(time){
                            var _month = time.month
                            var _day = time.day
                            if (item.month == _month && item.day == _day) {
                                item.monthSelect = _flag
                                item.pmSelect = _flag
                                item.amSelect = _flag
                            }
                        })

                    }
                })
            }
        },
        watch:{

            'agreeList.length':function(n,o){
                if (n == 0) this.agree = !1
                else this.agree = !0
            },
            'modifyOrStop':function(n,o){

            }
        }

    })

    module.exports = {
        init:function(startTime,endTime,call){
           app.init(startTime,endTime,call)
        },
        initBodyColor:function(array){
           app.bodyColorList = array
        },
        initInteriorColor:function(array){
           app.interiorColorList = array
        },
        initYearMonth:function(year,month){
           app.year     = year
           app.month    = month
           app.yearList = []
           for (var i = 2010; i <= year; i++) {
              app.yearList.push(i+"\u5e74")
           }
           app.yearList.reverse()
           app.monthList = []
           for (var i = 1; i <= month; i++) {
              app.monthList.push(i+"\u6708")
           }
        },
        initKm:function(km){
           app.kmList = []
           for (var i = km; i <= km + 100; i++) {
              app.kmList.push(i)
           }
        },
        initTimeList:function(array){
           app.timeList = array
        },
        setWorkList:function(array){
           app.workList = array
        }
    }
})
