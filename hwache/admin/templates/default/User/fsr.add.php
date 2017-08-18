<style type="text/css">
    .tabBank{width:100%; margin-top: 30px;}
    .tabBank tr{ height: 40px; padding:5px; }
    .tabBank td{height: 40px; padding-left:15px; text-align: left; font-size:16px;font-weight: bold; }
    .tabBank td.centers{text-align: center;}
</style>
<form action="<?=url('new_user','addFreeze')?>" id="otherForm">
    <table class="tabBank">
        <tr>
            <td><?=$output['find']['title']?>：</td>
        </tr>
        <tr>
            <input id="type" type="hidden" value="<?=$output['find']['type']?>" />
            <td><input id="value" type="text" style="width: 90%" /> </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td class="centers">
                <a href="javascript:;" class="btn" onclick="subOther()"><span>确定</span></a>
                <a href="javascript:;" class="btn" onclick="backUrl()"><span>返回</span></a></td>
        </tr>
    </table>
</form>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script>
    var _title = '<?=$output['find']['title']?>';
    function subOther(){
        var _type      = $('#type').val();
        var _val       = $('#value').val();
        if(_val == ''){
            layer.msg(_title+'为空哟！');
            return false;
        }else{
            var _data = {"type":_type,"value":_val};
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

    function backUrl(){
        parent.layerBack(); //再执行关闭
    }

    function topTips(msg,obj){
        if($(obj).val() == ''){
            layer.tips(msg, obj);
            //$(obj).focus();
            return false;
        }
    }
</script>