define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left")
    require("/webhtml/common/js/vendor/time.jquery")
    
    var app = new Vue({
        el: '.user-content',
        data: {
            
        },
        mounted:function(){
            
        }
        ,
        methods:{
            init: function (id,startTime,endTime,call) {
                $("#"+id).CountDown({
                      startTime:startTime,
                      endTime :endTime,
                      timekeeping:'countdown',
                      callback:function(){
                          if (call) {call()}
                      }
                }) 
            }  
        }
        ,
        watch:{
            
            '':function(){
                 
            }  
            
        }

    }) 
     
  
    module.exports = {
        
        init:function(id,startTime,endTime,call){
           app.init(id,startTime,endTime,call)
        }
    }
})
