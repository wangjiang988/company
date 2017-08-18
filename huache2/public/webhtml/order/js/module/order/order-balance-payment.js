define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/vue.common.mixin") 
    require("/webhtml/common/js/vendor/time.jquery")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/custom/js/module/custom/dropdown-components")
    require("/webhtml/user/js/module/user/user-code-count-down-component")
    
    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
           countDownNum:5, 
           countDownObj:{},
           successStatus:-1,
           isSelectVouchers:!1,
           selectTxt:"",
           vouchersObj:{},
           vouchersList:[],
           code:"",
           isCodeError:!1,
           voucher_id:0,
        },
        mounted:function(){
            this.init()
        }
        ,
        methods:{
          init:function(){
             $(".tal .pull-left:eq(0)").text("\u77ed\u4fe1\u9a8c\u8bc1\u7801\uff1a").next().addClass("mt-5")
          },
          getCode:function(code){
             this.code = code
             setTimeout(function(){
              app.init()
             })
          },
          sureVouchers:function(){
             this.isSelectVouchers  = !0
             var _split             = this.selectTxt.split(" ")
             this.vouchersObj.price = _split[0]
             this.vouchersObj.type  = _split[1]
             this.vouchersObj.time  = _split[2]
          },
          getVouchers:function(obj){
             this.selectTxt         = obj.array[0] + " " +  obj.array[1] + " " +  obj.array[2]
             this.voucher_id = obj.id
             var _price = 499 - parseFloat(obj.array[0].replace("￥","").replace(/,/g,""))
             $("#dym-price").text(app.formatMoney(_price,2,"￥"))

          },
          vouchersPay:function(){
             if (this.code === "") {
                 app.isCodeError = !0
                 return
             }
              $.ajax({
                   type: "get",
                   url:"/member/checkSms/",
                   data: {
                      phone:app.$refs.phonecode.phone,
                      template_code:app.$refs.phonecode.sendtype,
                      code:app.code
                   },
                   dataType: "json",
                   beforeSend: function(data){ 
                       app.isCodeError = !1
                   },
                   success: function(data){
                       //0:验证码错误  1：验证成功
                       if (data.success == 0)
                          app.isCodeError = !0
                       else if (data.success == 1)
                          $("#payEarnest").submit()
                   }
              })   
              
          },
         
          chanceVouchers:function(){
              $("#vouchersWin").hcPopup({'width':'450'})
          }
        }
        ,
        watch:{
            
            '':function(){
                 
            }  
            
        }

    })  
  
    module.exports = {
        
        initVouchersList:function(array) {
            app.vouchersList = array
            //console.log( app.vouchersList)
        },
        initVouchersObj:function(obj) {
            app.vouchersObj = obj
            app.voucher_id = obj.id
            app.$refs.vouchers.def = app.vouchersList.length == 0 ? "" : app.vouchersList[0].name //obj.price + " 券编码尾号：" + obj.sn
            //console.log(obj)
        },
        isVoucher:function(voucher){
            if (voucher) {
               var _price = 499 - parseFloat(app.vouchersObj.price.replace("￥","").replace(/,/g,""))
               $("#dym-price").text(app.formatMoney(_price,2,"￥"))
            }
        }
    }
})


