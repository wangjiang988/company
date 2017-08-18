define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/vendor/time.jquery")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/common/js/vendor/jquery.form")
    require("/webhtml/user/js/module/user/user-code-count-down-component")
    require("/webhtml/custom/js/module/custom/dropdown-components")
    require("/webhtml/custom/js/module/custom/dropdown-date-components")
    var datec = require("./order-datepicker-components")


    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            colorList:[{"month":"4","day":"30","select":"3","week":"0"},{"month":"5","day":"7","select":"2","week":"0"},{"month":5,"day":13,"week":6},{"month":5,"day":14,"week":0},{"month":5,"day":15,"week":1},{"month":5,"day":16,"week":2},{"month":5,"day":17,"week":3},{"month":5,"day":18,"week":4},{"month":5,"day":19,"week":5}],
            ampmlist:[{name:"上午",id:2},{name:"下午",id:3},{name:"上午/下午",id:1}],
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
            type: '',
            seltime:{},
            ownerName:"",
            carUserName:"",
            phone:"",
            isPhone:!0,
            countDownNum:5,
            countDownObj:{},
            isShow:!1,
            ampm_id:'',
            tiche:-1,
            vehicleUseList:[{name:"非营业个人客车（私家车）",id:1},{name:"非营业企业客车（公司自用车)",id:2},{name:"其他",id:3}],

            step6:{
                individualModel:"",//非营业个人客车 选项
                isParentSelect:0,
                childVal:0,
                householdRegistration:"",
                foreign:"",
                child:[
                    {val:1,txt:'\u56fd\u5185\u5176\u4ed6\u975e\u9650\u724c\u57ce\u5e02\u6237\u7c4d\u5c45\u6c11'},
                    {
                        val:2,
                        txt:'\u56fd\u5185\u5176\u4ed6\u9650\u724c\u57ce\u5e02\u6237\u7c4d\u5c45\u6c11',
                        list:[
                            {id:0,txt:'\u5317\u4eac',isSelect:!1},
                            {id:0,txt:'\u4e0a\u6d77',isSelect:!1},
                            {id:0,txt:'\u5e7f\u5dde',isSelect:!1},
                            {id:0,txt:'\u5929\u6d25',isSelect:!1},
                            {id:0,txt:'\u676d\u5dde',isSelect:!1},
                            {id:0,txt:'\u8d35\u9633',isSelect:!1},
                            {id:0,txt:'\u6df1\u5733',isSelect:!1},
                            {id:0,txt:'\u82cf\u5dde',isSelect:!1}
                        ]
                    },
                    {val:3,txt:'\u4e2d\u56fd\u519b\u4eba'},
                    {
                        val:4,
                        txt:'\u975e\u4e2d\u56fd\u5927\u9646\u4eba\u58eb',
                        list:[
                            {id:1,txt:'\u5916\u7c4d\u4eba\u58eb',isSelect:!1},
                            {id:2,txt:'\u53f0\u80de',isSelect:!1},
                            {id:3,txt:'\u6e2f\u6fb3\u4eba\u58eb',isSelect:!1},
                            {id:4,txt:'\u6301\u7eff\u5361\u534e\u4fa8',isSelect:!1}
                        ]
                    }
                ]
            },
            vehicleUse:"1",
            vehicleUseTxt:"",


        },
        computed: {

        },
        mounted:function(){

        }
        ,
        methods:{
            loadUser:function(){
                this.carUserName = this.ownerName
                //this.phone = $("input[name='phone']").val()
            },
            getTime:function(el,selectDateTxt,outputDateTxt,y,m,d,type){
                this.clearTimeListSelect()
                this.negotiationTime = selectDateTxt
                this.type = type
                this.isShow = type == "black" ? !0 :!1
                this.seltime = {}
                $(document.getElementById(el)).parent().next().find(".disabled").removeClass('disabled')
            },
            selectHouseholdRegistration:function(index,id,val){
                this.step6.householdRegistration = val
                this.step6.childVal = 2
                this.step6.isParentSelect = 1
                $.each(this.step6.child[1].list,function(idx,it){
                    if(index === idx) it.isSelect = !0
                    else it.isSelect = !1
                })
                this.step6.foreign = ""
                this.clearSecondIdentity()
            },
            selectForeign:function(index,id,val){

                this.step6.foreign = val
                this.step6.childVal = 4
                this.step6.isParentSelect = 1
                $.each(this.step6.child[3].list,function(idx,it){
                    if(index === idx) it.isSelect = !0
                    else it.isSelect = !1
                })
                this.step6.householdRegistration = ""
                this.clearFirstIdentity()
            },
            selectParant:function(val){
                this.step6.isParentSelect = 1
                this.step6.isAgree = !1
                if (val == 0 || val == 3) {
                    this.step6.householdRegistration = ""
                    this.step6.foreign = ""
                    this.clearSecondIdentity()
                    this.clearFirstIdentity()
                }
                if (val == 1) {
                    this.step6.foreign = ""
                    this.clearSecondIdentity()
                }
                if (val == 4) {
                    this.step6.householdRegistration = ""
                    this.clearFirstIdentity()
                }

            },
            setDefIdentity:function(index){
               this.step6.isParentSelect = 2
               this.step6.childVal = 0
               this.clearFirstIdentity()
               this.clearSecondIdentity()
            },
            clearFirstIdentity:function(){
                $.each(this.step6.child[1].list,function(idx,it){
                    it.isSelect = !1
                })
            },
            clearSecondIdentity:function(){
                $.each(this.step6.child[3].list,function(idx,it){
                    it.isSelect = !1
                })
            },
            getVehicleUse:function(id,val){
                //console.log(id,val)
                this.vehicleUse = id
                //清空已选择项
                $(".cur-select").removeClass('cur-select')
                this.setDefIdentity()
                $("input[type='radio']").removeAttr("checked").prop("checked",!1)
                this.step6.isParentSelect = 0
            },
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

            selectAmPm:function(id,val){
                this.ampm = val
                this.ampm_id = id
            },

            initPhone:function(){
                this.isPhone = !0
            },
            send:function(){
                /*console.log(this.seltime.year+':'+this.seltime.month+'-'+this.seltime.day,this.seltime.select)*/
                this.isTimeEmpty = !1
                this.isUseEmpty  = !1
                this.isEmpty     = !1
                this.isPhone     = !0

                var _flag        = !1
                this.timeList.forEach(function(item){
                    if (item.selected) _flag = !0
                })
                if (!_flag && (this.negotiationTime == "" || this.ampm == ""))
                   this.isTimeEmpty = !0
                //console.log(this.vehicleUse)
                if (this.vehicleUse == "1") {
                    var _tbl = $(".tbl-category")
                    var _radio = _tbl.find("input[type='radio']")
                    var _selectradio = null
                    $.each(_radio,function(index,item){
                        if ($(item).prop("checked")) _selectradio = $(item)
                    })
                    //console.log(_selectradio)
                    if (_selectradio) {
                        var _dataid = _selectradio.attr("data-id")
                        this.vehicleUseTxt = _dataid
                        if (_dataid == "2") {
                            this.isUseEmpty = !0
                        }else if(_dataid == "4" || _dataid == "6"){
                            var _select = _selectradio.next().next().find(".cur-select")
                            //console.log(_select)
                            if (_select.length != 0) {
                                 var _txt = _select.text()
                                 this.vehicleUseTxt = _dataid + "|" + _txt
                            }else{
                                this.isUseEmpty = !0
                            }

                        }
                    }
                    else{
                        this.isUseEmpty = !0
                    }
                }
                else if (this.vehicleUse == "2"){
                    var _radio = $(".div-cate").find("input[type='radio']")
                    var _radioFlag = !1
                    $.each(_radio,function(index,item){
                        if ($(item).prop("checked")) {
                            _radioFlag = !0
                            app.vehicleUseTxt = $(item).next().text()
                            return false
                        }

                    })
                    this.isUseEmpty =  !_radioFlag
                }
                if (this.ownerName == "" || this.carUserName == "" || this.phone == ""){
                    this.isUseEmpty = !0
                }
                if (!this.isPhoneNo(this.phone) && !this.isUseEmpty) {
                    this.isPhone = !1
                }

                if (this.isTimeEmpty || this.isUseEmpty) {
                    this.isEmpty = !0
                    setTimeout(function(){
                       app.isEmpty = !1
                    },6000)
                }
                else if(!this.isPhone){
                    //
                }
                else{

                   $("#tipWin").hcPopup({'width':'420'})
                }
                //console.log(_flag,this.negotiationTime == "", this.ampm == "",this.isTimeEmpty , this.isUseEmpty,this.isEmpty )
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
                var type = (this.type == 'black') ? 1 : 0
                if (this.vehicleUse == 2) { _this.vehicleUseTxt = $("input[name='yt']:checked").val()}
                $.ajax({
                    url: _form.attr("action"),
                    type: 'post',
                    dataType: 'json',
                    contentType:"application/json;charset=utf-8",
                    data: JSON.stringify( {
                        extract_phone: _this.phone,
                        extract_name: _this.carUserName,
                        owner_name: _this.ownerName,
                        member_data: _this.negotiationTime.replace('年','-').replace('月','-').replace('日',''),
                        member_day:_this.ampm_id,
                        default_data: _this.seltime.year ? (_this.seltime.year+'-'+_this.seltime.month+'-'+_this.seltime.day) : "",
                        default_day:_this.seltime.select,
                        is_timeout:type,
                        car_purpose: _this.vehicleUse - 1,
                        identity_type:_this.vehicleUseTxt,
                        _token:$("input[name='_token']").val()
                    }),
                })
                .done(function(data) {
                   if (data.code == 1 ) {
                           $("#successWin").hcPopup({'width':'420'})
                       }
                       app.simpleCountDown(function(){
                          app.reload()
                       })
                })

             //_form.ajaxForm(options).ajaxSubmit(options)
            },
            selectMonth:function(time){
                this.timeSelect = !0
                this.clearTimeListSelect()
                this.seltime = time
                time.selected = !time.selected
                this.negotiationTime = ""
                this.$refs.rili.def = ""
                //this.$refs.datepicker.colorlist = this.colorList
            },
            clearTimeListSelect:function(){
                this.timeList.forEach(function(item){
                   item.selected = !1
                })
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


        }

    })

    module.exports = {
        initTimeList:function(array){
           app.timeList = array
        },
        initStartEndTime:function(startTime,endTime){
            app.startTime = startTime
            app.endTime = endTime
        },
        initColorList:function(array){
           app.colorList = array
        },
    }
})
