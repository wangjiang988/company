
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
        modifyFwzy: function () {// 允许修改服务专员时  可供选择的服务专员列表被显示出来
            if($("#fuwuyuan").css("display") == "none"){
                $("#fuwuyuan").css("display","block");
            }else{
                $("#fuwuyuan").css("display","none");
            }
        }
        ,
        modifyFwzyChange: function () {//服务专员更改选择时  展示对应的信息如电话 手机
            if($("#fuwuyuan").val() == 'none'){
                $("#fwzyName").text('');
                $("#fwzyMobile").text('');
                $("#fwzyTel").text('');
            }else{
                var valStrArr = $("#fuwuyuan").val().split("_");
                $("#fwzyName").text(valStrArr[0]);
                $("#fwzyMobile").text(valStrArr[1]);
                $("#fwzyTel").text(valStrArr[2]);
            }
            
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
        uploadForMuliteFileInput:function(){
             var _num = $("input[name='file[]']").length
             if(_num>=5){
                 alert("最多上传5张图片")
                 return false
             }
              var $this = $(this)
              var _fileinputlist = $this.next()
              var _file = $("<input type='file' class='hide' name='file[]' />")
              _file.bind("change",function(){
                 var _val = $(this).val()
                 $this.prev().append("<span class='file-prev'>"+_val.substring(_val.lastIndexOf('\\')+1)+"</span>")
              })
              _fileinputlist.append(_file.click())
            
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
       },
       changesingle:function(){
           var $this = $(this)
           var _val = $this.val()
           $this.prev().prev().html("<span class='file-prev'>"+_val.substring(_val.lastIndexOf('\\')+1)+"</span>")
           $("#hfFile").val(
               $this.val()
              )
     },
       send_invite:function(){
           var _this = $(this)
           var _date = $("input[name='pdi_date_dealer']").val();
           if(_date == ''){
               alert('请为提交客户可选择的交车日期');
               return false
           }
           var _ck =_this.next().find("input[type='checkbox']")
           if (_ck.prop("checked")) {
               $("form[name='item-form']").submit()
           }else{
               alert("请勾选'预备移交的车辆和文件已检查完毕，确认符合上述约定内容',才能进行提交")
           } 
       }
       ,
       send_edit_before_invite:function(){
           var _this = $(this)
           var _ck =_this.parent().next().find("input[type='checkbox']")
           if (_ck.prop("checked")) {
               $("form[name='item-form-edit']").submit()
           }else{
               alert("请勾选'同意支付歉意金和客户买车担保金利息赔偿',才能进行提交")
           } 
       }
       ,
       send_modify_before_sure_earnest:function (){
           var _this = $(this)
           var _ck =_this.parent().next().find("input[type='checkbox']")
           if (_ck.prop("checked")) {
               $("form[name='form1']").submit()
           }else{
               alert("请勾选'同意支付歉意金赔偿',才能进行提交")
           } 
       }
       ,
       send_other_info_pdiok:function(){
           var _this = $(this)
           if($("#fuwuyuan").val() == 'none'){
               alert("请为此订单选择一个服务专员吧，好让订单继续下去");
               return false;
           }
           $("form[name='item_pdiok']").submit()
       }
       ,
       accessandredirect:function(){
        $("input[name=result]").val(1)
        $("#tiaojie_from").submit()
       }
 
       ,
       noaccessanddosame:function(){
        $("input[name=result]").val(0)
        $("#tiaojie_from").submit()
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
                        url: "/saveuserxzj/",
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
        },
        send_notice:function(){
            var _this = $(this)
            if($("input[name='pdi_date_dealer']").val()==''){
                alert("您还没有选择交车日期供客户挑选");
                return false;
            }
            var _ck =_this.next().find("input[type='checkbox']")
            if (_ck.prop("checked")) {
                $("form[name='item-form-notice']").submit()
            }else{
                alert("请勾选'预备移交的车辆和文件已检查完毕，确认符合上述约定内容',才能进行提交")
            } 
        }
        ,
        submit_tiche_end:function(){
            if($("input[name=sheng]").val() == '' || $("input[name=shi]").val() == ''){
                alert('请选择上牌地区');
                return false;
            }
            if($("input[name=yongtu]").val() == '' ){
                alert('请选择车辆用途');
                return false;
            }
            if($("input[name=reg_name]").val() == '' ){
                alert('请填写正确的上牌（注册登记）车主名称');
                return false;
            }
            if($("input[name='chepai[]'][value!='']").length != 7){
                alert('请填写完整的车牌号码');
                return false;
            }
            $("form[name=item-form]").submit();
        }
        ,
        pdibutie:function(){
            require("module/common/hc.popup.jquery")
            $("#pdi-tip").hcPopup({'width':'400'})
        }
        ,
        surebutie:function(){
            require("vendor/jquery.form");//
             var surebutieform = $("form[name='surebutieform']")
             var options = {

                 success: function (data) {
                   if(data==0){
                       alert('补贴更新成功');
                       setTimeout("window.location.reload()",1000);
                   }else{
                       alert('补贴更新失败');
                   }
                 }
                 ,
                 beforeSubmit:function(){
                     if(surebutieform.find('input[name="pdi_butie_file"]').val() == ''){
                         alert('必须上传补贴发放证据');
                         return false;
                     }
                     ///$(".loading6:eq(0)").fadeIn(300)
                 }
                 ,
                 clearForm:true
             }
             // ajaxForm 
             surebutieform.ajaxForm(options) 
             // ajaxSubmit
             surebutieform.ajaxSubmit(options) 
        }
        ,
        sub_jiaoche_ext:function(form_name){
            require("vendor/jquery.form");//
            var _form = $("form[name='"+form_name+"']")
            var options = {

                success: function (data) {
                  if(data==0){
                   alert('补充材料提交成功');
                   setTimeout("window.location.reload()",1000);
                  }else{
                   alert('补充材料提交失败');
                  }
                }
                ,
                beforeSubmit:function(){
                    if(_form.find('input[name="file[]"]').length ==0){
                        alert('补充材料不能为空');
                    }else{
                         _form.find('input[name="file[]"]').each(function(){
                             if($(this).val()==''){
                                 alert('补充材料不能为空');
                             }
                         })
                    }
                }
                ,
                clearForm:true
            }
            _form.ajaxForm(options) 
            _form.ajaxSubmit(options) 
        }
        ,
        agreeOrder:function(){
            var _input = $(".popup-content-large input[type='radio']")
            if (_input.length > 0) {
                var _xzj = []
                    //验证通过
                    
                    $.each($("input[name^='xzj['][type=radio]:checked"),function(index,item){ 
                        
                        var _item = $(item)
                        var _model = new model()
                        _model.id           = _item.attr("name")
                        _model.value        = _item.val()
                        _xzj.push(_model)                        
                    })
                    //提交
                    $.ajax({
                            url: "/dealer/savexzj",
                            type: "post",
                            dataType: "json",
                            data: $("form[name=rsync_xzj_form]").serialize(),
                            beforeSend: function () {
                                
                            }
                            ,
                            success: function (data) {
                                var _error_code = data.error_code;
                                var _error_msg = data.error_msg;
                                //console.log(_error_msg) //开发版请注释这行
                                if (_error_code == 0) {
                                    $("#dinggou-tip").hide();
                                   alert("数据更新成功");
                                   window.location.reload();
                                } else {
                                    alert(_error_msg)
                                }
                                
                            }
                    });
                
            }else{
                
            }
        }
        ,
        pdi_agree_calc:function(order_num,token){//代理同意结算
             $.ajax({
                 url: "/dealer/agreecalc",
                 type: "post",
                 dataType: "json",
                 data: {order_num:order_num,_token:token},
                 success: function (data) {
                     var _error_code = data.error_code;
                     var _error_msg = data.error_msg;
                     if (_error_code == 0) {
                        alert("数据更新成功");
                        window.location.href='/dealer/jchjhandleprocedures/'+order_num;
                     } else {
                        alert(_error_msg)
                     }
                     
                 }
         });
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
            _radio.attr("name","project["+_length+"][effect]")
        }
        //添加
        _wap.find(".goon").show().end().appendTo(_obj.parents("div.wap").parent())
        renum(_obj)
       
        //重定义其他input的name
        $.each(_wap.find("input[type='text']"),function(index,item){
            var _input = $(item)
            var _name = _input.attr("name")
            var _firstindex = _name.indexOf("[") + 1
            var _val = _name.slice(_firstindex,_firstindex+ 1)
            _name = _name.replace(_val,_length)
            _input.attr("name",_name ).val("")
            
        })
       
    }
/**
    function _add(obj){
        var _obj = $(obj)
        var _wap = _obj.hide().parents("div.wap").eq(0).clone()
        var _radio = _wap.find("input[type='radio']")
        //重定义clone出来的radio的名字 不然。。。所有radio相同名字你懂的
        if (_radio[0]) {
            var _length  = _obj.parents(".ifl").find("div.wap").length
            _radio.attr("name","jiaoche"+(_length + 1))
        }
        //添加
         _wap.find(".goon").show().end().appendTo(_obj.parents("div.wap").parent())
        renum(_obj)
    }
**/
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
    function modifyZhuanyuan(){
        $("#fuwuyuan").css("display","");
    }
    
    
});