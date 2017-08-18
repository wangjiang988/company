<?php defined('InHG') or exit('Access Invalid!');extract($output);?>
<style type="text/css">
    .table .required{width: 100px; margin: 0; padding: 0;}
    .tab {width: 100%;border: 1px #ccc solid;}
    .tab td{border: 1px #ccc solid;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>客户银行账号认证管理</h3>
            <ul class="tab-base">
                <li><a href="<?=url('approve','bank')?>"><span>管理</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>审核</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="bank_form" action="<?=url('approve','bank_post')?>" method="post" >
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="id" value="<?=$data['id']?>" />
        <input type="hidden" name="user_id" id="user_id" value="<?=$data['user_id'];?>" />
        <input type="hidden" name="ref_url" value="<?php echo getReferer();?>" />
        <table class="table tb-type2">
            <tbody>
            <tr>
                <td width="required">
                    <label for="status">工单号:</label>
                </td>
                <td class="vatop rowform">
                    <?=$data['id']?>
                </td>
                <td width="required">
                    <label for="status">提交时间:</label>
                </td>
                <td class="vatop rowform">
                    <?=$data['created_at']?>
                </td>
            </tr>

            <tr>
                <td width="required">
                    <label for="status">会员号:</label>
                </td>
                <td class="vatop rowform" >
                    <?=$data['user_id']?>
                </td>
                <td width="required">
                    <label for="status">类别:</label>
                </td>
                <td class="vatop rowform">
                    <?php $type=['新增','更换']; echo  $type[$data['type']-1]?>
                </td>
            </tr>

            <tr class="noborder">
                <td width="required">
                    <label for="real_name">开户行:</label>
                </td>
                <td class="vatop rowform" >
                     <?="(".$data['bank']['province'].$data['bank']['city'].$data['bank']['district'].")".$data['bank']['bank_address']."";?>
                </td>
            </tr>

            <tr class="noborder">
                <td width="required">
                    <label for="id_cart">账号:</label>
                </td>
                <td class="vatop rowform"  colspan="3">
                    <?=$data['bank_code'];?>
                </td>
            </tr>

            <tr>
                <td class="required">
                    <label for="passwrod">银行卡图片:</label>
                </td>
                <td class="vatop rowform" colspan="3">
                    <a href="<?=getImgidToImgurl($data['bank_img'])?>" target="_blank">
                        <img src="<?=getImgidToImgurl($data['bank_img'])?>" width="200" height="150" />
                    </a>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    if($data['sc_bank_img'] >0){
                    ?>
                    <a href="<?=getImgidToImgurl($data['sc_bank_img'])?>" target="_blank">
                        <img src="<?=getImgidToImgurl($data['sc_bank_img'])?>" width="200" height="150" />
                    </a>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <td class="required" colspan="4" style="background:#8c8c8c; color: #ffffff; margin-left: 5px;"> | <b>审核信息</b></td>
            </tr>
            <tr class="noborder">
                <td class="required"><label class="validation">审核结果</label> </td>
                <td colspan="3">
                    <label><input type="radio" name="status" value="1" />审核通过</label>
                </td>
            </tr>

            <tr class="noborder">
                <td class="required">&nbsp;</td>
                <td colspan="3">
                    <label><input type="radio" name="status" value="2" />审核不通过</label>
                    <label class="validation" >
                        原因：<input type="text" name="reason" id="reason" style="width:300px;" value="<?=$data['reason'] ?>" class="txt" style="width: 350px;" />
                    </label>
                </td>
            </tr>

            <tr>
                <td class="required">审核留存图片:</td>
                <td colspan="3">
                    <input type="hidden" name='file_path' id='file_path'/>
                    <a href="javascript:void(0);" onclick="show_upload(<?=$data['id']?>,1)"><span class="button">上传</span></a>
                    <input type="file" id="_pic" name="_pic" class="hidden" />
                    <span id="preview_area">

                    </span>
                </td>
            </tr>

            <tr>
                <td class="required">备注:</td>
                <td colspan="3"><textarea name="remark" rows="7" cols="80" ><?=$data['remark']?></textarea></td>
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/User/approve.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript">
function show_upload()
{
    $('#_pic').click();
}

$(document).ready(function(){
    $('#_pic').bind("change",uploadChange);

    $("#submitBtn").click(function(){
        validateBank();
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
        if($('#bank_form').valid()){
            $("#bank_form").submit();
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