define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left")
    require("/webhtml/common/js/module/vue.common.mixin") 
    require("/webhtml/common/js/vendor/jquery.form")
    require("./user-area-select-component")

    var app = new Vue({
        el: '.user-content',
        mixins: [mixin],
        data: {
            url:{
                successUrl:"",
            },
            province:"" ,
            city:"",
            name:"",
            isName:!1,
            numId:"",
            files:[],
            isFile:!1,
            isInput:!1,
            isParentSelect:!1,
            childVal:0,
            householdRegistration:"",
            foreign:"",
            fileName:"",
            drop:!1,
            province:"",
            city:"",
            dropCate:"personage",
            isCate:!1,
            isSub:!1,
            isSame:!1,
            dropValue:"\u975e\u8425\u4e1a\u4e2a\u4eba\u5ba2\u8f66",
            vehicleUseList:[{txt:'\u975e\u8425\u4e1a\u4f01\u4e1a\u5ba2\u8f66',tag:'company'},{txt:'\u975e\u8425\u4e1a\u4e2a\u4eba\u5ba2\u8f66',tag:'personage'}],
            child:[
                {val:1,txt:'\u56fd\u5185\u5176\u4ed6\u975e\u9650\u724c\u57ce\u5e02\u6237\u7c4d\u5c45\u6c11'},
                {
                    val:2,
                    txt:'\u56fd\u5185\u5176\u4ed6\u9650\u724c\u57ce\u5e02\u6237\u7c4d\u5c45\u6c11',
                    list:[
                        {id:0,txt:'\u5317\u4eac',isSelect:!1},
                        {id:0,txt:'\u4e0a\u6d77',isSelect:!1},
                        {id:0,txt:'\u5e7f\u5dde',isSelect:!1},
                        {id:0,txt:'\u5929\u6d25',isSelect:!1},
                        {id:0,txt:'\u676d\u5dde',isSelect:!1},
                        {id:0,txt:'\u8d35\u9633',isSelect:!1},
                        {id:0,txt:'\u6df1\u5733',isSelect:!1},
                        {id:0,txt:'\u82cf\u5dde',isSelect:!1} 
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
            companyCate:[{txt:'\u4e0a\u724c\u5730\u672c\u5e02\u6ce8\u518c\u4f01\u4e1a\uff08\u589e\u503c\u7a0e\u4e00\u822c\u7eb3\u7a0e\u4eba\uff09',val:'5'},{txt:'\u4e0a\u724c\u5730\u672c\u5e02\u6ce8\u518c\u4f01\u4e1a\uff08\u5c0f\u89c4\u6a21\u7eb3\u7a0e\u4eba\uff09',val:'6'}]

        },
        mounted:function(){
            
        }
        ,
        methods:{
            send:function(event){
                var _this = this
                var _form = $("form[name='user-special-file']")
                var options = {
                   beforeSend:function(){
                       event.target.setAttribute("disabled",true)
                   }, 
                   success: function (data) {
                       event.target.removeAttribute("disabled")
                       //success:0 相同的文件
                       if (data.success === 0){
                           _this.isSame = !0;
                       }else{//1 提交成功
                           window.location.href = app.url.successUrl;
                       }
                       //else //dosometing  
                   }
                   ,
                   error:function(){
                       event.target.removeAttribute("disabled")
                       _this.isSame = !0
                   }
                }

                this.isSub = !0
               
                if (this.name === "") 
                    this.isName = !0  
                else if (this.province === "" || this.city === "") 
                    $(".area-drop-btn").addClass("error-bg")
                else if(this.isParentSelect === 0 || this.childVal === 0) 
                    this.isCate = !0  
                else if((this.childVal == 2 && this.householdRegistration === "") || (this.childVal == 4 && this.foreign === "")) 
                    this.isCate = !0 
                else 
                    _form.ajaxForm(options).ajaxSubmit(options)
                
            }, 
            selectParant:function(val){
                this.isParentSelect = 1
                if (val == 0 || val == 3) {
                    this.householdRegistration = ""
                    this.foreign = ""
                    this.clearSecondIdentity()
                    this.clearFirstIdentity()
                } 
                if (val == 1) {
                    this.foreign = ""
                    this.clearSecondIdentity()
                } 
                if (val == 4) {
                    this.householdRegistration = ""
                    this.clearFirstIdentity()
                } 
              
            },
            setDefIdentity:function(index){
               this.isParentSelect = 2
               this.childVal = 0
               this.clearFirstIdentity()
               this.clearSecondIdentity()
            },
            selectHouseholdRegistration:function(index,id,val){
                this.householdRegistration = val  
                this.childVal = 2
                this.isParentSelect = 1
                $.each(this.child[1].list,function(idx,it){
                    if(index === idx) it.isSelect = !0
                    else it.isSelect = !1
                })
                this.foreign = ""
                this.clearSecondIdentity()
            },
            selectForeign:function(index,id,val){
                this.foreign = val  
                this.childVal = 4
                this.isParentSelect = 1
                $.each(this.child[3].list,function(idx,it){
                    if(index === idx) it.isSelect = !0
                    else it.isSelect = !1
                })
                this.householdRegistration = ""
                this.clearFirstIdentity()
            },
            clearFirstIdentity:function(){
                $.each(this.child[1].list,function(idx,it){
                    it.isSelect = !1
                }) 
            },
            clearSecondIdentity:function(){
                $.each(this.child[3].list,function(idx,it){
                    it.isSelect = !1
                }) 
            },
            loadFileName:function(event){
                this.fileName = event.target.files[0].name
            },
            upload:function(event){
                document.getElementById('upload-file').click()
            },
            delFile:function(event){
                document.getElementById('upload-file').value = ""
                this.fileName = ""
            },
            getArea: function (province,city) {
                this.province = province
                this.city     = city
                $(".area-drop-btn").removeClass("error-bg")
            },
            checkName: function () {
                if (this.name === "") this.isName = !0
            },
            initName: function () {
                this.isName = !1
            },
            dropDown: function () {
                this.drop = !this.drop
            },
            setValue: function (val) {
                this.dropValue              = val.txt
                this.dropCate               = val.tag
                this.drop                   = !this.drop
                this.isParentSelect         = 0
                this.childVal               = 0
                this.householdRegistration  = ""
                this.foreign                = ""
                this.clearFirstIdentity()
                this.clearSecondIdentity()
            }
        }
        ,
        watch:{
            'childVal':function(n,o){
                console.log(this.isParentSelect,n)
                this.isCate = !1
                this.isSub = !1
            },
            'isParentSelect':function(n,o){
                console.log(this.childVal,n)
                this.isCate = !1
                this.isSub = !1
            }
        }

    }) 
     
    module.exports = {
        initUrl:function(successUrl){
            app.url.successUrl = successUrl;
        }
    }
})
