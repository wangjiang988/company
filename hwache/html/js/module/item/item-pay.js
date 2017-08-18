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
                      if (call) {
                        call() 
                        $(".countdown").hide()
                        $(".timeout-text").removeClass("hide")
                        $(".timeout").removeClass("hide").CountDown({
                            startTime:endTime,
                            endTime :endTime,
                            timekeeping:'timeout',
                            callback:function(){
                                //if (call) {call()} 
                            }
                        }) 
                      }
                      
                  }
            }) 
        }
        ,
        toolTip:function(){
           $(".tip").hover(function(){
              $(this).parents(".psr-wapper").find(".tooltip").addClass("in")
           },function(){
              $(this).parents(".psr-wapper").find(".tooltip").removeClass("in")
           })
        }  
    });

    vm.init()
    vm.toolTip()

    module.exports = {
        init:function(startTime,endTime,call){
           vm.init(startTime,endTime,call)
        }
       
    }
        
});