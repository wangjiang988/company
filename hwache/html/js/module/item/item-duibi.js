
define(function (require) {

    require("jq");//

    var vm = avalon.define({
        $id: 'duibi',
        init: function () {

        }
        ,
        toggleContent: function () {
            
            $(this).find("i").toggleClass("hidec")
            $("." + $(this).parents("tr").next("tr").attr("class")).toggle()
        }
    });

    vm.init();

});