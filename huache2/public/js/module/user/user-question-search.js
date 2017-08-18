define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    //开发环境中请把每个$.ajax或ajaxForm中的error方法删除
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left") 
    //require("/webhtml/common/js/vendor/jquery.form") 
   
    var app = new Vue({
        el: '.content',
        data: {
            searchTitle:"" 
        },
        mounted:function(){
           
        }
        ,
        methods:{
            search:function(){
                if (this.searchTitle !="") {
                    $("form[name='qustion-form']").submit()
                }
            }
        },
        watch:{
            
        }

    }) 
     
  
    module.exports = {
        
    }
})
