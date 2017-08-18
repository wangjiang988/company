define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/vue.common.mixin")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {

        },
        mounted:function(){

        }
        ,
        methods:{

        }
        ,
        watch:{

            '':function(){

            }

        }

    })

    module.exports = {


    }
})
