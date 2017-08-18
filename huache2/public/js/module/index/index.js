define(function (require) {

    require("jq");//
    require("base");//加载需要include的文件信息
    var vm = avalon.define({
        $id: 'box',
        selectConfig: [{ branid: 0, chexiid: 0 }],
        search: function () {
            var _form = $("form[name='indexSearchForm']");
            var _txt = _form.attr("data-txt");
            var _brand = _form.find("input").eq(0);
            var _chexi = _form.find("input").eq(1);
            var _chexing = _form.find("input").eq(2);
            if ($.trim(_brand.val()) == "" || _brand.val() == _txt) {
                if (!_brand.hasClass("error")) {
                    _brand.addClass("error");
                }
                _brand.val(_txt);
            }
            else if ($.trim(_chexi.val()) == "" || _chexi.val() == _txt) {
                if (!_chexi.hasClass("error")) {
                    _chexi.addClass("error");
                }
                _chexi.val(_txt);
            }
            else if ($.trim(_chexing.val()) == "" || _chexing.val() == _txt) {
                if (!_chexing.hasClass("error")) {
                    _chexing.addClass("error");
                }
                _chexing.val(_txt);
            }
            else {
               _form.submit();
            }
            
        }
        ,
        chexi: [],
        chexing: [],
        
        showhide: function () {
            $(this).parent().parent().parent().find("dl").hide()
            $(this).next("dl").slideToggle();
        }
        ,
        selectBrand: function (id) {
            //console.log(id)
            vm.select(this);
            var _this = $(this);
            vm.selectConfig.branid = id;
            vm.chexi = [];//重置车系
            vm.chexing = [];//重置车系
            //清空车系，车型
            _this.parent().parent().parent().next().find("input").val("").end().next().find("input").val("");
           
            //一下是填充数据 正式应用只需一个ajax请求
            $.getJSON("/brand/"+id,function(data){vm.chexi = data})
            //data为json格式数据如下：
            //[
            //    { chexiid: 1, chexiname: 'x系' },
            //    { chexiid: 2, chexiname: 'm系' },
            //    { chexiid: 3, chexiname: 'z系' },
            //    { chexiid: 4, chexiname: 'i系' },
            //    { chexiid: 5, chexiname: 'zore系' },
            //    { chexiid: 6, chexiname: 't系' }
            //]
            //当然chexiid和chexiname自定义 和页面上的页面同步就行
            // for (var i = 0; i < vm.chexialllist.length; i++) {
            //     if (id == vm.chexialllist[i].branid) {
            //         for (var j = 0; j < vm.chexialllist[i].chexi.length; j++) {
            //             vm.chexi.push(vm.chexialllist[i].chexi[j]);
            //             //console.dir(vm.chexialllist[i].chexi[j])
            //         }
            //     }
            // }
            //以上for循环实际应用时候可删除！！！
        }
        ,
        selectCar: function (id) {
            vm.select(this);
            var _this = $(this);
            vm.selectConfig.chexiid = id;
            vm.chexing = [];//重置车型
            _this.parent().parent().parent().next().find("input").val("");
          $.getJSON("/brand/"+id,function(data){vm.chexing = data})
            //同selectBrand
            // for (var i = 0; i < vm.chexingalllist.length; i++) {
            //     if (id == vm.chexingalllist[i].chexiid) {
            //         for (var j = 0; j < vm.chexingalllist[i].chexing.length; j++) {
            //             vm.chexing.push(vm.chexingalllist[i].chexing[j]);
            //         }
            //     }
            // }
            //console.dir(vm.chexing)
            //以上for循环实际应用时候可删除！！！
        }
        ,
        selectModel: function (id) {
            vm.select(this);
            $("input[name='car']").val(id);
        }
        ,
        select: function (obj) {
            var _val = $(obj).text();
            var _slideBox = $(obj).parent();
            _slideBox.parent().find("input").val(_val).removeClass("error");
            _slideBox.slideToggle();
        }
        ,
        marginTop: function () {
            var _mbox = $(".i-search .tip").find("ul:first");
            var _li = _mbox.find("li");
            var _length = _li.length;
            var _height = _li.eq(0).height();
            var _mt = parseInt(_mbox.css("margin-top"));

            if (_mt >= (_length - 1) * _height) {
                _mt = 0;
            } else {
                _mt += _height;
            }
            _mbox.animate({ marginTop: "-" + _mt + "px" }, function () {
                $.noop();
            });
        },
        animateTop: function () {
            vm.marginTop();
            setInterval(vm.marginTop, 3000);
        }
        ,
        init: function () {
            $(document).click(function (e) {
               
                var _tagname = e.target.tagName;
                if (_tagname != "I" && _tagname != "DD" && _tagname != "DT" && _tagname != "A"  ) {
                    if ($(e.target).parent().context.tagName == "IMG" && $(e.target).parent()[0].tagName =="EM") {
                      
                    }
                    $(".i-s-panel dl").hide();
                }
               
            });
            vm.animateTop();
        }
    });
    
    vm.init();

});