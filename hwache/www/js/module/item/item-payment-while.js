
define(function (require) {

    require("jq");//
    require("vendor/jquery.form");//
    var vm = avalon.define({
        $id: 'item',
        init: function () {
            
        }
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
        edit: function () { 
            $(this).prev().show().focus()
        }
        ,
        edit2: function () { 
            var _prev = $(this).prev()
            var _label = $(this).prev().prev()
            _prev.show().val(_label.text()).focus()
            _label.hide()
            //$(this).prev().show().focus().prev().hide()
        }
        ,
        displayTm: function () {
            $(this).next().fadeIn(300)
        }
        ,
        hideTm: function () {
            $(this).next().hide()
        }
        ,
        selectTime:function(){
            $(this).parent().find("li").removeClass("active").end().end().addClass("active").parent().find("input[type='hidden']").val($(this).text()).parent().prev().find(".dropdown-label span").text($(this).text())
        }
        ,
        rili:function(){  
            require("vendor/DatePicker/WdatePicker")
           
            $(this).prev().focus()
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
      }
        ,
        submit_form:function(){
            var _check = true;
            //检查是否有部分内容没有确认
            $("a[name*='bt_sure_']").each(function(){
                if($(this).css("display") != "none"){
                    var _id = $(this).attr("name").replace(/[^0-9]/g,'');
                    _check = false;
                    alert("此区域您还没有确认，请先确认然后再提交");
                    var _top = $("a[name='bt_sure_"+_id+"']").offset().top - 300;
                    $('html,body').animate({scrollTop: _top}, 500);
                    return false
                }
            })
            if(_check == true){
               //检查勾选协议
               var _ck =$(this).parent().next().find("input[type='checkbox']")
               if (!_ck.prop("checked")) {
                   _check = false;
                   alert("请勾选'我已接受上述实际状况的车辆、附加品、文件、服务',才能进行提交");
                   return false;
               }
               if(_check == true){
                $("form[name='item-form']").submit();
                }
            }
            
            
        }
        ,
        access:function(){
            
            $(".zm-all").show()
            $(".dialog-access").show()
            $(".dialog-no-access").hide()
        }
        ,
        accessandredirect:function(){
            $("input[name=result]").val(1)
            $("#tiaojie_from").submit()
        }
        ,
        noaccess:function(){
            
            $(".zm-all").show()
            $(".dialog-access").hide()
            $(".dialog-no-access").show()
        }
        ,
        noaccessanddosame:function(){
            $("input[name=result]").val(0)
            $("#tiaojie_from").submit()
        }
        ,
        closeDialog:function(){
            $(".zm-all").hide()
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
            if($("input[name='chepai[]']").length>0 && $("input[name='chepai[]'][value!='']").length != 7){
                alert('请填写完整的车牌号码');
                return false;
            }
            $("form[name=item2]").submit();
        }


    });

    vm.init();
    
     $(".area-drop-btn").click(function(){
         //此判断添加 是为了防止失效
         /**
        if($(".area-tab-div").css("display") == "none"){
             $(".area-tab-div").show();
        }else{
            $(".area-tab-div").hide() ;
        }
        **/
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
       
    })

    $(".tbl input[type='radio']").click(function(){
        var _index = $(this).index()
        var _parent = $(this).parents("td.cell")
        
        if (_index == 0) {
            if (_parent.find("table.tbl2")[0]) {
                var _tr = $(this).parents("tr").index()
                _parent.next().find("tr").eq(_tr).find("span.edit").css("visibility","hidden").prev().hide();
            }else{
                _parent.next().find("span.edit").css("visibility","hidden").prev().hide()
            }
        }
        else if (_index == 2) {
            if (_parent.find("table.tbl2")[0]) {
                var _tr = $(this).parents("tr").index()
                _parent.next().find("tr").eq(_tr).find("span.edit").css("visibility","visible")
            }else{
                _parent.next().find("span.edit").css("visibility","visible")
            }
            
        }
    });
     $("#modifyShangpaiButton").click(function(){
         var shangpaiTime = $("input[name='shangpai_time']").val();
         if(shangpaiTime == ""){
            alert("如需提交上牌时间，请先选择正确的上牌日期");
                return false;
        }
         $("#form_shangpai").submit();
         
     })
     $("a[name*='bt_sure_']").click(function(){
        var _name = $(this).attr("name"); 
        var _id = _name.replace(/[^0-9]/g,'');
        $(this).hide();
        $("a[name=bt_has_sure_"+_id +"]").show();
        $("a[name=bt_modify_"+_id +"]").show();
     
     })
     $("a[name*='bt_modify_']").click(function(){
        var _name = $(this).attr("name"); 
        var _id = _name.replace(/[^0-9]/g,'');
        $("a[name=bt_has_sure_"+_id +"]").hide();
        $(this).hide();
        $("a[name=bt_sure_"+_id +"]").show();
     
     })
});