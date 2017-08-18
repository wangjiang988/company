define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left")
    require("/webhtml/custom/js/module/custom/dropdown-components")

    var app = new Vue({
        el: '.user-content',
        data: {
            timeList:[{id:-1,name:"全部"},{id:1,name:"近三个月订单"},{id:2,name:"三个月之前"}],
            brandList:["全部"],
            brand:'',
        },
        mounted:function(){

        }
        ,
        methods:{
            //选择时间
            getTime:function(id,val){
                //console.log(id,val)
            },
            //选择品牌
            getBrand:function(val){
                if( val == '全部') {
                    this.brand = ''
                } else {
                    this.brand = val
                }

            },
        }
        ,
        watch:{

            '':function(){

            }

        }

    })


    module.exports = {
        initBrandList:function(array){
            app.brandList = app.brandList.concat(array)
        },
        initDefValue:function(time,brand){
            app.$refs.time.valId = time
            app.$refs.brand.valId = brand
            app.brand = brand
        }



    }
})
