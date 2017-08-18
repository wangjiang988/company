define(function (require,exports,module) {

    require("module/common/hc.popup.jquery")
    var vm = avalon.define({
        $id: 'orderStatus',
        continueOrder:null,
        init: function () {
            
        },
        send:function () {
            $("#sendWin").hcPopup({'width':'400'}) 
        },
        stopOrder:function () {
            $("#stopWin").hcPopup({'width':'400'})
        },
        doStopOrder:function () {
            $("#stopWin").hide()
        },
        doSend:function () {
            $("#sendWin").hide()
        }
    })

    vm.init()

    module.exports = {
        init:function(){
           
        } 
    }
        
});