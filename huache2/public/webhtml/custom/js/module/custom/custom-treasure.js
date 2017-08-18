define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left")
    require("/webhtml/custom/js/module/custom/dropdown-components")
    require("/webhtml/common/js/vendor/hc.popup.jquery")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            priceStatusList:[
                {name:'全部' ,id:-1},
                {name:'>0' ,id:2},
                {name:'=0',id:1 }
            ],
            viewList:[],
            snList:[],
            isShowSnList:!1,
            sn:"",
            money:"",                  //冻结金额
            showDeailUrl:""           //查看详情url
        },
        mounted:function(){

        }
        ,
        methods:{
            getSnList:function(){
                $.ajax({
                   type: "get",
                   url: "/custom/getSnList",
                   data: {
                      sn:app.sn
                   },
                   dataType: "json",
                   success: function(data){
                        app.viewList = data.list
                        //list格式如error方法
                   },
                   error:function(){
                        app.snList = ["1235483","1257482"]
                   }
                })
            },
            displaySnList:function(){
                setTimeout(function(){
                    app.isShowSnList = !app.isShowSnList
                },300)
            },
            setSn:function(sn){
               this.sn = sn
            },
            getStatus:function(id,val){
                //冻结余额的选择项
                //console.log(id,val)
            },
            view:function(id,price){
                $("#viewWin").hcPopup({'width':'735'})
                $.ajax({
                   type: "get",
                   url: app.showDeailUrl,
                   data: {
                      id:id
                   },
                   dataType: "json",
                   beforeSend:function(){
                        app.money = price
                   },
                   success: function(data){
                        app.viewList = data.list;
                        app.sn       = data.orderSn;
                        app.money    = data.freezeTotalMoney;
                        //list格式如error方法
                   },
                   error:function(){
                        app.viewList = [
                        {status:"冻结",project:"歉意金",info:"2017-04-16 11:22:55",price:499,isNegative:!1},
                        {status:"冻结",project:"歉意金2",info:"2017-04-16 11:22:55",price:499,isNegative:!0}
                        ]
                   }
                })
            },
            closeView:function(id){
                $("#viewWin").fadeOut(300)
                setTimeout(function(){
                    app.money = 0
                },300)

            },
        },
        watch:{

        }

    })

    module.exports = {
        initUrl:function(showDeailUrl){
            app.showDeailUrl = showDeailUrl;
        }
    }
})