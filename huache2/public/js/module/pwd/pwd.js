define(function (require) {

    require("jq");//
    require("module/reg/reg-common"); 

    var vm = avalon.define({
        $id: 'reg',
        init: function () {

        } 
        ,
        select: function (_type) {
           $(this).parents(".pwd-form-div").find("i").removeClass("sl").end().end().addClass("sl")
           $("#type").val(_type)
        }  
        ,
        next: function () {
          var _type = $("#type").val()
          //console.log(_type == 1)
          var _url = ""
          if (_type == 1) {
              _url="/getpwdbyphone/"
          }else if (_type == 2) {
             _url="/getpwdbyemail/"
          }else{

          }

          if (_url != "") {
            window.location.href = _url
          }
          
        }  
    });
    
    vm.init();

});