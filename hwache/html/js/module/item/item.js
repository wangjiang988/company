
define(function (require) {

    require("jq");//

    var vm = avalon.define({
        $id: 'item',
        txt:"\u5df2\u540c\u610f",
        init: function () {

        }
        ,
        srollTopShow: function (index) {
            var _top = 0;
            if (index==1) {
                _top = 800;
            }
            else if (index == 2) {
              
                _top = $("#liping").click().offset().top - 100
            }
            $("html,body").animate({scrollTop:_top +"px"},300)
        }
        ,
        toggleContent: function () {
            var _this = $(this)
            _this.find("i").toggleClass("hidec").parents(".box").eq(0).find(".box-inner").removeClass("show").toggle();
        }
        ,
        agree: function () {
            var _this = $(this)
            _this.parents(".box").eq(0).find("code").removeAttr("class").text(vm.txt).end().next(".box").find(".box-inner").slideDown(300).end().find(".title i").removeClass("hidec");
            
        }
        ,
        back: function () {

        }
        ,
        huji:function(){
            var _this = $(this)
            var _className = "cur-select"
            _this.siblings().removeClass(_className).end().addClass(_className).parent().find("input[type='hidden']").val(_this.text()).parent().prev().prev().prop("checked","checked")
        }
        ,
        pay: function () {

            var _this = $(this)
            var _flag = true
           
            $.each(_this.parents(".wapper:eq(0)").find(".box"), function (index, item) {
                var _txt = $(item).find("code").text()
                if (_txt != vm.txt) {
                    _flag = false;
                    $(item).find(".box-inner").slideDown(300).end().find(".title i").removeClass("hidec")
                    $("html,body").animate({scrollTop:$(item).offset().top - 100},200);
                    //console.log(index);
                    return false
                }
            })

            if (_flag) {

                var _ck =_this.next().find("input[type='checkbox']")
                if (_ck.prop("checked")) {
                    //window.location.href = $("#txturl").val()
                    $("form[name='item-form']").submit()
                }else{
                    alert("请选择订单约定条款")
                }
                
            }
           
        }
    });

    vm.init();

});