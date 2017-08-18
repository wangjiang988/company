define(function(require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left")
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/custom/js/module/custom/dropdown-components")
    require("/webhtml/user/js/module/user/user-code-count-down-component")
    require("/webhtml/common/js/vendor/hc.popup.jquery")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            doWithdrawalURl: "",
            doSuccessUrl: "",
            doErrorUrl: "",
            checkMsgCode: "",
            isEmtpy: !1,
            isError: !1,
            isCodeError: !1,
            code: "",
            fee: 0,
            countDownNum: 5,
            countDownObj: {},
            price: "",
            maxprice: "",
            isPrice: !0,
            withdrawalCount: 0,
            isTransform: !1,
            successStatus: -1,
            priceEmpty: !1,
            error_msg: "抱歉，可提现余额已发生变化，<br>您本次提现申请无效，请重新申请"
        },
        mounted: function() {

        },
        methods: {

            initPrice: function() {
                this.isPrice = !0
                this.priceEmpty = !1
                this.isTransform = !1
            },
            setPrice: function() {

                if (this.price == "") {
                    this.priceEmpty = !0
                } else if (isNaN(this.price.toString().replace(/,/g, ""))) {
                    this.isPrice = !1
                    this.priceEmpty = !0
                } else {
                    var _price = this.price > this.maxprice ? this.maxprice : this.price;
                    this.price = this.formatMoney(_price.toString().replace(/,/g, ""), 2, "")
                    this.isTransform = !0
                    $('#price_money').val(_price);
                }
            },
            withdrawal: function() {

                if (isNaN(this.price) || this.price == "") {
                    if (!this.isTransform)
                        this.isPrice = !1
                    else this.showPhoneValite()
                } else this.showPhoneValite()
            },
            showPhoneValite: function() {
                $("#phoneValite").hcPopup({ 'width': '420' })
            },
            checkCode: function() {
                var _flag = !1;
                var _phone = $("input[name='phone']").val();
                $.ajax({
                    url: app.checkMsgCode,
                    type: "get",
                    data: { 'phone': _phone, 'code': this.code, 'template_code': '78535082' },
                    dataType: "json",
                    async: false,
                    beforeSend: function() {

                    },
                    success: function(data) {
                        _flag = data.success
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        _flag = !1;
                    }
                })
                return _flag
            },
            doWithdrawal: function() {
                if (this.code == "") {
                    this.isEmtpy = !0
                    this.isError = !1
                    setTimeout(function() {
                        app.isEmtpy = !1
                    }, 3000)
                } else if (!app.checkCode()) {
                    app.isEmtpy = !1
                    this.isError = !0
                    setTimeout(function() {
                        app.isError = !1
                    }, 3000)
                } else {
                    this.isEmtpy = !1
                    this.isCodeError = !1
                    this.isError = !1

                    $.ajax({
                        url: app.doWithdrawalURl,
                        type: "post",
                        data: $('#withdrawal_application').serialize(),
                        dataType: "json",
                        beforeSend: function() {

                        },
                        success: function(data) {
                            //0:支付失败 1：支付成功
                            if (data.success == 0) {
                                app.simpleCountDown(function() {
                                    window.location.href = app.doErrorUrl; //"S.Z.9申请提现.html"
                                })
                                $("#errorWin").hcPopup({ 'width': '450' })
                            } else if (data.success == 1) {
                                app.simpleCountDown(function() {
                                    window.location.href = app.doSuccessUrl; //"S.Z.8提现.html"
                                })
                                $("#successWin").hcPopup({ 'width': '450' })
                            }

                            //真实环境请删除下面的error方法
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            //拦截错误显示
                            if (XMLHttpRequest.responseJSON.message)
                                app.error_msg = XMLHttpRequest.responseJSON.message + ', 提交失败!'

                            $("#errorWin").hcPopup({ 'width': '450' })
                            app.simpleCountDown(function() {
                                //window.location.href = ""
                            })
                        }
                    })
                }
            },
            getCode: function(code) {
                this.code = code
                if (code == "") {
                    this.isEmtpy = !0
                    setTimeout(function() {
                        app.isEmtpy = !1
                    }, 3000)
                }

            },
            simpleCountDown: function(call) {
                var _time = setInterval(function() {
                    if (app.countDownNum <= 0) {
                        clearInterval(app.countDownObj)
                        if (call) { call() }
                    } else
                        app.countDownNum--
                }, 1000)
                this.countDownObj = _time
            }

        },
        watch: {

        }

    })

    module.exports = {
        initMaxprice: function(maxprice) {
            app.maxprice = maxprice;
        },
        initWithdrawalCount: function(count) {
            app.withdrawalCount = count;
        },
        initWithdrawalFee: function(fee) {
            app.fee = fee;
        },
        initUrl: function(doWithdrawalURl, doSuccessUrl, doErrorUrl, checkMsgCode) {
            app.doWithdrawalURl = doWithdrawalURl;
            app.doSuccessUrl = doSuccessUrl;
            app.doErrorUrl = doErrorUrl;
            app.checkMsgCode = checkMsgCode;
        }
    }
})