define(function (require) {

    require("jq");//
    var vm = avalon.define({
        $id: 'pay',
        init: function () {
            var _time = 9;
            var _fun = function () {
                if (_time==0) {
                    window.location.href = "index.html";
                }
                $("small i").text(_time);
                _time--;
                
            }
            setInterval(_fun, 1000);
        }
       
    });
    
    vm.init();

});