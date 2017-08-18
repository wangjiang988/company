
define(function (require) {

    require("jq");//

    var vm = avalon.define({
        $id: 'item',
        init: function () {
            
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
    
    });

    vm.init();

    $(".formItemDiff").mouseover(function() {
        $(this).addClass("sele").prevAll().addClass("sele")
        $(this).nextAll().removeClass("sele")
        $(this).find(".cot").show()
        $(this).parent().find("input[type='hidden']").val($(this).prevAll().length+1);
    }).mouseout(function() {
        $(this).find(".cot").hide()
    });

});