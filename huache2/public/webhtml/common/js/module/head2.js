    var app2= new Vue({
        el: '.head-wrapper',
        data: {
            isShowLoginOut:!1
        },
        mounted:function(){
            
        }
        ,
        methods:{
            loginOutConfirm:function(){
                this.isShowLoginOut = !this.isShowLoginOut
                return false
            },
            sureLoginOut:function(){
                this.isShowLoginOut = !1
                window.location.href = "/dealer/loginout/"
                return false
            },
            canceLoginOut:function(){
                this.isShowLoginOut = !1
                return false
            }
        }

    }) 
     
