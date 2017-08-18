define(function (require) {

    require("jq");//
    var vm = avalon.define({
        $id: 'pay',
        init: function () {

            var _scrollTop = $(document).scrollTop();
            var _winWidth = $(window).width();
            var _payWin = $('.pay-win');
            var _winLeft = (_winWidth / 2) - (_payWin.width() / 2);
            _payWin.css(
                            {
                                "left": _winLeft + "px",
                                "top": "20%"
                            }
                        )
                    .show();
            $('.zhifu-tip').css({ "top": _scrollTop + "px" });


        }
        ,
        pay: function () {
            $('.zhifu-tip').fadeIn('300');
        }
      
    });
    
    vm.init();

});