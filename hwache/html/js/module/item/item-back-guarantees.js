
define(function (require) {

    require("jq");//

    var vm = avalon.define({
        $id: 'item',
        init: function () {
            
        }
        ,
        displayTm: function () {
            $(this).next().fadeIn(300)
        }
        ,
        hideTm: function () {
            $(this).next().hide()
        }
        ,
        pdibutie:function(){
            require("module/common/hc.popup.jquery")
            $("#pdi-tip").hcPopup({'width':'400'})
        }
        ,
        surebutie:function(){
            //应该是个ajax操作吧。。。
            $.getJSON("/surebutie/",function(){
                $("#pdi-tip").hide()
            })
        }
        
    });

    vm.init();

    $(".formItemDiff").mouseover(function() {
		$(this).addClass("sele").prevAll().addClass("sele")
		$(this).nextAll().removeClass("sele")
		$(this).find(".cot").show()
		$(this).parent().find("input[type='hidden']").val($(this).prevAll().length+1);
	}).mouseout(function() {
		$(this).find(".cot").hide()
	});

});