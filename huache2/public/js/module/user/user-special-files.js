define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    
    var app = new Vue({
        el: '.user-content',
        data: {
            delId:0
        },
        mounted:function(){
            
        }
        ,
        methods:{
            delFile:function(id){
                this.delId = id
                $("#delWin").hcPopup({'width':'400'})
            }, 
            doDelFile:function(event){
                var _this = this
                $.ajax({
                    type: "post",
                    url: "/user/delFile/", 
                    data: {
                        id:_this.delId,
                        _token:$("input[name='token']").val()
                    },
                    dataType: "json",
                    success: function(data){
                        _this.delId = 0
                        //刷新?
                        //window.location.reload()
                    },
                    error: function(data){
                        _this.delId = 0
                    }    
                })   
                $("#delWin").hide()
            }
            
        }
        ,
        watch:{
            
        }

    }) 
     
  
    module.exports = {
        
    }
})
