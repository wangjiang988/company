define(function (require, exports, module) {
    require("/webhtml/common/js/module/head")
    var app = new Vue({
        el: '.container',
        data: {
            
        },
        mounted:function(){
            var _timecount = 5
            var _timetig = setInterval(function(){
                if (_timecount <= 0) {
                    window.location.href = "/"
                    return
                }
                _timecount--
                document.getElementById('timeshow').innerHTML = _timecount
            },1000)
           
        }
        ,
        methods:{
            
        }
        ,
        watch:{
          
        }

    })

    module.exports = {
       
    }
})
