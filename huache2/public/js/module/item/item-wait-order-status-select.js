define(function (require,exports,module) {

    require("module/common/hc.popup.jquery")
    var vm = avalon.define({
        $id: 'orderStatus',
        continueOrder:null,
        init: function () {
            
        },
        send:function () {
            if (vm.continueOrder == null) {
               $("#showhide").removeClass("hide").show()
               setTimeout(function(){
                  $("#showhide").fadeOut(300)
               },3000)
            }else{
               vm.continueOrder ? $("#sendWin").hcPopup({'width':'400'}) : $("#stopWin").hcPopup({'width':'400'})
            }
        },
        stopOrder:function () {
            $("#stopWin").hide()
            $("#stops").submit();
        },
        doSend:function () {
            $("#sendWin").hide()
            $("#sellback").submit();
        }
    })

    vm.init()

    module.exports = {
        init:function(){
           
        } 
    }
        
});