define(function (require, exports, module) { 

    
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
                {isToggle:!1,isAgree:!1},
                {isToggle:!1,isAgree:!1},
                {isToggle:!1,isAgree:!1},
                {isToggle:!1,isAgree:!1},
                {isToggle:!1,isAgree:!1},
                {isToggle:!1,isAgree:!1}
                
            ],
            step6:{
                isAgree:!1,
                isParentSelect:0,
                childVal:0,
                householdRegistration:"",
                foreign:"",
                child:[
                    {val:1,txt:'\u56fd\u5185\u5176\u4ed6\u975e\u9650\u724c\u57ce\u5e02\u6237\u7c4d\u5c45\u6c11'},
                    {
                        val:2,
                        txt:'\u56fd\u5185\u5176\u4ed6\u9650\u724c\u57ce\u5e02\u6237\u7c4d\u5c45\u6c11',
                        list:[
                            /*{id:0,txt:'\u5317\u4eac',isSelect:!1},
                            {id:0,txt:'\u4e0a\u6d77',isSelect:!1},
                            {id:0,txt:'\u5e7f\u5dde',isSelect:!1},
                            {id:0,txt:'\u5929\u6d25',isSelect:!1},
                            {id:0,txt:'\u676d\u5dde',isSelect:!1},
                            {id:0,txt:'\u8d35\u9633',isSelect:!1},
                            {id:0,txt:'\u6df1\u5733',isSelect:!1},
                            {id:0,txt:'\u82cf\u5dde',isSelect:!1} */
                        ]
                    },
                    {val:3,txt:'\u4e2d\u56fd\u519b\u4eba'},
                    {
                        val:4,
                        txt:'\u975e\u4e2d\u56fd\u5927\u9646\u4eba\u58eb',
                        list:[
                            {id:1,txt:'\u5916\u7c4d\u4eba\u58eb',isSelect:!1},
                            {id:2,txt:'\u53f0\u80de',isSelect:!1},
                            {id:3,txt:'\u6e2f\u6fb3\u4eba\u58eb',isSelect:!1},
                            {id:4,txt:'\u6301\u7eff\u5361\u534e\u4fa8',isSelect:!1} 
                        ]
                    }
                ],
                licensingIndicators :"2"
            }
            ,
            step11:{
                isAgree:!0,
                parent:"",
                child:[],
                shangpai:0
            },
            isSelectAgree:[],
            isClickPay:!1,
            errorList:[],
            errorMsg:""
            
        },
        mounted:function(){
             
        }
        ,
        methods:{
            srollTopShow: function (index) {
                var _top = 0;
                if (index==1) {
                    _top = 800
                }
                else if (index == 2) {
                    _top = $("#liping").click().offset().top - 86
                }
                $("html,body").animate({scrollTop:_top +"px"},300)
            },
            pay:function(){
                var _flag = !0
                this.errorList = []
                $.each(this.item,function(idx,it){
                    if (!it.isAgree) {
                        //有[没有同意的]
                        //it.isToggle = !0
                        //app.animateToTitle(idx)
                        _flag = !1
                        app.errorList.push(idx + 1)
                        //return false
                    }
                }) 
                if (this.errorList.length > 0) {
                    this.errorMsg = "第" + this.errorList.join("、") + "项" + (this.isSelectAgree.length == 0 ? ",服务协议" : "") +"未同意！对不起，未全部同意请恕无法进入下个订购步骤～"
                }
                else{
                    if (this.isSelectAgree.length == 0)
                        this.errorMsg = "服务协议未同意！对不起，未全部同意请恕无法进入下个订购步骤～"
                }
                if (_flag) {
                    if (this.isSelectAgree.length == 0) {
                        this.isClickPay = !0
                    }else{
                        $("#item-form").submit();
                      //  window.location.href = "zhifu.html"
                    }
                }
            },
            animateToTitle:function(idx){
                if (idx >= $(".item-form .box").length) return
                $("html,body").animate({scrollTop:$(".item-form .box").eq(idx).find(".title").offset().top - 86  },300)
            },
            relatedMatters:function(){
                this.step11.isAgree = !0
                if (this.step11.shangpai != 2) {
                    var _flag = !0  
                    if(this.step11.parent == "" )
                    { 
                        this.step11.isAgree = !1
                        _flag = !1
                    }
                    else if(this.step11.parent ==  1)
                    {
                        if (this.step11.child.length == 0) {
                            this.step11.isAgree = !1
                            _flag = !1
                        }
                    }
                    if (_flag) {
                        this.agree(10)
                    }
                }else{
                    this.agree(10)
                }
            },
            agreeRegistrationService:function(){
                
                this.step6.isAgree = !0
                var _idendity = parseInt(this.step6.isParentSelect) ,
                    _childVal = parseInt(this.step6.childVal) ,
                    _flag     = !0 
                //户籍选择
                if(_idendity === 0){
                    _flag = !1
                }
                else if (_idendity === 1 /*|| _idendity === 2*/) {
                    //国内其他限牌城市户籍居民
                    if(_childVal == 0 ){
                        _flag = !1
                    }
                    if(_childVal === 2 ){
                        if(this.step6.householdRegistration === ""){
                            _flag = !1
                        }
                    }
                    //非中国大陆人士
                    if(_childVal === 4){
                        if(this.step6.foreign === ""){
                            _flag = !1
                        }
                    }
                }
                //注册企业选择
                else if (_idendity === 3 || _idendity === 4) {

                }
                if(this.step6.licensingIndicators === "0"){
                    _flag = !1
                }

                if (_flag) {
                    this.step6.isAgree = !1
                    this.agree(5)
                }
            },
            selectParant:function(val){
                this.step6.isParentSelect = 1
                this.step6.isAgree = !1
                if (val == 0 || val == 3) {
                    this.step6.householdRegistration = ""
                    this.step6.foreign = ""
                    this.clearSecondIdentity()
                    this.clearFirstIdentity()
                } 
                if (val == 1) {
                    this.step6.foreign = ""
                    this.clearSecondIdentity()
                } 
                if (val == 4) {
                    this.step6.householdRegistration = ""
                    this.clearFirstIdentity()
                } 
              
            },
            setDefIdentity:function(index){
               this.step6.isParentSelect = 2
               this.step6.childVal = 0
               this.clearFirstIdentity()
               this.clearSecondIdentity()
            },
            toggleContent:function(index){
               this.item[index].isToggle = !this.item[index].isToggle 
            },
            agree:function(index){
                if(this.item[index].isAgree) return
                this.item[index].isAgree = !0
                this.item[index].isToggle = !1 
                this.item[index+1].isToggle = !0  
                setTimeout(function(){
                    app.animateToTitle(index+1)
                },100)
            },
            selectHouseholdRegistration:function(index,id,val){
                if (this.item[5].isAgree) {return}
                this.step6.householdRegistration = val  
                this.step6.childVal = 2
                this.step6.isParentSelect = 1
                $.each(this.step6.child[1].list,function(idx,it){
                    if(index === idx) it.isSelect = !0
                    else it.isSelect = !1
                })
                this.step6.foreign = ""
                this.clearSecondIdentity()
            },
            selectForeign:function(index,id,val){
                if (this.item[5].isAgree) {return}
                this.step6.foreign = val  
                this.step6.childVal = 4
                this.step6.isParentSelect = 1
                $.each(this.step6.child[3].list,function(idx,it){
                    if(index === idx) it.isSelect = !0
                    else it.isSelect = !1
                })
                this.step6.householdRegistration = ""
                this.clearFirstIdentity()
            },
            clearFirstIdentity:function(){
                $.each(this.step6.child[1].list,function(idx,it){
                    it.isSelect = !1
                }) 
            },
            clearSecondIdentity:function(){
                $.each(this.step6.child[3].list,function(idx,it){
                    it.isSelect = !1
                }) 
            },
            hitTop:function () {
                $("body,html").animate({scrollTop:0},360)
            }
        }
        ,
        watch:{
            
            'step11.child.length':function(n,o){
                if(n == 0) return
                this.step11.parent = 1
            },
            'step11.parent':function(n,o){
                if (n == 0) this.step11.child = []
            },  
            'step6.isAgree':function(n,o){
                if (n) setTimeout(function(){app.step6.isAgree = !1},3000)
            },  
            'step11.isAgree':function(n,o){
                if (!n) setTimeout(function(){app.step11.isAgree = !0},3000)
            }    
  
            
        }

    })

    //监控滚动条滚动事件
    $(window).scroll(function(event){
        $(".floattip").css("top",$(document).scrollTop() + 245)
    })
  
    module.exports = {
        
        init : function(obj){
            app.step6.child[1].list.push({id:0,txt:obj,isSelect:!1})
        },
        initShangPai : function(sp){
            app.step11.shangpai = sp
        } 
    }
});