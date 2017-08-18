define(function (require,exports,module) {
    var step9 = new Vue({
        el: '#vue',
        delimiters : ['${', '}'],
        unsafeDelimiters : ['{--', '--}'],//don't work --! with v-html repeat
        init:function(){
             
        }
        ,
        data: { 
            provide:0,
            item:"",
            work:"",
            time:"",
            isSub:true,
            error1:false,
            error2:false,
            error:false,
        }
        ,
        methods: {
            subsidy:function(){
                 this.isSub  = true
                 if (this.provide == 1) {
                     this.error  = false
                     this.error1 = false
                     this.error2 = false
                     if (this.item == "") {
                        this.error = true
                        this.isSub = false
                     }else if (this.item == 1) {
                        if (this.work == "") {
                            this.error1 = true
                            this.isSub = false
                        }else{
                            this.error1 = false
                        }
                     }
                     else if (this.item == 2) {
                         if (this.time == "") {
                            this.error2 = true
                            this.isSub = false
                        }else{
                            this.error2 = false
                        }
                     }
                 }else{

                 }
                 if (this.isSub) {
                     require("vendor/jquery.form")
                     var _form  = $("form[name='next-form']")
                     var options = {
                        type: 'post',
                        success: function(data) {
                           console.log(data)

                           if(data.step == 8) {
                                var url = window.location.href
                                var site =url.substr(0,url.length-1);
                                window.location.href=site+9;
                                //window.location.href = window.location.href.replace("step8","step9")
                           }        
                           else{           
                               if(data.error_code ==1){
                                   //require("module/common/hc.popup.jquery")
                                   //$("#tip-succeed").hcPopup({content:"操作成功",callback:function(){
                                       window.location.reload()
                                   //}})
                               }
                           }   
                        }
                     }
                     _form.ajaxForm(options).ajaxSubmit(options);
                 }
            }
            ,
            nothing:function(){
                this.error  = false
                this.error1 = false
                this.error2 = false
                this.provide = 0
                this.item = ""
                this.work = ""
                this.time = ""
            }
            ,
            provides:function(){
                this.error  = false
                this.error1 = false
                this.error2 = false
                this.provide = 0
                this.item = ""
                this.work = ""
                this.time = ""
            }
            ,
            selectItem:function(val){
                this.provide = 1
                this.item = val
            }
            ,
            focusInput:function(val){
                this.provide = 1
                this.item = val
                if (val == 1) {
                    this.error2 = false
                    this.time = ""
                }
                else if (val == 2) {
                    this.error1 = false
                    this.work = ""
                }
            }
           
             
        }
        ,
        watch:{
            'work':function(){
                this.work   = this.work.toString().replace(/\D/g,'')
                this.error1 = false
                if (this.work <= 0) {
                    this.work = this.work.toString().replace('0','')
                }
            },
            'time':function(){
                this.time   = this.time.toString().replace(/\D/g,'')
                this.error2 = false
                if (this.time <= 0) {
                    this.time = this.time.toString().replace('0','')
                }
            }
             
        }

    })

  
    
 
    module.exports = {
        init:function(provide,work,time){
            step9.provide = provide
            if (provide == 1) {
                //这个怎么判断来着？
                //如何判断经销商代办上牌？
                //如何判断上牌资料齐全？
                //provide == 0 不提供
                //provide == 1 提供
                //item    == 1 经销商代办上牌选中
                //item    == 2 上牌资料齐全选中
                //work         工作日的值
                //time         时限的值

                if (work!=0) {
                    step9.item = 1
                }
                else if (time!=0) {
                    step9.item = 2
                }
            }
            step9.work = work
            step9.time = time
        }
    }

})