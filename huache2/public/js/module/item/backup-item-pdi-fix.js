﻿
define(function (require) {

    require("jq");//

    var vm = avalon.define({
        $id: 'item',
        istuijian:true
        ,
        init: function () {
            
        }
        ,
        zerenxian:{x:0,y:0,z:0}
        ,
        toggleContent: function () {
           
        }
        ,
        rili:function(){  
            require("vendor/DatePicker/WdatePicker")
            /*console.log(111111111111)
            $(this).date_input();*/
            $(this).prev().focus()
        }
        ,
        selectTime:function(){
            $(this).parent().find("li").removeClass("active").end().end().addClass("active").parent().find("input[type='hidden']").val($(this).text()).parent().prev().find(".dropdown-label span").text($(this).text())
        }
        ,
        SolicitationTime:function(){
            var _this = $(this)
            var _disable ="disable"
            if (_this.hasClass(_disable)) {
                return false
            }
            var _index = _this.index()
            var _className = "cur-select"
            var _nextClassName = ["select-next","select-prev"]
            _this.addClass(_className).siblings().removeClass(_className).removeClass(_nextClassName[0]).removeClass(_nextClassName[1])
            if (_index == 1) {
                _this.next().addClass(_nextClassName[0])
            }else{
                _this.next().addClass(_nextClassName[0]).end().next().addClass(_nextClassName[0])
            } 
            _this.parent().find("input[type='hidden']").val(_this.text())
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
                _this.parent().next("input").removeClass("hide")
            }else{
                _this.next().addClass(_nextClassName[0]).end().next().addClass(_nextClassName[0])
                _this.parent().next("input").addClass("hide")
            } 
            _this.parent().prev("input[type='hidden']").val(_this.text())

        }
        ,
        huji:function(){
            var _this = $(this)
            var _className = "cur-select"
            _this.siblings().removeClass(_className).end().addClass(_className).parent().find("input[type='hidden']").val(_this.text()).parent().prev().prev().prop("checked","checked")
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
        baojia:function(){
            var _this = $(this) 
            _this.parents(".baoxian").show().next().hide()
        }
        ,
        baojia2:function(){
            var _this = $(this) 
            _this.parents(".baoxian").hide().next().show().find("td").slice(1).find("input").attr("disabled","disabled")
            _this.removeAttr("checked")
        }
        ,
        baojia3:function(){
            var _this = $(this) 
            _this.parents(".baoxian").hide().prev().show()
            _this.removeAttr("checked")
        }
        ,
        baojia4:function(){
           
        }
        ,
        bxself:function(val){
           if (isNaN(val)) {
              if (!$(this).prop("checked")) {
                 $(this).parent().next().find("input").removeAttr("checked").end().next()
                 .find("p").show().next().hide().text("").end().end()
                 .next().html("")
              }
           }else{
              val = val + " \u5143" 
              if (!$(this).prop("checked")) {
                 val = ""
                 $(this).next().find(".pdi-tip").hide()
              }else{

                if ($(this).hasClass("fjx")) {

                    var _obj = $("."+$(this).attr("for"))
                    var _flag = true
                    var _flag2 = true 
                    if (_obj.prop("checked")) {
                        _flag = false
                    }  
                    $.each(_obj.parents("td").next().find("input"),function(index,item){
                        
                        if ($(item).prop("checked")) {
                            _flag2 = false 
                            return
                        }
                        _flag = true
                    }) 
                    if (_flag && _flag2) {
                        $(this).next().find(".pdi-tip").show()
                    }
                    
                }

              }
              var _td = $(this).parents("td").next().next().next()
              if (_td.find(".tongji-ry")[0]) {
                _td.find(".tongji-ry").show()
              }else{
                _td.html(val)
              }
              //tongji-ry
              
           }
           
        }
        ,
        bxself2:function(val){
           if (isNaN(val)) {
              if (!$(this).prop("checked")) {
                 $(this).parent().next().find("input").removeAttr("checked").end().next()
                 .find("p").show().nextAll().hide().text("")
                 $(this).parent().parent().find(".cts").text("")
              }
           }
           
        }
        ,
        selectBX:function(){
          var _this = $(this)
          var _val = $(this).attr("data-bind") + " \u5143" 
          _this.parent().prev().find("input[type='checkbox']").prop("checked","checked")
          _this.parent().next().find("p").hide().next().show().text(_val).end().end()
          .next().html(_val)
          
        }
        ,
        selectBX2:function(){
          var _this = $(this)
          var _val = $(this).attr("data-bind") + " \u5143" 
          _this.parents("td").next().find("table tr:eq(0) td").find("p").hide().next().show().text(_val)
          _this.parents("td").next().next().find("table tr:eq(0) td").html(_val)
          vm.zerenxian.z = parseInt($(this).val())
        }
        ,
        selectBXBoth:function(){
          var _this = $(this)
          var _v1,_v2,_vx

          if ($(this).attr("data-bind-type") == "1") {
             _v1 = $(this).attr("data-bind") 
             vm.zerenxian.x = parseInt($(this).val())
          }
          else if ($(this).attr("data-bind-type") == "2") {
            _v2 = "/" + $(this).attr("data-bind") 
            vm.zerenxian.y = parseInt($(this).val())
          }
          _vx = vm.zerenxian.x * vm.zerenxian.y 
          _this.parents("td").next().find("tr:eq(1) td p").hide().next().show().text(_v1).next().show().text(_v2)
          _this.parents("td").next().next().find("tr:eq(1) td ").text(
            _vx == 0 ? "" : _vx
           )
           
        }
        ,
        shangpai:function(){
           $("table.sp").hide().eq($(this).parent().index()).show()
        }
        
        
    });

    vm.init();
    $(window).scroll(function(){
      //console.log($(window).scrollTop())
      if ($(window).scrollTop() > 4713  ) {
         $(".btn-save").removeClass("btn-save")
      }else{
          $(".btn-fl").addClass("btn-save")
      }
    })

});