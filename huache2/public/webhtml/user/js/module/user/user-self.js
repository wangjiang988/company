define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    //开发环境中请把每个$.ajax中的error方法删除
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left") 
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("./user-area-select-component")
    require("/webhtml/common/js/module/vue.common.mixin") 

    var app = new Vue({
        el: '.user-content',
        mixins: [mixin],
        data: {
            nickName:"",
            nickNameTmp:"",
            isSetNick:!1,
            isSetAddress:!1,
            province:"",
            city:"",
            provinceTmp:"",
            cityTmp:"",
            address:"",
            isSelectProvince:!0,
            isInputAddress:!0,
            isClickUpload:!1,
            isUpload:!1,
            isSelectFile:!1,
            uploadStatus:0,
            callChecked:!0,
            setNickUrl:"",
            setAddressUrl:""
        },
        mounted:function(){
            
        }
        ,
        methods:{
            setNick:function(){
                $("#setNick").hcPopup({'width':'400'})
            },
            doSetNick:function(){
                var _this = this;
                if(_this.nickNameTmp ==""){
                    _this.callChecked = !1;
                    return false;
                }
                $.ajax({
                    type: "post",
                    url: app.setNickUrl,
                    data: {
                        nickName:_this.nickNameTmp,
                        _token:$("input[name='_token']").val()
                    },
                    dataType: "json",
                    success: function(data){
                        _this.nickName = _this.nickNameTmp
                        _this.isSetNick = !0
                        //刷新?
                        if(data.success ==1){
                            window.location.reload();
                        }
                    },
                    error: function(data){
                        _this.nickName = _this.nickNameTmp
                        _this.isSetNick = !0
                    }    
                })   
                $("#setNick").hide()
            },
            setAddress:function(){
                $("#setAddress").hcPopup({'width':'400'})
            },
            doSetAddress:function(){
                var _flag = !0
                var _this = this
                if (this.provinceTmp === "" || this.cityTmp === "") {
                    this.isSelectProvince = !1
                    _flag = !1
                }
                else if (this.address === "") {
                    this.isInputAddress = !1
                    _flag = !1
                }
                if(_flag) {
                    $("#setAddress").hide()
                    $.ajax({
                        type: "post",
                        url: app.setAddressUrl,
                        data: {
                            province:_this.provinceTmp,
                            city:_this.cityTmp,
                            address:_this.address,
                            _token:$("input[name='_token']").val()
                        },
                        dataType: "json",
                        success: function(data){
                            _this.province = _this.provinceTmp
                            _this.city = _this.cityTmp
                            _this.isSetAddress = !0
                            //刷新?
                            if(data.success ==1){
                                window.location.reload();
                            }
                        },
                        error: function(data){
                            _this.province = _this.provinceTmp
                            _this.city = _this.cityTmp 
                            _this.isSetAddress = !0
                        }    
                    })   
                } 
            },
            setAddressStatus:function(){
                this.isInputAddress = !0
            },
            listenSelect:function(_province,_city){
                this.provinceTmp        = _province
                this.cityTmp            = _city 
                this.isSelectProvince   = !0
            },
            authentication:function(_province,_city){
                $("#authentication").hcPopup({'width':'400'})
                /*$.ajax({
                    type: "get",
                    url: "/user/getAuthentication/", 
                    data: {
                        
                    },
                    dataType: "json",
                    success: function(data){
                       if (data.success == 0) $("#authentication").hcPopup({'width':'400'})
                       else window.location.href = "K.1.26"
                    },
                    error: function(data){
                        $("#authentication").hcPopup({'width':'400'})
                    }    
                })   */
                
            },
            showUpload:function(_province,_city){
                this.isClickUpload = !0
            },
            upload:function(){
                document.getElementById('upload-file').click()
                /*this.isUpload = !1
                this.isSelectFile = !1*/
            },
            uploadFile:function(){
                require("/webhtml/common/js/vendor/jquery.form")
                var _this = this
                var _form = $("form[name='user-img-form']")
                var options = {
                   success: function (data) {
                       _this.isUpload = !0
                       if(data.success ==1){
                           window.location.reload();
                       }
                   }
                   ,
                   error:function(){
                       _this.isUpload = !0
                   }
                   ,
                   clearForm:true
                }
                _form.ajaxForm(options).ajaxSubmit(options)
            },
            canceUploadFile:function(){
                this.isClickUpload = !0
                this.isUpload = !1
                this.isSelectFile = !1
                var _img = document.getElementById('upload-img')
                _img.src = _img.getAttribute("data-src")
            },
            uploadAgain:function(){
                this.upload()
                /*this.isUpload = !1
                this.isSelectFile = !1*/
            },
        }
        ,
        watch:{
            
            '':function(){
                 
            }  
            
        }

    }) 
     
  
    module.exports = {
        
        initNick:function(nick){
            app.nickName = nick;
            app.nickNameTmp = nick
            app.isSetNick = !0;
        },
        initAddress:function(province,city,address){
            app.province = province;
            app.city = city;
            app.address = address;
            app.isSetAddress = !0;
        },
        initUserImg:function(src){
            document.getElementById('upload-img').src = src;
            app.isClickUpload = !0;
            app.isUpload = !0;
            app.isSelectFile = !0;
        },
        initSetUrl:function(_setNickUrl,_setAddressUrl){
            app.setNickUrl = _setNickUrl;
            app.setAddressUrl =  _setAddressUrl;
        },
    }
})