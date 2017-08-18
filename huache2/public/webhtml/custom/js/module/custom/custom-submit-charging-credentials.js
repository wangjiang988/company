define(function(require, exports, module) {
  //根目录{webhtml}自行配置
  require("/webhtml/common/js/module/head")
  require("/webhtml/common/js/module/vue.common.mixin")
  require("/webhtml/common/js/vendor/time.jquery")
  require("/webhtml/common/js/vendor/hc.popup.jquery")
  require("/webhtml/common/js/vendor/jquery.form")

  var app = new Vue({
    el: '.content',
    mixins: [mixin],
    data: {
      price: null,
      priceSource:0,
      isPrice: !1,
      files: [],
      isFile: !1,
      isInput: !1,
      bankNumList:[],
      isSelect:!1,
      isSend:!1,
      countDownNum:5,
      countDownObj:{},
      sendCount:0,
      successUrl:"",
      errorUrl:"",
      sendSmsUrl:"",
      priceEmpty:!1
    },
    mounted: function() {

    },
    methods: {
      simpleCountDown: function(call) {
          var _time = setInterval(function() {
              if (app.countDownNum <= 0) {
                  clearInterval(app.countDownObj)
                  if (call) { call() }
              } else
                  app.countDownNum--
          }, 1000)
          this.countDownObj = _time
      },
      send: function() {
        var _this = this
        this.files = []
        $.each($("input[type='file']"), function(index, item) {
          if ($(item).val() != "") _this.files.push($(item).val())
        })
        var _price = (this.price + "").replace(/,/g,"")
        if (this.price == "" || this.price == null || isNaN(_price)) {
            this.priceEmpty = !0
            this.isPrice = !0
        }
        if (this.files.length != $("input[type='file']").length)
          this.isFile = !0
        if(!this.isPrice && !this.isFile)
          $("#subWin").hcPopup({'width': '420'})

      },
      doSend:function(event) {
        var _this = this;
        var _form = $("form");
        var options = {
          beforeSend: function() {
            event.target.setAttribute("disabled", true)
          },
          success: function(data) {
            event.target.removeAttribute("disabled")
            $("#subWin").hide();
            var _href = "";
            //提交失败
            if (data.success == 0 ) {
                _href = app.errorUrl;
            }else{
              //提交成功
              _href = app.successUrl;
            }
            window.location.href = _href;
          },
          error: function() {
            event.target.removeAttribute("disabled")
            $("#subWin").hide()
          }
        }
        _form.ajaxForm(options).ajaxSubmit(options)
      },
      checkBankInfo: function() {
        if (this.bankInfo === "") this.isBankInfo = !0
      },
      initBankInfo: function() {
        this.isBankInfo = !1
      },
      checkUserName: function() {
        if (this.userName === "" || !this.isChinese(this.userName)) this.isUserName = !0
      },
      initUserName: function() {
        this.isUserName = !1;
      },
      checkPrice: function(event) {
         var _price = this.price.replace(/,/g,"")
         if (this.price == "") {
            $('#t_price').val("")
            this.priceEmpty = !0
         }else if(isNaN(_price)){
            this.priceEmpty = !0
            this.isPrice = !0
            $('#t_price').val("")
         }else{
            $('#t_price').val(_price);
            this.price = this.formatMoney(_price,2,"");
         }


      },
      initPrice: function(event) {
          this.isPrice = !1
          this.priceEmpty = !1
      },
      upload: function() {
        this.isFile = !1
        document.getElementById('upload-file').click()
      },
      uploadHand: function() {
        this.isFile = !1
        document.getElementById('upload-file-hand').click()
      },
      checkBankNum: function() {
        this.isInput = !1
        setTimeout(function(){
          app.isSelect = !0
        },400)
        if (this.bankNum === "") this.isBankNum = !0
        else if (!this.luhmCheck(this.bankNum)) this.isBankError = !0
        else this.getBankInfo()
      },
      initBankNum: function() {
        this.isBankNum = !1
        this.isBankError = !1
        this.isInput = !0
        this.isSelect = !1
      },
      getBankInfo: function() {
        $.ajax({
             type: "get",
             url:"/order/getBankInfo/",
             data: {
                numid:app.bankNum
             },
             dataType: "json",
             beforeSend: function(data){

             },
             success: function(data){
                //汇款银行
                app.bankInfo = data.bankInfo
                //汇款人姓名
                app.userName = data.userName

             },
             error: function(data){
                //汇款银行
                app.bankInfo = "中国农业银行"
                //汇款人姓名
                app.userName = "王大力"
             }
        })
      },
      selectBankNum: function(num) {
         this.bankNum = num
         this.isSelect = !0
         this.isBankError = !1
         this.getBankInfo()
      },
      bankNumChange: function() {
         $.ajax({
             type: "get",
             url:"/order/bankNumChange/",
             data: {

             },
             dataType: "json",
             beforeSend: function(data){

             },
             success: function(data){
                app.bankNumList = data.list
             },
             error: function(data){
                app.bankNumList = ["6228480838666526473","623130770700118458","6212263602009716598"]
             }
          })
      },
      valiteBankNum: function(event) {
        this.bankNum = this.replaceEmpty(this.bankNum)
        this.bankNumChange()
      },

      init: function(startTime, endTime, call) {
        $(".countdown").CountDown({
          startTime: startTime,
          endTime: endTime,
          timekeeping: 'countdown',
          callback: function() {
            if (call) {
              call()
              $(".countdown").hide()
              $(".timeout-text").removeClass("hide")
              $(".timeout").removeClass("hide").CountDown({
                startTime: endTime,
                endTime: endTime,
                timekeeping: 'timeout',
                callback: function() {
                  //if (call) {call()}
                }
              })
            }

          }
        })
      },
      sendCode:function(){
          if (this.sendCount >= 3) {
             $("#endWin").hcPopup({'width':'420'})
          }else{
            if (this.isSend) $("#errorWin").hcPopup({'width':'420'})
            else $("#tipWin").hcPopup({'width':'420'})
            this.isSend = !0
            setTimeout(function(){
              app.isSend = !1
            },60*2*1000)
          }
      },
      doSendCode:function(){
          $.ajax({
             type: "get",
             url:app.sendSmsUrl,
             data: {
                phone:$("input[name='phone']").val(),
                code:1,
                template_code:'78590067',
                max:3
             },
             dataType: "json",
             beforeSend: function(data){

             },
             success: function(data){
                $("#tipWin").hide()
                //验证码间隔在两分钟之内
                if (data.success == 0) {
                    $("#errorWin").hcPopup({'width':'420'})
                }
                else if (data.success == 1) {
                    $("#successWin").hcPopup({'width':'420'})
                    app.simpleCountDown(function(){
                       $("#successWin").hide()
                    })
                    //返回已经发送几次了
                    app.sendCount = data.count
                }

             },
             //开发环境删除error方法
             error: function(data){
                $("#tipWin").hide()
                $("#successWin").hcPopup({'width':'420'})
                app.simpleCountDown(function(){
                   $("#successWin").hide()
                })
                app.sendCount = 2
             }
          })
      },


    },
    watch: {


    }

  })

  module.exports = {
    initSendCount:function(count){
        app.sendCount = count
    },
    initUrl:function(successUrl,errorUrl,sendSmsUrl){
        app.successUrl = successUrl;
        app.errorUrl   = errorUrl;
        app.sendSmsUrl = sendSmsUrl;
    }
  }
})