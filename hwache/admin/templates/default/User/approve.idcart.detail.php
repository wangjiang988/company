<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    .table .required{width: 100px; margin: 0; padding: 0;}
    .tab {width: 100%;border: 1px #ccc solid;}
    .tab td{border: 1px #ccc solid;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>客户实名认证管理</h3>
            <ul class="tab-base">
                <li><a href="<?=url('approve','idcart')?>"><span>管理</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>实名认证</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="idcart_form" action="<?=url('approve','post')?>" method="post" >
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="id" value="<?=$output['find']['id']?>" />
        <input type="hidden" name="user_id" id="user_id" value="<?=$output['find']['user_id'];?>" />
        <input type="hidden" name="ref_url" value="<?php echo getReferer();?>" />
        <table class="table tb-type2">
            <tbody>
            <tr>
                <td width="required">
                    <label for="status">工单号:</label>
                </td>
                <td class="vatop rowform">
                    <?=$output['find']['id']?>
                </td>
                <td width="required">
                    <label for="status">提交时间:</label>
                </td>
                <td class="vatop rowform">
                    <?=$output['find']['created_at']?>
                </td>
            </tr>

            <tr>
                <td width="required">
                    <label for="status">会员号:</label>
                </td>
                <td class="vatop rowform" colspan="3">
                    <?=$output['find']['user_id']?>
                </td>
            </tr>

            <tr class="noborder">
                <td width="required">
                    <label for="real_name">真实姓名:</label>
                </td>
                <td class="vatop rowform" colspan="3">
                    <?=$output['find']['last_name'];?>&nbsp;<?=$output['find']['first_name'];?>
                </td>
            </tr>

            <tr class="noborder">
                <td width="required">
                    <label for="id_cart">身份证号:</label>
                </td>
                <td class="vatop rowform"  colspan="3">
                    <?=$output['find']['id_cart'];?>
                </td>
            </tr>

            <tr>
                <td class="required">
                    <label for="passwrod">身份证图片:</label>
                </td>
                <td class="vatop rowform" colspan="3">
                    <a href="<?=getImgidToImgurl($output['find']['sc_id_cart_img'])?>" target="_blank"><img src="<?=getImgidToImgurl($output['find']['sc_id_cart_img'])?>" width="200" height="150" /></a>
                    <a href="<?=getImgidToImgurl($output['find']['id_facade_img'])?>" target="_blank"><img src="<?=getImgidToImgurl($output['find']['id_facade_img'])?>" width="200" height="150" /></a>
                    <a href="<?=getImgidToImgurl($output['find']['id_behind_img'])?>" target="_blank"><img src="<?=getImgidToImgurl($output['find']['id_behind_img'])?>" width="200" height="150" /></a>
                </td>
            </tr>

            <tr>
                <td class="required" colspan="4" style="background:#8c8c8c; color: #ffffff; margin-left: 5px;"> | <b>审核信息</b></td>
            </tr>
            <tr class="noborder">
                <td class="required"><label class="validation">审核结果</label> </td>
                <td colspan="3">
                    <label><input type="radio" name="status" value="1" />审核通过</label>
                    <label class="validation" >姓：<input type="text" name="last_name" value="<?=$output['find']['last_name'] ?>" class="txt small" /></label>
                    <label class="validation" >名：<input type="text" name="first_name" value="<?=$output['find']['first_name'] ?>" class="txt small" /></label>
                </td>
            </tr>

            <tr class="noborder">
                <td class="required">&nbsp;</td>
                <td colspan="3">
                    <label><input type="radio" name="status" value="2" />审核不通过</label>
                    <label class="validation" >
                        原因：<input type="text" name="reason" id="reason" value="<?=$output['find']['reason'] ?>" class="txt" style="width: 350px;" />
                    </label>
                </td>
            </tr>

            <tr>
                <td class="required">审核留存图片:</td>
                <td colspan="3">
                    <input type="hidden" name='file_path' id='file_path'/>
                    <a href="javascript:void(0);" onclick="show_upload(<?=$output['find']['id']?>,1)"><span class="button">上传</span></a>
                    <input type="file" id="_pic" name="_pic" class="hidden" />
                    <span id="preview_area">
                        
                    </span>
                </td>
            </tr>

            <tr>
                <td class="required">备注:</td>
                <td colspan="3"><textarea name="remark" rows="7" cols="80" ><?=$output['find']['remark']?></textarea></td>
            </tr>

            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td colspan="4" >
                    <button type="button" class="btn" id="submitBtn"><?=$lang['nc_submit']?></button>
                    <a href="<?=url('new_user','list')?>" class="btn"><span>返回</span></a>
                </td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/diy_validate.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/User/edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript">
function show_upload()
{
    $('#_pic').click();
}

$(document).ready(function(){
    $('#_pic').bind("change",uploadChange);

    $("#submitBtn").click(function(){
        validateIdcart();

        var _type = $("input[name='status']:checked").val();
        if(_type ==2){
            $("#reason").rules("add", {
                required: true,
                messages: { required: "请填写审核不通过的原因！"}
            });
        }else{
            $("#reason").removeClass('error').rules("remove");
        }
        set_file_path();
        if($('#idcart_form').valid()){
            $('#idcart_form').submit();
        }
    });
});
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
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("file type error");
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
				url:'index.php?act=common&op=pic_upload2&form_submit=ok&uploadpath=user/judge',
				secureuri:false,
				fileElementId:'_pic',
				dataType: 'json',
				success: function (data, status)
				{
					if (data.status == 1){
                       img_url =  "<?php echo UPLOAD_SITE_URL;?>"+data.url;
                       img_html= "<div class='pure-u-1-4'><img class='shenpi_images' data-id='"+data.image_id+"' style='max-width:300px;' src='"+img_url+"'/><br/><a href='javascript:void(0);' class='button' onclick='remove_with_parent(this);'>移除</a></div>";
                       $("#preview_area").append(img_html);
                       $("#_pic").unbind('change').bind('change',uploadChange);
					}else{
						alert(data.msg);
					}
				}
			}
		)
	};

    function remove_with_parent(ele)
    {
        $(ele).parent().remove();
    }
</script>