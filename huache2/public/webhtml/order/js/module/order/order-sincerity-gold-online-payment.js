define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/vue.common.mixin") 
    require("/webhtml/common/js/vendor/time.jquery")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/custom/js/module/custom/dropdown-components")
    require("/webhtml/user/js/module/user/user-code-count-down-component")
    require("/webhtml/common/js/vendor/jquery.form")
    
    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
           successStatus:-1,
           payMethod:0,
           price:null,
           errorStatus:-1,
           maxPrice:null,
           minPrice:null
        },
        mounted:function(){
            
        }
        ,
        methods:{
          subPayForm: function () {
              if (!this.price) {
                 this.errorStatus = 0
              }
              else if (isNaN(this.price)) {
                 this.errorStatus = 2
              }
              else if (this.price > this.maxPrice) {
                 this.errorStatus = 3
              }
              else if (this.price < this.minPrice) {
                  this.errorStatus = 4
              }
              else{
                $("#payform").submit()
              } 
          },
          initErrorStatus:function(){
             this.errorStatus = -1
          },
          selectPayMethod: function (id,event) {
              var _this = event.target.tagName == "LI" ? $(event.target) : $(event.target).parent()
              _this.find("span").addClass("selectpay").end().siblings('li').find("span").removeClass('selectpay')
              $("input[name='paymethod']").val(_this.index())
              $(".showerror").addClass('hide')
              this.payMethod = id
          }
        }
        ,
        watch:{
            
            '':function(){
                 
            }  
            
        }

    })  
  
    module.exports = {
       
        initPrice :function(maxprice,minprice){
            app.maxPrice = maxprice,
            app.minPrice = minprice
        },
         
    }
})


