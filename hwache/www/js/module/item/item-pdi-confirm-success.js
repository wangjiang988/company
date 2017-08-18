
define(function (require) {

    require("jq");//

    var vm = avalon.define({
        $id: 'item',
        init: function () {
            
        }
        ,
        tiCheMethod:function(){
            var _this = $(this)
            var _className = "cur-select"
            var _nextClassName = ["select-next","select-prev"]
            var _index = _this.index()
            _this.addClass(_className).siblings().removeClass(_className).removeClass(_nextClassName[0]).removeClass(_nextClassName[1])
            if (_index == 1) {
                _this.next().addClass(_nextClassName[0])
            }else{
                _this.next().addClass(_nextClassName[0]).end().next().addClass(_nextClassName[0])
            } 
            _this.parents("table").parent().find("input[type='hidden']").val(_this.text())
        }
        
    });

    vm.init();

});