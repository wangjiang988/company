
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
        
    });

    vm.init();

});