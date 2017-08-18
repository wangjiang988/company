define(function (require,exports,module) {
 
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/vue.common.mixin") 
    require("/webhtml/common/js/vendor/time.jquery")
    
    var app = new Vue({
        el: '.vue-content',
        mixins: [mixin],
        data: {
           
        },
        mounted:function(){
            this.toolTip()
        },
        methods:{
            init: function (startTime,endTime,call) {
                $(".countdown").CountDown({
                    startTime:startTime,
                    endTime :endTime,
                    timekeeping:'countdown',
                    callback:function(){
                        if (call) {
                          call() 
                          $(".countdown").hide()
                          $(".timeout-text").removeClass("hide")
                          $(".timeout").removeClass("hide").CountDown({
                              startTime:endTime,
                              endTime :endTime,
                              timekeeping:'timeout',
                              callback:function(){
                                  //if (call) {call()} 
                              }
                          }) 
                        }
                }
            }) 
        },
        toolTip:function(){
           $(".tip").hover(function(){
              $(this).parents(".psr-wapper").find(".tooltip").addClass("in")
           },function(){
              $(this).parents(".psr-wapper").find(".tooltip").removeClass("in")
           })
        },
        sub:function(){
          $("#pay_one").submit();
        }  
        }
        ,
        watch:{
            
            '':function(){
                 
            }  
            
        }

    })  
  
    module.exports = {
        init:function(startTime,endTime,call){
            app.init(startTime,endTime,call)
        } 
         
    }
})





 
        
 