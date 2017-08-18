define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin");
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/vendor/hc.popup.jquery");

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {


        },
        mounted:function(){

        }
        ,
        methods:{
            agree:function(){
                $("#tipWin").hcPopup({'width':'450'});
            },
            doAgree:function(){
                 $("#tipWin").hide()
                 $("#manger").submit()
            },


        },
        watch:{

        }

    })

    module.exports = {

    }
})
