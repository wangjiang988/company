define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")  
    require("/webhtml/custom/js/module/custom/dropdown-components") 

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            dropList:[{id:1,name:'全部'},{id:2,name:'123456789'},{id:3,name:'123456876'}]
        },
        mounted:function(){
             
        }
        ,
        methods:{
            getOrderSn:function(id,val){
                console.log(id,val)
            } 
        },
        watch:{
           
        }

    })  
  
    module.exports = {
        initDropList:function(array){
             app.dropList = array
        }
    }
})
