define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    //开发环境务必删除AJAX的error方法
    require("/webhtml/common/js/module/vue.common.mixin")  
    require("./user-area-select-component")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            province:"" ,
            city:"",
            bankInfo:"",
            isBankInfo:!1,
            bankNum:"",
            bankNumView:" ",
            isBankNum:!1,
            isBankError:!1,
            isError:!1
        },
        mounted:function(){
            
        }
        ,
        methods:{
            send: function () {
                var _flag = !0
                if (this.province === "" || this.city  === "") {
                    $(".area-drop-btn").addClass('error-bg')
                    this.isError = !0
                    _flag = !1
                }
                if (this.bankInfo === "") {
                    this.isBankInfo = !0
                    this.isError = !0
                    _flag = !1
                }
                if (_flag) 
                    $("form").submit()
            },
            checkBankInfo: function () {
                if (this.bankInfo === "") this.isBankInfo = !0
            },
            initBankInfo: function () {
                this.isBankInfo = !1
                this.isError  = !1
            }, 
            getArea: function (province,city) {
                //获取省份城市 
                this.province = province
                this.city     = city
                $(".area-drop-btn").removeClass('error-bg')
                this.isError  = !1
            },
        },
        watch:{
           
        }

    })  
  
    module.exports = {
         
    }
})
