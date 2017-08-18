
define(function (require,exports,module) {

    require("jq");//
    require("module/common/time.jquery")
    //xuanzhuang model
    function model () {
        this.id          = ""
        this.pingpai     = ""
        this.name        = "" 
        this.xinghao     = "" 
        this.zhidaojia   = "" 
        this.anzhuangfei = "" 
        this.danjia      = "" 
        this.shuliang    = "" 
        this.price       = "" 
        this.total       = ""
    }
    var ycjingping       = [] //原厂选装精品
    var fycjingping      = [] //非原厂选装精品

    var vm = avalon.define({
        $id: 'custom',
        init: function () {
            
        }
        ,
        view:false
        ,
        i:0
        ,
        timelist:[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16]
        ,
        en:['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z']
        ,
        fill:[0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z']
        ,
        areaSn:['京','沪','津','渝','冀','晋','蒙','辽','吉','黑','苏','浙','皖','闽','赣','鲁','豫','鄂','湘','粤','桂','琼','川','贵','云','藏','陕','甘','青','宁','新']
        ,
        citylist:[
            {'id':125,'name':'苏州'},
            {'id':126,'name':'常熟'},
            {'id':127,'name':'无锡'},
            {'id':128,'name':'太仓'}
        ]
        ,
        displayTm: function () {
            $(this).next().fadeIn(300)
        }
        ,
        viewMethod:function(intgerParam){
            vm.view = intgerParam == 2? false:true

        }
        ,

        hideTm: function () {
            $(this).next().hide()
        }
        ,
        toggleList: function () {
            $(this).parents(".fs14").next(".tbl").toggle()
        }
        ,
        edit: function () { 
            var _prev = $(this).prev()
            var _label = $(this).prev().prev()
            _prev.show().val(_label.text()).focus()
            _label.hide()
            //$(this).prev().show().focus().prev().hide()
        }
        ,
        showSJD:function(){
            vm.i = 1
            if ($(this).parent().hasClass("disabled")) return
            $(".sj-s-cur").removeClass("sj-s-cur")
            $("dl.sjd").hide()
            $(this).parent().addClass("sj-s-cur").find("dl.sjd").show().prev().show()
        }
        ,
        setDealer:function(){
            var _val = ""
            $(".hasselect dd").each(function(index,item){
                //_val+=$(item).find("span").text() +","
                //_val+=$(item).attr("data-bind")+"|"+$(item).find("span").text() +","
                //_val+=$(item).attr("data-bind").split('-')[0] +"-"+$(item).find("span").text() +","
                _val+=$(item).attr("data-bind").split('-')[0] +"-"+$(item).find("span").text() +","

            })
            //_val = _val.split(",").slice(0,_val.split(",").length - 1)
            $("#pdi_date_dealer").val(_val)
        }
        ,
        hideSJD:function(index,d){
            //stopBubble(e)
            //这边出现了冒泡事件 额 不知道什么原因。。。
            //用vm.i做个限制只执行一次 
            //console.log(index)
            if (vm.i == 1) {
                vm.i = 0
                $(this).parent().hide().prev().hide().parent().removeClass("sj-s-cur")
                var _dl = $(".hasselect")
                var _span = $(this).parents("li").find(".txt-w").text()
                var _txt = $(this).text()
                var _tpl = vm.tpl
                var _id = "li_"+index
                $("#"+_id).remove()
                _dl.append(_tpl.replace("{0}",_span +" "+_txt ).replace("{1}",_id ).replace("{2}",d ))
                
                bindClick()
                vm.setDealer()
            }
        }
        ,
        tpl:"<dd id='{1}' data-bind='{2}'><span>{0}</span><i>x</i></dd>"
        ,
        rili:function(){  
            require("vendor/DatePicker/WdatePicker")
            /*//console.log(111111111111)
            $(this).date_input();*/
            $(this).prev().focus()
        }
        ,
        initDP:function(min,cur,max){
          
        }
        ,
        selectTime:function(){
            $(this).parent().find("li").removeClass("active").end().end().addClass("active").parent().find("input[type='hidden']").val($(this).text()).parent().prev().find(".dropdown-label span").text($(this).text())
        }
        ,
        selectTimeWithVal:function(val){
            $(this).parent().find("li").removeClass("active").end().end().addClass("active").parent().find("input[type='hidden']").val(val).parent().prev().find(".dropdown-label span").text($(this).text())
        }
        ,
        selectProvince:function(id){
             var $this = $(this)
             $this.parents(".dropdown-menu").find("input[type='hidden']").eq(0).val($this.text())
             $this.addClass("select-province").siblings().removeClass("select-province")
             $this.parent().prev().find("span").eq(0).removeClass("cur-tab").end().eq(1).addClass("cur-tab")
             $this.parent().hide().next().show()
             //$this.parent().next().find("dd").removeClass("select-city")
             vm.citylist=[]
             $.getJSON("/getcityjosn/"+id+"/",function(data){
                vm.citylist = data
             })
        }
        ,
        selectCity:function(){
             var $this = $(this)
             //$this.addClass("select-city").siblings().removeClass("select-city")
             $this.parents(".dropdown-menu").find("input[type='hidden']").eq(1).val($this.text())
             $(".area-tab-div").hide().prev().find(".dropdown-label span").text(
                $this.parents(".dropdown-menu").find("input[type='hidden']").eq(0).val() + 
                $this.parents(".dropdown-menu").find("input[type='hidden']").eq(1).val()
             )
             $this.parent().hide().prev().show()
             $this.parent().prev().prev().find("span").eq(1).removeClass("cur-tab").end().eq(0).addClass("cur-tab")
                      
        }
         ,
        upload:function(){
             var $this = $(this)
             $this.next().click()
        }
        ,
        change:function(){
             var $this = $(this)
             var _val = $this.val()
             $this.prev().prev().append("<span class='file-prev'>"+_val.substring(_val.lastIndexOf('\\')+1)+"</span>")
             $("#hfFile").val(
                $("#hfFile").val() == "" ? $this.val():

                $("#hfFile").val()+","+$this.val()

                )
        }
        ,
        
        dinggou:function(){

            ycjingping = []
            fycjingping = []
            //yuanchang
            $.each($(".bgtbl:eq(0)").find("tr").slice(1),function(index,item){
                
                var _item = $(item)
                if (_item.find("input.input").val() == "0" ) {
                    return
                }
                var _model = new model()
                _model.id           = _item.attr("data-id")
                _model.name         = _item.find("td:eq(0)").text()
                _model.xinghao      = _item.find("td:eq(1)").text()
                _model.zhidaojia    = _item.find("td:eq(2)").text()
                _model.anzhuangfei  = _item.find("td:eq(3)").text()
                _model.danjia       = _item.find("td:eq(4)").text()
                _model.shuliang     = _item.find("td:eq(5) input").val()
                _model.price        = _item.find("td:eq(6)").text()
                _model.total        = _item.parents("table").next().find("input[type='hidden']").val()
                ycjingping.push(_model)
                _item.find("td:eq(5) input").siblings().css("visibility","hidden")
                
            })

            

            //是否选择其中之一
            if (ycjingping.length != 0 )
            {  
                $(this).nextAll().removeClass("hide")
                if (!$(this).hasClass("end")) {
                    $(this).addClass("hide")
                }
                
                //提交
                $.ajax({
                        url: "http://www.hwache.cn/saveuserxzj/",
                        type: "post",
                        dataType: "json",
                        data: {
                            yc:ycjingping,
                            fyc:fycjingping
                        },
                        beforeSend: function () {
                            
                        }
                        ,
                        success: function (data) {
                            
                            var _error_code = data.error_code;
                            var _error_msg = data.error_msg;
                            console.log(_error_msg) //开发版请注释这行
                            if (_error_code == 0) {
                               
                            } else {

                            }
                            
                        }
                });


            }

            //console.log(vm.ycjingping)
            //console.log(vm.fycjingping)
        }
        ,
        setValue:function(obj){
            var _this = $(obj)
            var _price = parseInt( _this.parents("td").prev().text() )
            var _val = parseInt(_this.val())
            var _txt = _val * _price
            _txt =  _txt == 0 ? "" : _txt+""
            //每项的金额 选择购买件数 * 含安装费折后总单价
            _this.parents("td").next().text(_txt)
            var _total = 0
            var _tbl = _this.parents(".bgtbl")
            //总金额 
            $.each(_tbl.find("tr").slice(1),function(index,item){
                var _itemval  = parseInt($(item).find("td:last").text())
                _total += !isNaN(_itemval) ?_itemval: 0
            })
            _tbl.next().find("input[name='price']").val(_total)//后台取值对象
            _tbl.next().find("label").text(_total)
        }
        
        ,
        prev:function(){
            var _this = $(this)
            var _input = $(this).next()
            var _val = parseInt(_input.val())
            var _min = 0
            _input.val(_val == _min ? _min : (_val - 1))
            vm.setValue(_input) 
        }
        ,
        next:function(max){
            var _max = max || 1 //可在页面传参 注：此件数选择不超过单车可装件数，也不超过可供件数。
            var _this = $(this)
            var _input = $(this).prev()
            var _val = parseInt(_input.val())
            _input.val(_val == _max ? _max : (_val + 1))
            vm.setValue(_input)
        } 
        ,
        prev2:function(){
            var _this = $(this)
            var _input = $(this).next()
            var _val = parseInt(_input.val())
            var _min = 0
            _input.val(_val == _min ? _min : (_val - 1))
        }
        ,
        next2:function(max){
            var _max = max || 1 //可在页面传参 注：此件数选择不超过单车可装件数，也不超过可供件数。
            var _this = $(this)
            var _input = $(this).prev()
            var _val = parseInt(_input.val())
            _input.val(_val == _max ? _max : (_val + 1))
        }
        ,
        modify:function(){
           $(".modifydiv").removeClass('hide')
        }
        ,
        showPhone:function(){
           $(this).next().removeClass('hide')
        }
        ,
        access:function(){
           $(".zm-all .dialog").eq(0).show().next().hide()
           $(".zm-all").show()
        }
        ,
        noaccess:function(){
            $(".zm-all .dialog").eq(0).hide().next().show()
            $(".zm-all").show()
            vm.refobj = $(this)
        }
        ,
        closeDialog:function(){
            $(".zm-all").hide()
        }
        ,
        accesssure:function(){
            $(".zm-all").hide()
        }
        ,
        initXuanzhuangjian:function(){
            require("module/common/hc.popup.jquery")
            $("#dinggou-tip").hcPopup({'width':'815','height':'300','min':true})
        }
        ,
        agreeOrder:function(){
            var _input = $(".popup-content-large input[type='radio']")
            if (_input.length > 0) {
                var _checkedinput = $(".popup-content-large input[type='radio']:checked")
                if (_checkedinput.length == _input.length / 2) {
                    //验证通过
                }else{
                   //没有选择完毕
                }
            }else{
                
            }
        }
        ,
        pdibutie:function(){
            require("module/common/hc.popup.jquery")
            $("#pdi-tip").hcPopup({'width':'400'})
        }
        ,
        surebutie:function(){
            //应该是个ajax操作吧。。。
            $.getJSON("/surebutie/",function(){
                $("#pdi-tip").hide()
            })
        }

        
    });

    $(".area-drop-btn").click(function(){
        $(this).next().toggle()
        return false
    })
    $(document).click(function(e){
        //console.log($(".area-tab-div").css("display"))
        if (e.target.className.indexOf("area-drop-btn") > 0 && $(".area-tab-div").css("display") == "none") {
            $(".area-tab-div").show()
        }
        else if (!$(".area-tab-div").find(e.target)[0] ) {
            $(".area-tab-div").hide() 
        }
        /*else  
        {
            $(".area-tab-div").show() 
        }*/
        //console.log(e.target.className.indexOf("area-drop-btn") > 0)
    })

    $(document).on("click",".goon",function(){
        _add($(this))
    })
    $(document).on("click",".del",function(){
        if ($(this).parents(".ifl").find(".wap").length > 1) {
            if ($(this).next().css("display") == "block") {
                $(this).parents("div.wap").prev().find(".goon").show()
            }
            renum($(this))
        }
    })

    
    function _add(obj){
        var _obj = $(obj)
        var _wap = _obj.hide().parents("div.wap").eq(0).clone()
        var _radio = _wap.find("input[type='radio']")
        //重定义clone出来的radio的名字 不然。。。所有radio相同名字你懂的
        var _length  = _obj.parents(".ifl").find("div.wap").length
        if (_radio[0]) {
            _radio.attr("name","project["+_length+"][selecttime]")
        }
        //添加
        _wap.find(".goon").show().end().appendTo(_obj.parents("div.wap").parent())
        //重定义其他input的name
        $.each(_wap.find("input[type='text']"),function(index,item){
            var _input = $(item)
            var _name = _input.attr("name")
            var _firstindex = _name.indexOf("[") + 1
            var _val = _name.slice(_firstindex,_firstindex+ 1)
            _name = _name.replace(_val,_length)
            _input.attr("name",_name ).val("")
        })
        
        renum(_obj)
    }

    function renum(obj){
      
        var _ifl = $(obj).parents(".ifl")
        if ($(obj).hasClass("del")) {
           $(obj).parents("div.wap").remove() //先定义_ifl 不然元素删后就找不到对象了
        }
        _ifl.find(".wap").each(function(index,item){
            $(item).find(".num").text(index + 1)
        })
    }
    

    function stopBubble(e)
    {
        if (e && e.stopPropagation)
            e.stopPropagation()
        else
            window.event.cancelBubble=true
    }
    var bindClick = function(){
        $(".hasselect i").click(function(){
            $(this).parent().remove()
            vm.setDealer()
        })
    }
    vm.init();

    module.exports = {
        checkxuanzhuangjian:function(){
             vm.initXuanzhuangjian()
        }
    }


});