﻿
define(function (require) {

    require("jq");//

    var vm = avalon.define({
        $id: 'item',
        init: function () {
            vm.timeDiff("2015-10-21 12:45:20")
        }
        ,
        getTime:function (day){
            re = /(\d{4})(?:-(\d{1,2})(?:-(\d{1,2}))?)?(?:\s+(\d{1,2}):(\d{1,2}):(\d{1,2}))?/.exec(day);
            return new Date(re[1],(re[2]||1)-1,re[3]||1,re[4]||0,re[5]||0,re[6]||0);
        },
        timeDiff: function (oldtime) {

            _fun = function () {

                var _now = new Date();
                var _old = vm.getTime(oldtime);
                var _diff = _now - _old

                var _totalsecond = _diff / 1000
                var _hours = Math.floor(_totalsecond / 60 / 60)
                var _minite = Math.floor(_totalsecond / 60 % 60)
                var _second = Math.floor(_totalsecond % 60)

                if (_hours > 24 || _hours < 0) {
                    $(".jishi span[class!='fuhao']").text(0)
                    clearInterval(_settime)
                    return
                } else {
                    _hours = 24 - _hours - 1
                    _minite = 60 - _minite
                    _second = 60 - _second == 60 ? 59 : 60 - _second
                    _hours = _hours.toString()
                    _minite = _minite.toString()
                    _second = _second.toString()
                    _hours = _hours.length == 2 ? _hours : "0" + _hours
                    _minite = _minite.length == 2 ? _minite : "0" + _minite
                    _second = _second.length == 2 ? _second : "0" + _second
                    $(".jishi span").eq(0).text(_hours.slice(0, 1)).end()
                                    .eq(1).text(_hours.slice(1)).end()
                                    .eq(3).text(_minite.slice(0, 1)).end()
                                    .eq(4).text(_minite.slice(1)).end()
                                    .eq(6).text(_second.slice(0, 1)).end()
                                    .eq(7).text(_second.slice(1))
                }
            }
            var _settime = setInterval(_fun, 1000)
        }
    });

    vm.init();

});