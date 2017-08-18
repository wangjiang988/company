define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/common/js/vendor/hc.popup.jquery")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            isQcode:!1,
            insurance:0,
            transportRoyalities:0,
            vehiclePurchaseTax:0,
            registration:0,
            commercialInsurance:0,
            temporaryLicenceFee:0,
            otherFees:0,
            price:0,
            fixedPrice:0,
            totalPrice:0

        },
        mounted:function(){
           setTimeout(function(){
              app.subPrice()
           })
        },
        methods:{
            collect:function(event){
                $("#collectWin").hcPopup({'width':'420'})
            },
            doCollect:function(event){
                $("#collectWin").hide()
                window.location.href = "/order/nextstep"
            },
            savePrice:function(event){
                $.ajax({
                  url: '/order/savePrice/',
                  type: 'POST',
                  dataType: 'json',
                  data: {
                      insurance : app.replaceMoney(app.insurance),
                      transportRoyalities : app.replaceMoney(app.transportRoyalities),
                      vehiclePurchaseTax : app.replaceMoney(app.vehiclePurchaseTax),
                      registration : app.replaceMoney(app.registration),
                      commercialInsurance : app.replaceMoney(app.commercialInsurance),
                      temporaryLicenceFee : app.replaceMoney(app.temporaryLicenceFee),
                      otherFees : app.replaceMoney(app.otherFees),
                      token:$("input[name='token']").val()
                  },
                })
                .done(function() {
                  console.log("success");
                })
                .fail(function() {
                  console.log("error");
                })


            },
            initPrice:function(event){
                event.target.value = app.replaceMoney(event.target.value)
            },
            subPrice:function(){
                this.price =
                            parseFloat(this.replaceMoney(this.insurance)) +
                            parseFloat(this.replaceMoney(this.transportRoyalities)) +
                            parseFloat(this.replaceMoney(this.vehiclePurchaseTax) )+
                            parseFloat(this.replaceMoney(this.registration))+
                            parseFloat(this.replaceMoney(this.commercialInsurance)) +
                            parseFloat(this.replaceMoney(this.temporaryLicenceFee)) +
                            parseFloat(this.replaceMoney(this.otherFees))
                this.price = this.formatMoney(this.price,2,"￥")
                this.totalPrice =
                this.formatMoney(
                (
                  parseFloat(this.replaceMoney(this.price))  +
                  parseFloat(this.replaceMoney(this.fixedPrice))
                ),2,"￥")

            },
            setPrice:function(event){

              setTimeout(function(){
                event.target.value = app.formatMoney(app.replaceMoney(event.target.value),2,"")
             },200)

            },
            showQcode:function(){
                this.isQcode = !this.isQcode
            },
            agree:function(){
                $("#tipWin").hcPopup({'width':'420'})
            },
            doSend:function(url){
              $.post(url, {_token: $("input[name='_token']").val()}, function(data) {
                $("#collectWin").hide()
                 window.location.reload()
              });
            },
            reload:function(){
                window.location.reload()
            },

        },
        watch:{


        }

    })

    module.exports = {
         initFixedPrice:function(array){
            var _price = 0.00
            array.forEach(function(item){
               _price += item
            })
            app.fixedPrice = app.formatMoney(_price,2,"￥")
         },
         initPrice: function(insurance,
            transportRoyalities,
            vehiclePurchaseTax,
            registration,
            commercialInsurance,
            temporaryLicenceFee,
            otherFees) {
                app.insurance = app.formatMoney(insurance,2,"")
                app.transportRoyalities = app.formatMoney(transportRoyalities,2,"")
                app.vehiclePurchaseTax = app.formatMoney(vehiclePurchaseTax,2,"")
                app.registration = app.formatMoney(registration,2,"")
                app.commercialInsurance = app.formatMoney(commercialInsurance,2,"")
                app.temporaryLicenceFee = app.formatMoney(temporaryLicenceFee,2,"")
                app.otherFees = app.formatMoney(otherFees,2,"")
          }
    }
})
