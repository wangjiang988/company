define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left") 

    var app = new Vue({
        el: '.user-content',
        data: {
            simpleCountDown:5
        },
        setUrl:"",
        mounted:function(){
            var app = this
            var _time = setInterval(function() { 
                if (app.simpleCountDown == 0) { 
                    window.location.href = app.setUrl;
                    clearInterval(_time)
                } else { 
                    app.simpleCountDown-- 
                }
            },1000)
        }
        ,
        methods:{
            
        }

    }) 
     
  
    module.exports = {
       init:function(_setUrl){
           app.setUrl = _setUrl;
       }
    }
})
