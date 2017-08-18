define(function (require,exports,module) {

    require("module/common/time.jquery")
    var vm = avalon.define({
        $id: 'item', 
        startTime:'',
        endTime:'',
        init: function (startTime,endTime,call) {
            $(".countdown").CountDown({
                  startTime:startTime,
                  endTime :endTime,
                  timekeeping:'countdown',
                  callback:function(){
                      if (call) {call()}
                  }
            }) 
        }  
    });

    vm.init()

    module.exports = {
        init:function(startTime,endTime,call){
           vm.init(startTime,endTime,call)
        }
       
    }
        
});