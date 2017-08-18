define(function (require, exports, module) {

    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/popup.vue") 

    var app = new Vue({
        el: '.item-form',
        data: {
            item:[
                {isToggle:!0,isAgree:!1},
                {isToggle:!1,isAgree:!1},
                {isToggle:!1,isAgree:!1},
                {isToggle:!1,isAgree:!1},
                {isToggle:!1,isAgree:!1},
                {isToggle:!1,isAgree:!1},
                {isToggle:!1,isAgree:!1}

            ],

            step5:{
                isAgree:!0,
                parent:"",
                child:[],
                limitSelect:"",
                isLimit:!1

            },
            isSelectAgree:[],
            isClickPay:!1,
            errorList:[],
            errorMsg:"",
            allAgree:!1

        },
        mounted:function(){
            
        }
        ,
        methods:{
            noagree:function(){
                this.isSelectAgree = []
            },
            getStatus:function(){
                this.isSelectAgree = "agree"
            },
            service: function () {
                app.$refs.popup.display() 
            },
            srollTopShow: function (index) {
                var _top = 0;
                if (index==1) {
                    _top = $("#yuanchang").click().offset().top - 86
                }
                else if (index == 2) {
                    _top = $("#feiyuanchang").click().offset().top - 86
                }
                $("html,body").animate({scrollTop:_top +"px"},300)
            },
            pay:function(){
                var _flag = !0
                this.errorList = []
                $.each(this.item,function(idx,it){
                    if (!it.isAgree) {
                        //有[没有同意的]
                        _flag = !1
                        app.errorList.push(idx + 1)
                    }
                })
                if (this.errorList.length > 0) {
                    this.errorMsg = "\u7b2c" + this.errorList.join("\u3001") + "\u9879" + (this.isSelectAgree.length == 0 ? "\u002c\u670d\u52a1\u534f\u8bae" : "") +"\u672a\u540c\u610f\uff01\u5bf9\u4e0d\u8d77\uff0c\u672a\u5168\u90e8\u540c\u610f\u8bf7\u6055\u65e0\u6cd5\u8fdb\u5165\u4e0b\u4e2a\u8ba2\u8d2d\u6b65\u9aa4\uff5e"
                }
                if (_flag) {
                    if (this.isSelectAgree.length == 0) {
                        this.isClickPay = !0
                    }else{
                        $("input[type='radio'],input[type='checkbox']").removeAttr('disabled')
                        $("#item-form").submit();
                    }
                }
            },
            animateToTitle:function(idx){
                $("html,body").animate({scrollTop:$(".item-form .box").eq(idx).find(".title").offset().top - 86  },300)
            },
            relatedMatters:function(){
                this.step5.isAgree = !0
                var _flag = !0
                if(this.step5.isLimit){
                    if(this.step5.limitSelect == ""){
                        this.step5.isAgree = !1
                        _flag = !1
                        setTimeout(function(){
                            app.animateTo("special-notice-1")
                        },1000)
                    }
                }
                if(this.step5.parent == "" ){
                    this.step5.isAgree = !1
                    _flag = !1
                }
                else if(this.step5.parent ==  1){
                    if (this.step5.child.length == 0) {
                        this.step5.isAgree = !1
                        _flag = !1
                    }
                }
                if (_flag) {
                    this.agree(4)
                }
            },
            animateTo:function(obj){
                $("html,body").animate({scrollTop:$("#"+obj).offset().top - 300},300)
            },
            toggleContent:function(index){
               this.item[index].isToggle = !this.item[index].isToggle
            },
            agree:function(index){
                if(this.item[index].isAgree) return
                this.item[index].isAgree = !0
                this.item[index].isToggle = !1
                //console.log(index , app.item.length,index < app.item.length)
                if (index < app.item.length-1)
                    this.item[index+1].isToggle = !0
                setTimeout(function(){
                    //if (index == app.item.length-1 ) return
                    app.animateToTitle(index+1)
                },100)
            },
            hitTop:function () {
                $("body,html").animate({scrollTop:0},360)
            }
        }
        ,
        watch:{

            'step5.child.length':function(n,o){
                if(n == 0) return
                this.step5.parent = 1
            },
            'step5.parent':function(n,o){
                if (n == 0) this.step5.child = []
            },
            'step5.isAgree':function(n,o){
                if (!n) setTimeout(function(){app.step5.isAgree = !0},3000)
            },
            item:{
                handler:function(n,o){
                    var _flag = !0
                    this.item.forEach(function(item,index){
                       if (!item.isAgree) {
                            _flag = !1
                            return false
                       }

                    })
                    if (_flag) this.allAgree = !0
                },
                deep:true
            }


        }

    })

    //监控滚动条滚动事件
    $(window).scroll(function(event){
        $(".floattip").css("top",$(document).scrollTop() + 245)
    })

    module.exports = {

        initIsLimit : function(limit){
            app.step5.isLimit = limit
        }
    }
});