
define(function (require, exports, module) {


    $("#login input").bind("keydown", function () {
        $(this).next().addClass("hide")
    }) 
    
    function fixNull(val) {
        return val == null ? "" : val
    }

    var vmweb = avalon.define({
        $id: 'search',
        isInit:false, 
        chexi: [],
        chexing: [],
        chexingalllist:
        [
            {
                chexiid: 1,
                chexing: [
                        { chexingid: 1, chexingname: '118i 都市设计套装' },
                        { chexingid: 2, chexingname: '118i 领先型' },
                        { chexingid: 3, chexingname: '120i 领先型' },
                        { chexingid: 4, chexingname: '120i 运动设计套装' },
                        { chexingid: 5, chexingname: '125i M运动型' },
                        { chexingid: 6, chexingname: 'M135i' }
                ]
            }
            ,
            {
                chexiid: 2,
                chexing: [
                        { chexingid: 1, chexingname: '218i 领先型' },
                        { chexingid: 2, chexingname: '218i 运动设计套装' },
                        { chexingid: 3, chexingname: '220i 豪华设计套装' },
                        { chexingid: 4, chexingname: '218i 运动设计套装' },
                        { chexingid: 5, chexingname: '220i 运动型' },
                        { chexingid: 6, chexingname: '220i 领先型' }
                ]
            }
        ]
        ,
        chexialllist:
        [
            {
                branid: 1,
                chexi: [
                        { chexiid: 1, chexiname: '1系' },
                        { chexiid: 2, chexiname: '2系' },
                        { chexiid: 3, chexiname: '3系' },
                        { chexiid: 4, chexiname: '4系' },
                        { chexiid: 5, chexiname: '5系' },
                        { chexiid: 6, chexiname: '6系' }
                       ]
            }
            ,
            {
                branid: 2,
                chexi: [
                        { chexiid: 1, chexiname: 'x系' },
                        { chexiid: 2, chexiname: 'm系' },
                        { chexiid: 3, chexiname: 'z系' },
                        { chexiid: 4, chexiname: 'i系' },
                        { chexiid: 5, chexiname: 'zore系' },
                        { chexiid: 6, chexiname: 't系' }
                ]
            }
        ]
        ,
        searchlist:[],
        dropdown: function () {
            var _this = $(this);
            _this.parent().next().toggle();
        }
        ,
        sort: function () {
            var _this = $(this)
            if (_this.hasClass("desc")) {
                _this.addClass("asc").removeClass("desc")
            } else {
                _this.addClass("desc").removeClass("asc")
            }
        }
        ,
        select: function (_this,inputname,id) {
            var _val = _this.text();
            if (_val.indexOf('(') > 0 || _val.indexOf(')') > 0) {
                _val = _val.replace("(", "<span>(").replace(")", ")</span>");
            }
            _this.parent().parent().hide().prev().find("p").html(_val);
            $("input[name='" + inputname + "']").val(id);//设置选择后的值
        }
        ,
        SelectCardArea: function (areid){
            vmweb.select($(this), "area", areid);
            //清空其他选项
            //$(this).parents("li").nextAll().slice(0, 3).find("dt p").html("")
            //vmweb.list([])//查询
        }
        ,
        SelectBrand: function (brandid) {
            vmweb.select($(this), "carbrand", brandid);
            //情况其他选项
            $(this).parents("li").nextAll().slice(0, 2).find("dt p").html("")
            //vmweb.selectConfig.branid = brandid;
            vmweb.chexi = [];//重置车系
            //一下是填充数据 正式应用只需一个ajax请求
            $.getJSON("/brand/"+brandid,function(data){vmweb.chexi = data})
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
            // for (var i = 0; i < vmweb.chexialllist.length; i++) {
            //     if (brandid == vmweb.chexialllist[i].branid) {
            //         for (var j = 0; j < vmweb.chexialllist[i].chexi.length; j++) {
            //             vmweb.chexi.push(vmweb.chexialllist[i].chexi[j]);
            //             //console.dir(vmweb.chexialllist[i].chexi[j])
            //         }
            //     }
            // }
            //以上for循环实际应用时候可删除！！！
        }
        ,
        //chexiid可以换string类型就看传什么参数
        //只是绑定了changed事件查询事件
        //还没有changed改变各个下拉框的值
        SelectChexi: function (chexiid) {
            vmweb.select($(this), "chexi", chexiid);
            $(this).parents("li").nextAll().slice(0, 2).find("dt p").html("")
            vmweb.chexing = [];//重置车系
            //一下是填充数据 正式应用只需一个ajax请求
            $.getJSON("/brand/"+chexiid,function(data){vmweb.chexing = data})
            //同selectBrand
            // for (var i = 0; i < vmweb.chexingalllist.length; i++) {
            //     if (chexiid == vmweb.chexingalllist[i].chexiid) {
            //         for (var j = 0; j < vmweb.chexingalllist[i].chexing.length; j++) {
            //             vmweb.chexing.push(vmweb.chexingalllist[i].chexing[j]);
            //         }
            //     }
            // }
            //vmweb.list([])//查询
        }
        ,
        SelectChexing: function (chexingid) {
            vmweb.select($(this), "car", chexingid);
            var _area = $('input[name=area]').val();
            var _car = $('input[name=car]').val();
            var direct_url = "/s?area="+_area+"&car="+_car;
            window.location.href = direct_url;
            return false;
            $("form[name='SearchPageForm']").submit()
        }
        ,
        slideOption: function () {
            var _this = $(this)
            _this.toggleClass("hover")
            var _data = _this.attr("data-slide-data")
            if (_data.toString() === "1") {
                $(this).parent().parent().prevAll("dl").hide();
                _this.attr("data-slide-data", 0)
                _this.html(_this.html().replace(_this.attr("data-s") ,_this.attr("data-more")));
            } else {
                $(this).parent().parent().prevAll("dl").show();
                _this.attr("data-slide-data", 1)
                _this.html(_this.html().replace(_this.attr("data-more"), _this.attr("data-s")));
            }
            
        }
        ,
        showDetail: function () {
            var _this = $(this)
            var _parent = _this.parents("td.nobd").eq(0);
            _this.parents("td.td").addClass("hoverbg");
            _parent.find(".info-c").show().html(_this.attr("data-title"));
        }
        ,
        showCheckbox: function () {
            var _this = $(this);
            _this.hide().next().show().next().show().parents("dl").eq(0).find("dd label").toggleClass("hide")
        }
        ,
        hideCheckbox: function () {
            var _this = $(this);
            _this.hide().prev().hide().prev().show().parents("dl").eq(0).find("dd label").toggleClass("hide").end().find("input").addClass("filter-curs")
        }
        ,
        
        duibicheck: function () {
            var _i = $(this).find("i")
            _i.toggleClass("checks-me")
            //$(this).toggleClass("filter-curs").attr("checked", !$(this).attr("checked"))
        } 
        ,
        pagesize: 10,
        pageindex: 1,
        pagecount: 100,
        brandid: 1,
        car: '',    //车型id
        area: '',   //地区id
        juli: '',   //车源距离
        body_color: '',   //车身颜色
        interior_color: '', //内饰颜色
        licheng: '',  //行驶里程
        chuchang: '',  //出厂年月
        fukuan: '', //付款方式
        buytype: '',  //上牌用途
        biaozhun: '' //排放标准
        ,
        list: function (options) {
            //真实环境替换url地址
            //data中的参数自行添加
            
            $.ajax({
                //url: "http://www.hwache.cn/s?",
                // url: "/js/module/search/searchbaojia.json",
                type: "get",
                dataType: "json",
                data: {
                    pagesize: vmweb.pagesize,
                    pageindex: vmweb.pageindex,
                    pagecount: vmweb.pagecount,
                    brandid: vmweb.brandid,
                    car: vmweb.car, //车型id
                    area: vmweb.area,   //地区id
                    juli: vmweb.juli,   //车源距离
                    body_color: vmweb.body_color,   //车身颜色
                    interior_color: vmweb.interior_color, //内饰颜色
                    licheng: vmweb.licheng,  //行驶里程
                    chuchang: vmweb.chuchang,  //出厂年月
                    fukuan: vmweb.fukuan, //付款方式
                    buytype: vmweb.buytype,  //上牌用途
                    biaozhun: vmweb.biaozhun //排放标准
                },
                beforeSend: function () {
                    //console.log("beforeSend");
                    //_this.next().fadeIn(200);
                }
                ,
                success: function (data) {
                    // vmweb.searchlist = data
                    //console.log("list:--->" + vmweb.licheng)
                }
            });
            
        }
        ,
        init: function () {
            $("[data-toggle=tooltip]").tooltip()
            vmweb.list([])
            //setTimeout(function () {
            //    vmweb.isInit = true
            //}, 1000)
        }
        ,
        textchange: function (val) {
            
            //function doInit() {
            //    if (vmweb.isInit) {
            //        vmweb.list([])
            //    } else {
            //        doInit()
            //    }
            //}
            //doInit()
        },
        compare_car:function(area,brand,buytype){
            var _ids = new Array();
            var _idsObj = $("i.checks-me[name^='compare_queue[']");
            if(_idsObj.length<=1){
                alert("对比需要至少两个以上报价才能进行，请按规则操作")
                return false
            }else if(_idsObj.length >5){
                alert("目前只支持两个以上五个以内报价对比，请按规则操作")
                return false
            }else{
                _idsObj.each(function(index, item){
                    _ids.push( $(item).attr('name').replace(/[^0-9]/g,''))
                })
                _ids = _ids.join(',')
                //alert(_ids);
                window.location.href="/compare/"+area+"/"+brand+"/"+_ids+"/"+buytype;
            }
        }

    });
    vmweb.init()
  


/*
$(".i-checks input[type='checkbox']").bind("click", function () {
    var _inputclass = "filter-curs"
    var _dl = $(this).parents("dl");
    var _find = ".i-checks input[type='checkbox']";
    //_allinput == true not first
    var _allinput = typeof ($(this).parents("dt")[0]) == "undefined"
    
    //$(this).toggleClass(_inputclass).attr("checked", !$(this).attr("checked"))
    var _allselect = true;

    //点击的是全选
    if (!_allinput) {
        _dl.find(_find).toggleClass(_inputclass).attr("checked", !$(this).attr("checked"));
        if ($(this).hasClass(_inputclass))  _dl.find(_find).addClass(_inputclass)
        else _dl.find(_find).removeClass(_inputclass)
    } else {
        $(this).toggleClass(_inputclass).attr("checked", !$(this).attr("checked"))
        $.each(_dl.find(_find), function (index, item) {
            if (!$(item).attr("checked") && index != 0) {
                _allselect = false;
                _dl.find("dt .i-checks input[type='checkbox']").removeClass(_inputclass).removeAttr("checked");
            }
        })
    
        if (_allselect) {
            _dl.find(_find).addClass(_inputclass).attr("checked", "checked");
        }
    }
})
*/
    

 

$("dt .i-checks-me").click(function () {
   var _i = $(this).find("i")
   _i.toggleClass("checks-me")
   var _dl = $(this).parents("dl")
   var _input = _dl.find("dt input[tpye='hidden']")
    //多选
   if (_dl.index() == 1 || _dl.index() == 2) {
       _dl.find("dd ul li em").addClass("selected")
       _dl.find("dd ul li label i").addClass("checks-me")
   }
   else {
       
       _dl.find("dd ul li em").removeAttr("class")
   }
   _input.val('')

});

$(".search-select-option dl dd ul li").bind("click", function (event) {
    //event.stopPropagation()
    var _this = $(this)
    var _val = _this.find("em").text()
    //ms-duplex 数据绑定的时候 inputs名字和 if里面的input名字同名了
    //导致inputs对象无法赋值无法触发list方法
    var _inputs = _this.parents("dl").find("input[type='hidden']")
    //多选
    if (_this.find("label")[0]) {
        var _i = $(this).find("i")
        _i.toggleClass("checks-me")
        _this.find("em").toggleClass("selected")
        var _arr = []
        var _flag = true //判断是否是全选
        $.each(_this.parent().find("li"), function (index, item) {
            if ($(item).find("em").hasClass("selected")) {
                _arr.push($(item).find("em.selected").text())
            } else {
                _flag = false
            }
        })
        if (_flag) {
            _inputs.parent().find("i").addClass("checks-me")
        } else {
            _inputs.parent().find("i").removeClass("checks-me")
        }
        _val = _arr.toString()
        //
    } else {
        var _input = _this.parents("dl").find("dt label i").removeClass("checks-me")
        _this.parent().find("em").removeClass("selected")
        _this.find("em").addClass("selected")
    }
    _inputs.val(_val)
    //$("form[name='SearchPageForm']").submit()
    //vmweb.list([])
})

});