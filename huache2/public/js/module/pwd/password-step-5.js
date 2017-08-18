
define(function (require, exports, module) {

    var app = new Vue({
        el: '#vue',
        data: {   
            countDownNum:5, 
            countDownObj:{} ,
            isend:!0
        },
        mounted:function(){
           this.simpleCountDown()
        }
        ,
        methods:{
            simpleCountDown:function(){
                var _time = setInterval(function () {
                    if (app.countDownNum <=0) { 
                        app.isend = !1
                        app.toLogin()
                    }
                    app.countDownNum--
                },1000)
                this.countDownObj = _time
            },
            toLogin:function(){
                $(".login-link")[0].click()
            },
        },
        watch:{
            'countDownNum':function(n,o){
                if (n < 0) {
                  clearInterval(app.countDownObj)
                  app.countDownNum = 5
                } 
            }
        }
    })
  
    module.exports = {
        
    }
});