/***********************************
**************2016-3-9**************
**************llm*******************
**************v1.0.0.0**************
***********************************/

define(function (require, exports, module) {
    
    $.fn.CountDown = function(_options){

        
        var defaults = {
           startTime: new Date(),//服务器时间
           endTime: new Date(),//限定时间
           timekeeping: 'countdown', //countdown  or timeout
           callback:function(){}
        }
        var options = $.extend(defaults, _options)
        var getTime = function (day){
            re = /(\d{4})(?:-(\d{1,2})(?:-(\d{1,2}))?)?(?:\s+(\d{1,2}):(\d{1,2}):(\d{1,2}))?/.exec(day);
            return new Date(re[1],(re[2]||1)-1,re[3]||1,re[4]||0,re[5]||0,re[6]||0);
        }
        this.each(function(){

            var _endTime = getTime(options.endTime)
            var _startTime = getTime(options.startTime)
            var _diff = options.timekeeping ==  'countdown' ?  _endTime - _startTime : _startTime - _endTime   
            var _totalsecond = _diff / 1000
            var _timeWapper= $(this)

            _difffun = function(){

                
                /*console.log(_startTime)
                console.log(_endTime)*/
                //var _diff = options.timekeeping ==  'countdown' ?  _endTime - new Date() : new Date() - _endTime   
                //_totalseconds = _totalsecond
                var _hours = Math.floor(_totalsecond / 60 / 60).toString()
                var _minite = Math.floor(_totalsecond / 60 % 60).toString()
                var _second = Math.floor(_totalsecond % 60).toString()
                if (options.timekeeping ==  'countdown') {
                   
                    if (_totalsecond <= 0 ) {
                        clearInterval(_setdifftime)
                        options.callback()
                        return
                    }
                }
                _hours = _hours.toString()
                _minite = _minite.toString()
                _second = _second.toString()
                _hours = _hours.length == 2 ? _hours : "0" + _hours
                _minite = _minite.length == 2 ? _minite : "0" + _minite
                _second = _second.length == 2 ? _second : "0" + _second
                /*console.log(_hours)
                console.log(_minite)
                console.log(_second)*/
                var _length = _timeWapper.find("span").length
                if (_length == 3) {
                     _timeWapper.find("span") 
                                    .eq(0).text(parseInt(_hours)).end()
                                    .eq(1).text(parseInt(_minite)).end()
                                    .eq(2).text(_second)
                }
                else if (_length == 4) {
                    var _day = _totalsecond / 60 / 60/ 24
                    _timeWapper.find("span") 
                                    .eq(0).text(parseInt(_day)).end()
                                    .eq(1).text(parseInt(_hours) % 24).end()
                                    .eq(2).text(parseInt(_minite)).end()
                                    .eq(3).text(_second)
                }
                else if (_length >5) {
                    _timeWapper.find("span").eq(0).text(_hours.slice(0, 1)).end()
                                    .eq(1).text(_hours.slice(1)).end()
                                    .eq(3).text(_minite.slice(0, 1)).end()
                                    .eq(4).text(_minite.slice(1)).end()
                                    .eq(6).text(_second.slice(0, 1)).end()
                                    .eq(7).text(_second.slice(1))
                }else{
                    _timeWapper.find("span") 
                                    .eq(0).text(_minite.slice(0, 1)).end()
                                    .eq(1).text(_minite.slice(1)).end()
                                    .eq(3).text(_second.slice(0, 1)).end()
                                    .eq(4).text(_second.slice(1))

                }
                if (options.timekeeping ==  'countdown') {
                    _totalsecond --
                }else{
                    _totalsecond ++
                }
                 
                //console.log(_totalsecond)

           }
           
           var _setdifftime = setInterval(_difffun, 1000)

        })
    }
    
});