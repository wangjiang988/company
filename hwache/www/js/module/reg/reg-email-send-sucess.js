define(function (require) {

    require("jq");//location
    require("module/reg/reg-common");
    var vm = avalon.define({
      $id: 'reg',
      resendInfo:false,
      resend: function(url, email) {
        //真实环境请把$.post释放出来当然也可以自己写ajax
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.post(url, {'email':email}, function (data) {
          if (data.error_code == 0) {
            vm.resendInfo = true;
          }
        })
      }

    });

});