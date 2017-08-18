define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left")
    require("/webhtml/common/js/module/vue.common.mixin")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            // template_code: '78680082',
        },
        created:function(){
            require("/webhtml/common/js/module/store.legacy.min")
        },
        mounted:function(){
                phone   =  store.get("phone")
                if(!phone) return false;
                paymethod = $("input[name='paymethod']").val()
                if(!paymethod)  return false;
                if(paymethod == 4 )
                    this.sendsms_bank(phone)
                else
                    this.sendsms(phone, paymethod)
        },
        methods:{
            sendsms: function(phone,  paymethod){
                now    =  this.getNow()
                is_success = $("input[name='is_success']").val()
                money = $("input[name='money']").val()
                template_code = '78680082'; 
                if(!is_success)
                    template_code = '78680082'; //TODO 充值失败模板不存在
                    
                if(this.isPhoneNo(phone))
                {
                    $.ajax({
                         type: "GET",
                         url: '/member/sendSms',
                         data: {
                            phone: phone,
                            template_code: template_code,
                            paytype: paymethod,
                            time: now,
                            money: money,
                        },
                         dataType: "json",
                         beforeSend: function(data){
                         },
                         success: function(data){
                             console.log(data)
                            // if (app.checkPhoneStatus == 2) {
                            // }else{
                            // }
                         },
                         error: function(data){
                              console.log(data)
                            //app.countDown($event.target)
                            //app.isSendCode = !0
                         }
                    })   
                }
            },
            sendsms_bank: function(){  //银行转账短信发送
                 now        =  this.getNow()
                 is_success = $("input[name='is_success']").val()
                 money      = $("input[name='money']").val()
                 template_code= '78735055'
                if(!is_success)
                   template_code = '78560078'; //TODO 充值失败模板不存在
                if(this.isPhoneNo(phone))
                {
                    $.ajax({
                         type: "GET",
                         url: '/member/sendSms',
                         data: {
                            phone:phone,
                            template_code:template_code,
                            time: now,
                            money: money,
                        },
                         dataType: "json",
                         beforeSend: function(data){
                         },
                         success: function(data){
                             console.log(data)
                         },
                         error: function(data){
                              console.log(data)
                         }
                    })   
                }
            },
            isPhoneNo:function(phone) {
                var pattern = /^1[34578]\d{9}$/
                return pattern.test(phone)
            },
            getNow: function()
            {
                 var date = new Date();
                var seperator1 = "-";
                var seperator2 = ":";
                var month = date.getMonth() + 1;
                var strDate = date.getDate();
                if (month >= 1 && month <= 9) {
                    month = "0" + month;
                }
                if (strDate >= 0 && strDate <= 9) {
                    strDate = "0" + strDate;
                }
                var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
                        + " " + date.getHours() + seperator2 + date.getMinutes()
                        + seperator2 + date.getSeconds();
                return currentdate;
            }
          
        }
        ,
        watch:{

            '':function(){

            }

        }

    })

    module.exports = {
        format:function(price){
            return app.formatMoney(price,2,"￥")
        }

    }

})
