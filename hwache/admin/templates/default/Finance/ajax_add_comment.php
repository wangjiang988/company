<?php defined('InHG') or exit('Access Invalid!');?>
<form method="get" name="ajax_form" id="ajax_form">
    <input type="hidden" name="act" value="finance">
    <input type="hidden" name="op" value="ajax_set_status">
    <input type="hidden" name="ur_id" value="<?php echo $output['ur_id']?>">
    <input type="hidden" name="file_path" id="file_path" value="">
    <input type="hidden" name="file_name" id="file_name" value="">
    <input type="hidden" name="operation_type" id="operation_type" value="<?php echo $output['operation_type'];?>">
    <input type="hidden" name="status"  value="<?php echo $output['recharge']['status'];?>">
    <input type="hidden" name="in_ajax" value="1">
    <div class="info" style="padding: 10px;">
    <table class="table tb-type2">
        <tbody>
        <tr>
            <td colspan="2" class="required"><label for="site_email">备注内容:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                <textarea name="remark" id="" cols="30" rows="10"></textarea>
            </td>
            <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label for="site_logo">上传证据:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                <span class="type-file-box"><input type='text' name='textfield' id='textfield1'
                                                   class='type-file-text'/>
                    <input type='button' name='button' id='button1' value='' class='type-file-button'/>
                    <input name="comment" type="file" class="type-file-file" id="comment" size="30" hidefocus="true"
                            nc_type="change_site_logo">
                </span>
            </td>
            <td class="vatop tips"><span class="vatop rowform">图片或音频文件格式(可重复添加多个文件)</span></td>
        </tr>
        <tr>
            <td id="file_list">

            </td>
        </tr>
        </tbody>
    </table>
    </div>
    <div class="panel_footer" style="padding: 10px;text-align: center;">
        <span class="label">
            <a href="javascript:void(0);"  onclick="addComment();" class="button">提交</a>
            <a href="javascript:void(0);" onclick="closeLayer();" class="button">取消</a>
        </span>
    </div>
</form>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>

<script type="text/javascript">
 $(function(){
     $("#comment").change(function(){
         $("#textfield1").val($(this).val());
     });
     // 上传语音类型
     $('input[class="type-file-file"]').change(uploadChange);

 });

 function uploadChange(){
     var filepatd=$(this).val();
     var extStart=filepatd.lastIndexOf(".");
     var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();
     if(ext!=".WAV"&&ext!=".MP3"&&ext!=".WMA"&&ext!=".OGG"&&ext!=".ACC"&&ext!=".AAC"&&ext!=".APE"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".BMP"&&ext!=".JPEG"&&ext!=".PNG"&&ext!=".tbi"){
         alert("<?php echo  "非图片或音频文件格式"?>");
         $(this).attr('value','');
         return false;
     }
     if ($(this).val() == '') return false;
     ajaxFileUpload();
 }

 function ajaxFileUpload()
 {
     $.ajaxFileUpload
     (
         {
             url:'index.php?act=common&op=audio_upload&form_submit=ok&uploadpath=common',
             secureuri:false,
             fileElementId:'comment',
             dataType: 'json',
             success: function (data, status)
             {
                 if (data.status == 1){
//                     alert("上传成功");
//                     $('#file_name').val(data.file_name);
//                     $('#file_path').val(data.url);
                     let file = data.url.split('.');
                     file = file[0];
                     let file_name = file.split('/')[2];
                     let a_ele ="<p id='file_"+file_name+"'>";
                     a_ele  +=  '<a class="file_list" data-name="'+data.file_name+'" href="'+data.url+'" >'+data.file_name+'</a>   <a href="javascript:void(0);" onclick="cancel_file(\''+data.url+'\',\''+file_name+'\');">取消上传</a>';
                     a_ele  +='</p>';
                     $("#file_list").append(a_ele);
                 }else{
                     alert(data.msg);
                 }
                 $('input[class="type-file-file"]').bind('change',uploadChange);
             },
             error: function (data, status, e)
             {
                 alert('upload failed');
                 $('input[class="type-file-file"]').bind('change',uploadChange);
             }
         }
     )
 };

 function cancel_file(file_url, file_name){
        $("#file_"+file_name).remove();
         post('index.php?act=finance&op=ajax_cancel_file', {"file_url":file_url}).then(function(res){
         if(res.data.code == 200)
         {

         }else
         {
             alert(res.data.msg);
         }
     }).catch(function(err){
         console.log(err);
     });
 }

    function addComment()
    {
        files_path = getAllFileInfo();
        $('#file_path').val(files_path);

        let json= $('#ajax_form').serializeJSON();

        post('index.php?act=finance&op=ajax_add_comment', json).then(function(res){
            if(res.data.code == 200)
            {
                alert(res.data.msg);
                refresh_workspace();
                closeLayer();
            }else
            {
                alert(res.data.msg);
            }
        }).catch(function(err){
            console.log(err);
        });
    }

    function getAllFileInfo()
    {
        let file_string = '';
        $(".file_list").each(function(){
            _this = $(this);
            file_string += _this.data('name')+','+_this.attr('href')+"|";
        });
        s=  file_string.substring(0,file_string.length -1);
        return s;
    }
</script>