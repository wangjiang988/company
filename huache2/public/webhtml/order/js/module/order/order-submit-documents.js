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
      bankInfo: "",
      isBankInfo: !1,
      bankNum: "",
      userName: "",
      price: null,
      priceSource:0,
      bankNumView: " ",
      isBankNum: !1,
      isBankError: !1,
      isUserName: !1,
      isPrice: !1,
      files: [],
      isFile: !1,
      isInput: !1,
      bankNumList:[],
      isSelect:!1,
      isSend:!1,
      countDownNum:5, 
      countDownObj:{},
      sendCount:0
    },
    mounted: function() {

    },
    methods: {
      send: function() {
        var _this = this
        this.files = []
        $.each($("input[type='file']"), function(index, item) {
          if ($(item).val() != "") _this.files.push($(item).val())
        })
        if (this.bankInfo === "")
          this.isBankInfo = !0
        else if (this.bankNum === "")
          this.isBankNum = !0
        else if (!this.luhmCheck(this.bankNum))
          this.isBankError = !0
        else if (this.userName === "" || !this.isChinese(this.userName))
          this.isUserName = !0
        else if (this.price === "" || this.price == 0 || this.price == null || isNaN(this.priceSource))
          this.isPrice = !0
        else if (this.files.length != $("input[type='file']").length)
          this.isFile = !0
        else
          $("#subWin").hcPopup({'width': '420'})

      },
      doSend:function(event) {
        var _this = this
        var _form = $("form")
        var options = {
          beforeSend: function() {
            event.target.setAttribute("disabled", true)
          },
          success: function(data) {
            event.target.removeAttribute("disabled")
            $("#subWin").hide()
            var _href = ""
            //提交失败
            if (data.success == 0 ) {
               _href = "OK.ZF.12银行转账支付余款凭证提交失败.html"
            }else{
              //提交成功
              _href = "OK.ZF.11银行转账支付余款凭证提交成功.html"
            }
            window.location.href = _href
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
        this.isUserName = !1
      },
      checkPrice: function() {
        if (!(this.price === "" || this.price == 0 || this.price == null || isNaN(this.priceSource))) {
            this.priceSource = this.price
            this.price = this.formatMoney(this.priceSource,2,"")
        }else this.isPrice = !0  
      },
      initPrice: function() {
        this.isPrice = !1
        if (this.priceSource != 0) 
            this.priceSource = (this.price + "").replace(/[',']/g, '')
        this.price = this.priceSource   
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
      simpleCountDown:function(call){
            var _time = setInterval(function () {
                if (app.countDownNum <=0) { 
                   clearInterval(app.countDownObj)
                   if (call) call()  
                }else
                  app.countDownNum--
            },1000)
            this.countDownObj = _time
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
              url:$("#sendcode").attr('sendurl'),
              data: {
                  phone:$("#sendcode").attr('phone'),
                  template_code:$("#sendcode").attr('template_code'),
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

      'bankNum': function(n, o) {
        var _html
        if (n.length > 4 && n.length < 8)
          _html = n.slice(0, 4) + " " + n.slice(4)
        else if (n.length >= 8 && n.length < 12)
          _html = n.slice(0, 4) + " " + n.slice(4, 8) + " " + n.slice(8, 12)
        else if (n.length >= 12)
          _html = n.slice(0, 4) + " " + n.slice(4, 8) + " " + n.slice(8, 12) + " " + n.slice(12)
        else
          _html = n
        this.bankNumView = _html
      },
      
    }

  })

  module.exports = {
    init: function(startTime, endTime, call) {
      app.init(startTime, endTime, call)
    },
    initSendCount:function(count){
        app.sendCount = count
    }  

  }
})