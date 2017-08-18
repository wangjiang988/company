define(function (require, exports, module) {

    Vue.component("phone-code", {
        props:["phone","sendurl","sendtype","iscode","max","isSumSendCount"],
        template: `
            <p class="fs14 m-t-10">
               <span class="pull-left">手机验证码：</span>
               <input @keydown="tiggerSendCode" type="text" maxlength=6 v-model="code" @focus="initCode" @blur="checkCode" name="code" placeholder="6位数字" :class="{'form-control':true, 'pull-left':true ,code:false, w150:true, 'error-bg':isEmpty}">
               <button @click="sendCode" type="button" class="ml20 pull-left btn btn-s-md btn-danger fs14 btn-s mt-5 btn-send-code" v-html="sendCodeTxt"></button>
            </p>
        `,
        data: function() {
            return {
                code:"",
                isEmpty:!1,
                countDownTime:60,
                sendCodeTxtArr:["\u83b7\u53d6\u9a8c\u8bc1\u7801","\u91cd\u65b0\u83b7\u53d6\uff08<span class=\"red\">{0}</span>s\uff09"],
                sendCodeTxt:"",
                sendCount:0,
            }
        },
        computed: {

        },
        mounted:function(){
            this.sendCodeTxt = this.sendCodeTxtArr[0]
        },
        methods: {
            tiggerSendCode:function(event){
               if (event.keyCode == 13) {
                    event.preventdefault()
                    event.stopPropagation()
                    return false
                }
            },
            sendCode:function(event){

                if (this.phone === "" || !this.isPhoneNo(this.phone) ){
                    this.$emit('valite-code',this.code,true,false)
                    return
                }
                var app = this
                $.ajax({
                    type: "GET",
                    url:app.sendurl,
                    data: {
                        phone:app.phone,
                        template_code:app.sendtype,
                        code:app.iscode,
                        max:app.max
                    },
                    dataType: "json",
                    beforeSend: function(data){

                    },
                    success: function(data){
                        //data.count 返回还有多少次可以发送验证码
                        if (app.isSumSendCount) {
                            app.sendCount = data.count
                            app.$emit('valite-send-count',data.count)
                        }
                        if (app.isSumSendCount && app.sendCount < 5)
                            app.countDown(event.target)
                        else
                            app.countDown(event.target)
                    },
                    error: function(data){
                        app.$emit('valite-send-count',4)
                        app.sendCount = 4
                        app.countDown(event.target)
                    }
                })

            },
            isPhoneNo:function(phone) {
                var pattern = /^1[34578]\d{9}$/
                return pattern.test(phone)
            },
            countDown:function(evt){
                evt.setAttribute("disabled", true)
                var app = this
                app.sendCodeTxt = app.sendCodeTxtArr[1].replace("{0}",app.countDownTime)
                var _time =
                    setInterval(function() {
                        if (app.countDownTime == 0) {
                            if (app.isSumSendCount && app.sendCount >= 5) evt.setAttribute("disabled", true)
                            else evt.removeAttribute("disabled")
                            app.countDownTime = 60
                            app.isCanCountDown = !1
                            app.sendCodeTxt = app.sendCodeTxtArr[0]
                            clearInterval(_time)
                        } else {
                            app.countDownTime--
                            app.sendCodeTxt = app.sendCodeTxtArr[1].replace("{0}",app.countDownTime)
                        }

                    },1000)
            },
            checkCode:function(){
                var _flag = !0
                if (this.code === "") {
                    this.isEmpty = !0
                    _flag = !1
                }
                else if (!this.checkNum(this.code)) {
                    this.isEmpty = !0
                    _flag = !1
                }
                this.$emit('valite-code',this.code,_flag)
            },
            checkNum:function(num){
                if (num.length < 6 || isNaN(num)) {
                    return !1
                }
                return !0
            },
            initCode:function(){
                //console.log("xxx")
                this.isEmpty = !1
            },
        },
        watch:{
            'code' :function(){

            }
        }

    })
})