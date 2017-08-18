$(function(){
        $('#_pic').bind("change",uploadChange);

        $("#submitBtn").click(function(){
            set_file_path();
            if(form_checked()){
                //询问框
                layer.confirm('确定提交么？', {
                btn: ['确定','取消'] //按钮
                }, function(){
                    $("#ajax_form").submit();
                }, function(){
                    
                });
            }
        });
});

function show_upload()
{
    $('#_pic').click();
}

function set_file_path(){
    $ids = '';
    $(".shenpi_images").each(function(){
        $ids += $(this).data('id')+',';  
    });
    console.log($ids);
    $("#file_path").val($ids);
}

function uploadChange(){
    var filepatd=$(this).val();
    var extStart=filepatd.lastIndexOf(".");
    var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();
    if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG" &&ext!=".WAV"&&ext!=".MP3"&&ext!=".OGG"&&ext!=".ACC"){
        alert("file type error");
        $(this).attr('value','');
        return false;
    }
    if ($(this).val() == '') return false;
    _this = $(this);

    ajaxFileUpload(_this.data('type'), _this.data('path'));
}

function ajaxFileUpload(type, path)
{
    _url = 'index.php?act=common&op=pic_upload2&form_submit=ok&uploadpath='+path;
    
    if(type=='audio')
        _url =  'index.php?act=common&op=audio_upload2&form_submit=ok&uploadpath='+path;
    $.ajaxFileUpload
    (
        {
            url:_url,
            secureuri:false,
            fileElementId:'_pic',
            dataType: 'json',
            success: function (data, status)
            {
                if (data.status == 1){
                    img_url =  data.url;
                    img_html= "<div class='pure-u-1-4'><a class='shenpi_images' href='"+img_url+"' data-id='"+data.image_id+"' style='max-width:300px;'>"+data.file_name+"</a>"+
                    "<a href='javascript:void(0);'  onclick='remove_with_parent(this);'><i class='fa fa-window-close' aria-hidden='true'></i></a>"+
                    "</div>";
                    $("#preview_area").append(img_html);
                  
                }else{
                    alert(data.msg);
                }
                $("#_pic").unbind('change').bind('change',uploadChange);
            }
        }
    )
};

function remove_with_parent(ele)
{
    $(ele).parent().remove();
}

