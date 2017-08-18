
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
        choose_special_file_for_order: function () {
            if($("input[name='ziliao[]']:checked").length>0){
                $("input[name='wenjian'][value=1]").prop("checked",true);
            }else{
                $("input[name='wenjian'][value=1]").prop("checked",false);
                $("input[name='wenjian'][value=0]").prop("checked",true);
            }
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
        ,
        hitTop:function () {
            $("body,html").animate({scrollTop:0},360)
        }

    });

    vm.init();

    $(document).scroll(function(){
        //console.log($(window).height())
        $(".floattip").css("top",$(document).scrollTop() + 245)
        var _list = []
        $(".box-inner").each(function(index,item){
            if ($(item).css("display") == "block") {
                //console.log($(item).find(".btn-danger").offset().top)
                var _top = $(item).offset().top
                if (_top  >= $(window).scrollTop() && _top< ($(window).scrollTop()+$(window).height())) {
                    _list.push(index)
                }
                
            }
        })
        //console.log(_list[_list.length-1])
        var _txt = $(".box-inner").eq(_list[_list.length-1]).prev().find("label").text().split('、')[1]
        if (_list.length > 0) 
            $(".ques").next().attr("href","help.html?q="+_txt)
    })

});