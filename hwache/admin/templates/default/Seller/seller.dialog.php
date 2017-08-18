 <style type="text/css">
    .dialog_tips{width: 90%; padding: 10px;}
    .dialog_tips .dialog_ul { width: 100%; list-style: none;}
    .dialog_tips .dialog_ul li{float:left;width: 100%; height: 45px; line-height: 45px; }
    .dialog_tips .dialog_ul .sp{ float:left; width: 100px; height: 40px; line-height: 35px;  text-align: right; display: inline}
    .dialog_tips .dialog_ul .inp{float:left; width: 200px;display: inline}
    .btn {width: 200px; height: 35px; color: #ffffff; background: #ff4f01; border: none; margin-left:100px;}
</style>
<div class="dialog_tips" action="<?=url('seller','addOther')?>" id="otherForm">
    <ul class="dialog_ul">
        <input type="hidden" id="seller_id" value="<?=$output['seller_id'];?>" />
        <li><span class="sp">名称：</span><input class="inp" type="text" id="oth_name" onblur="topTips('名称不能为空','#oth_name')"></li>
        <li><span class="sp">号码：</span><input class="inp" type="text" id="oth_value" onblur="topTips('号码不能为空','#oth_value')"></li>
        <li><button class="btn" type="button" onclick="subOther();"> 提 交 </button></li>
    </ul>
<div>
 <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
 <script>
     function subOther(){
         var _name      = $('#oth_name').val();
         var _val       = $('#oth_value').val();
         var _seller_id = $('#seller_id').val();
         if(_name =='' || _val == ''){
             layer.msg('名称或者号码为空哟！');
             return false;
         }else{
             var _data = {"seller_id":_seller_id,"oth_name":_name,"oth_value":_val};
             var _url  = $("#otherForm").attr('action');
             $.ajax({
                 url:_url,
                 type:'POST',
                 dataType:'json',
                 data:_data,
                 success:function(result){
                    if(result.Success ==1){
                        parent.layerBack(); //再执行关闭
                    }else{
                        layer.msg(result.Msg);
                        return false;
                    }
                 }
             });

         }
     }
     function topTips(msg,obj){
         if($(obj).val() == ''){
             layer.tips(msg, obj);
             //$(obj).focus();
             return false;
         }
     }
 </script>
