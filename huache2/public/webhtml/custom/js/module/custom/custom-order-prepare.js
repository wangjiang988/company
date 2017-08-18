define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/vendor/time.jquery")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/common/js/vendor/jquery.form")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            goodsList:[],
            isLoading:!1,
            checkboxModel:[],
            checked:!1
        },
        mounted:function(){
            //this.getGoodsList(dealer,brand)
        }
        ,
        methods:{
            send:function(){
                $("#send").hcPopup({'width':'450'})
            },
            doSend:function(event){
              $("form[name='wait-sub-form']").submit()
                $("#send").hide()

            },
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
            checkedAll: function() {
                if (!this.checked) {
                  app.checkboxModel = []
                }else{
                  app.checkboxModel = []
                  app.goodsList.forEach(function(item) {
                      app.checkboxModel.push(item.xzj_daili_id)
                  })
                }
              },
            loadGoods:function(){
                $("#export").hcPopup({'width':'450'})
            },
            doLoadGoods:function(dealer,brand){
                $("#export").hide()
                this.getGoodsList(dealer,brand)
            },
            getGoodsList:function(dealer,brand){
                $.ajax({
                    url: "/dealer/options/"+dealer+"/brand/"+brand,
                    type: "get",
                    data: {},
                    dataType: "json",
                    beforeSend: function() {
                        setTimeout(function(){
                            app.isLoading = !0
                        })
                    },
                    success: function(data) {
                        //data格式参展error方法中的goodsList
                        //开发环境务必把error方法删除
                        app.goodsList = data
                        app.isLoading = !1
                        //默认全选
                        //checkboxModel中push所有数据ID
                        //app.checked = !0
                        data.forEach(function(item) {
                            app.checkboxModel.push(item.xzj_daili_id)
                        })
                    },
                    complete: function() {
                        //document.getElementById('checkAll').click()
                        setTimeout(function(){
                            $("input[name^='xzj_list']").each(function(){
                                $(this).val($(this).attr("data-id")).prop("checked",true)
                            })
                         },300)

                    },
                })
            }
        }
        ,
        watch:{

            'checkboxModel': {
                    handler: function (val, oldVal) {
                      if (this.checkboxModel.length === this.goodsList.length) {
                        this.checked = !0
                      }else{
                        this.checked = !1
                      }
                    },
                    deep: !0
                }
            }

    })

    module.exports = {
        init:function(startTime,endTime,call){
           app.init(startTime,endTime,call)
        },
        getGoodsList:function(dealer,brand){
          app.getGoodsList(dealer,brand)
        }

    }
})
