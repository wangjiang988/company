
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
        refobj:null
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
        selectProvince:function(){
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
             var $this = $(this)
             var _fileinputlist = $this.next()
             var _file = $("<input type='file' class='hide' name='FileUpload' />")
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
        accessandredirect:function(){
            window.location.href="/4.2.4B调解OK继续交车页面.html"
        }
        ,
        noaccessanddosame:function(){
            $(".zm-all").hide()
            vm.refobj.addClass("hide").next().removeClass('hide')
        }
        ,
        postzhengju:function(){
            
            var _fileinputlist = $(".fileinputlist input")
            if (_fileinputlist.length != 0) {
                var _agree = $("#ck-agree")
                var _error = $("#agree-error")
                if (_agree.prop("checked")) {

                    var zhengjuform = $("form[name='zhengjuform']")
                    var options = {

                        success: function (data) {
                            $(".loading6:eq(0)").fadeOut(300)
                        }
                        ,
                        beforeSubmit:function(){

                            $(".loading6:eq(0)").fadeIn(300)
                        }
                        ,
                        clearForm:true
                    }
                    // ajaxForm 
                    zhengjuform.ajaxForm(options) 
                    // ajaxSubmit
                    zhengjuform.ajaxSubmit(options) 

                }else{
                    _error.removeClass('hide').show()
                    setTimeout(function(){
                        _error.addClass('hide').hide()
                    },3000)
                }
            }else{
                //没有文件不做任何处理
                $("#fileerror").removeClass('hide')
                setTimeout(function(){
                    $("#fileerror").addClass('hide')
                },3000)
            } 

            
        }
        ,
        postmediate:function(){
            
            var _mediate= $("textarea[name='mediate']")
            if ($.trim(_mediate.val())!="") {
                
                var mediateform = $("form[name='mediateform']")
                var options = {

                    success: function (data) {
                        $(".loading6:eq(1)").fadeOut(300)
                    }
                    ,
                    beforeSubmit:function(){
                        $(".loading6:eq(1)").fadeIn(300)
                    }
                    ,
                    clearForm:true
                }
                // ajaxForm 
                mediateform.ajaxForm(options) 
                // ajaxSubmit
                mediateform.ajaxSubmit(options) 

                
            }else{
               
                $("#mediateerror").removeClass('hide')
                setTimeout(function(){
                    $("#mediateerror").addClass('hide')
                },3000)
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

    vm.init();
    
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

});