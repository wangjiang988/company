define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/vue.common.mixin") 
    
    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
           countDownNum:5, 
           countDownObj:{},
        },
        mounted:function(){
            this.simpleCountDown()
        }
        ,
        methods:{ 
          simpleCountDown:function(call){
              var _time = setInterval(function () {
                  if (app.countDownNum <=0) { 
                     clearInterval(app.countDownObj)
                     window.location.href = $("#redirect-url").attr("href")
                  }else
                    app.countDownNum--
              },1000)
              this.countDownObj = _time
          }
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


