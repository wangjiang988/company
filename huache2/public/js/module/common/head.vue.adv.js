define(function (require, exports, module) { 

    
    var app = new Vue({
        el: '#navbar',
        data: {
            isShowLoginOut:!1 
        },
        mounted:function(){
            
             
        }
        ,
        methods:{
            
            loginOut:function(){
                this.isShowLoginOut = !0
            },
            doLoginOut:function(){
                this.cancelLoginOut()
                window.location.href = "/?loginout=!0"
            },
            cancelLoginOut:function(){
                this.isShowLoginOut = !1
            } 
        }
        ,
        watch:{
            
            'isShowLoginOut':function(){
                 
            }  
  
            
        }

    }) 
     
  
    module.exports = {
        
        init : function(obj){
            
        } 
    }
});