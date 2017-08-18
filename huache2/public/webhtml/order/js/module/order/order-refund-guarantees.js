define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin")
    require("/webhtml/custom/js/module/custom/dropdown-components")
    require("/webhtml/user/js/module/user/user-code-count-down-component")
    require("/webhtml/common/js/vendor/hc.popup.jquery")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            doWithdrawalURl:"",
            isEmtpy:!1,
            isError:!1,
            isCodeError:!1,
            code:"",
            length:0
        },
        mounted:function(){
            this.evaluation()
        }
        ,
        methods:{
            checkLength:function(event){
                this.length = this.getByteLen(event.target.value)
            },
            evaluation:function(){
                $(".formItemDiff").mouseover(function() {
                    $(this).addClass("sele").prevAll().addClass("sele")
                    $(this).nextAll().removeClass("sele")
                    $(this).find(".cot").show()
                    //$(this).parent().find("input[type='hidden']").val($(this).prevAll().length+1);
                }).mouseout(function() {
                    var _val = $(this).parent().find("input[type='hidden']").val()
                    $(this).find(".cot").hide()
                    $(this).parent().find(".formItemDiff").removeClass('sele').slice(0, _val).addClass('sele')
                }).click(function(){
                    $(this).parent().find("input[type='hidden']").val($(this).prevAll().length+1);
                })
            },
            send:function(){
                $("#comment").submit()
            },
            getByteLen:function (val) {
                  var len = 0;
                  for (var i = 0; i < val.length; i++) {
                    var a = val.charAt(i);
                    if (a.match(/[^\x00-\xff]/ig) != null) {
                      len += 1;
                    }
                    else {
                      len += 1;
                    }
                  }
                  return len;
            }

        },
        watch:{

        }

    })

    module.exports = {

    }
})
