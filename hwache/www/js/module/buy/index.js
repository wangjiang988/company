/**
 * 购买页面js控制器
 *
 * @auther      技安 php360@qq.com
 * @copyright   ©2015 Suzhou HuanGuoSoft Co.,Ltd.
 * @link        http://www.huanguosoft.com
 */
define(function(require) {

    var dialog = require('module/dialog/dialog');

    var vm = avalon.define({
        $id: 'controller',
        username: '',
        phone: '',
        num: 1,
        // 提交表单
        submitForm: function(e) {
            var _submitOk = true;
            if (vm.username.length == 0) {
                _submitOk = false;
                d('请输入用户名');
            } else if (vm.phone.length == 0) {
                _submitOk = false;
                d('请输入手机号');
            }

            if (!_submitOk) {
                e.preventDefault()//阻止页面刷新
                return false;
            }
        }
    });

    var d = function(str) {
        var _d = dialog({
            fixed: true,
            content: str
        }).show();
        setTimeout(function () {
            _d.close().remove();
        }, 2000);
    };

});
