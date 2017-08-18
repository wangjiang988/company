define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")  
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/common/js/vendor/jquery.form") 
    require("/webhtml/custom/js/module/custom/dropdown-components")
    

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
           
            ampmrevelist:["上午/下午","上午","下午"],
            dayArray:["日","一","二","三","四","五","六"],
            timeList:[],
            timeSelect:!0, 
            margin:60,
            click:0,
            marginLeft:0,
            
        },
        computed: {
          
        },
        mounted:function(){
          
        }
        ,
        methods:{
            pushLeft:function(){
                if (this.marginLeft == 0) return
                this.click --
                this.marginLeft =  -this.margin * this.click
            },
            pushRight:function(){
                if (this.timeList.length - 15 == this.click) return
                this.click ++
                this.marginLeft =  -this.margin * this.click
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
        }
    }
})
