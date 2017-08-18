
define(function (require) {

    require("jq");//

    var vm = avalon.define({
        $id: 'custom',
        init: function () {
            vm.timeDiff("2015-10-22 10:45:20")
        }
        ,
        getTime:function (day){
            re = /(\d{4})(?:-(\d{1,2})(?:-(\d{1,2}))?)?(?:\s+(\d{1,2}):(\d{1,2}):(\d{1,2}))?/.exec(day);
            return new Date(re[1],(re[2]||1)-1,re[3]||1,re[4]||0,re[5]||0,re[6]||0);
        }
        ,
        timeDiff: function (oldtime) {

            _fun = function () {

                var _now = new Date();
                var _old = new Date(oldtime);
                var _diff = _now - _old
                var _totalsecond = _diff / 1000
                var _minite = Math.floor(_totalsecond / 60)
                var _second = Math.floor(_totalsecond % 60)
                
                if (_minite > 20 || _minite < 0 ) {
                    $(".jishi span[class!='fuhao']").text(0)
                    clearInterval(_settime)
                    return
                } else {

                    _minite = 20 - _minite
                    _second = 60 - _second == 60 ? 59 : 60 - _second
                    _minite = _minite.toString()
                    _second = _second.toString()
                    _minite = _minite.length == 2 ? _minite : "0" + _minite
                    _second = _second.length == 2 ? _second : "0" + _second
                    $(".jishi span").eq(0).text(_minite.slice(0, 1)).end()
                                    .eq(1).text(_minite.slice(1)).end()
                                    .eq(3).text(_second.slice(0, 1)).end()
                                    .eq(4).text(_second.slice(1))
                }
            }
          var _settime =  setInterval(_fun, 1000)
        }
    });

    vm.init();

});