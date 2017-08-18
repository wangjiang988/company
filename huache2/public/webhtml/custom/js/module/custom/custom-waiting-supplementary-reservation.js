define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")
    //require("/webhtml/common/js/vendor/time.jquery")
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/common/js/vendor/jquery.form")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
           userList:[],
           user:{
                id:"",
                name:"",
                phone:"",
                tel:""
           },
           isDisabled:!1,
           isEmpty:!1,
           optionalStatus:-1,
           optionalError:!1
        },
        computed: {

        },
        mounted:function(){

        }
        ,
        methods:{
            setDisabled:function(index){
                this.isDisabled = index == 0 ? !1 : !0
            },
            send:function(){
                if (this.userList.length == 0) {
                    this.isEmpty = !0
                }else if(this.optionalStatus != 1){
                    this.optionalError = !0
                }
                else
                    $("#tipWin").hcPopup({'width':'420'})
            },
            confirmReload:function(){
                $("#reloadWin").hcPopup({'width':'420'})
            },
            reload:function(){
                window.location.reload()
            },
            doSend:function(){
                $("#tipWin").hide()
                $("#main").submit()
            }

        },
        watch:{


        }

    })

    module.exports = {
        initUserList:function(array){
           app.userList = array
           app.user = app.userList[0] || app.user
        },
        initOptionalStatus:function(status){
           app.optionalStatus = status
        },
    }
})
