<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>工单管理</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=work_sheet&op=index"><span>工单管理</span></a>▷</li>
                <li><a class="current"><span>新建内部工单</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="post"  id="ajax_form" name="ajax_form">
        <input type="hidden" name="act" value="work_sheet">
        <input type="hidden" name="op" value="create">
        <input type="hidden" name="form_submit" value="ok">

        <div class="pure-g line">
            <div class="pure-u-1-5">
                工单对象:
            </div>
            <div class="pure-u-1-5">
                平台
            </div>
            <div class="pure-u-3-5">
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-5">
                <span style="color:red;">*</span>跟进处理部门:
            </div>
            <div class="pure-u-4-5">
                <input type="hidden" name="dept" value="0">
                <?php if($dept_list){ ?> 
                    <?php foreach($dept_list as $dept){ ?>
                        <span style="margin-right:10px;">
                            <input id="dept_<?=$dept['id']?>" class="dept" name="dept_check" type="radio" value="<?=$dept['id']?>"><label for="dept_<?=$dept['id']?>"><?=$dept['name']?></label>                      
                            <select name="" id="">
                                <option value="">普通权限</option>
                                <option value="">高级权限</option>
                            </select>
                        </span> 
                    <?php }?>
                <?php }?>
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-5">
                <span style="color:red;">*</span>主题:
            </div>
            <div class="pure-u-4-5">
                    <input type="text" name='subject'>
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-5">
                问题描述:
            </div>
            <div class="pure-u-4-5">
                    <textarea name="description" id="" cols="60" rows="20"></textarea>
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-5">
                证据:
            </div>
            <div class="pure-u-4-5">
                <input type="hidden" name="upload_files" id="file_path">
                <a href="javascript:show_upload();" class="button">点击上传</a>
                <input type="file"  id="_pic" name="_pic" data-type="audio" data-path="work_sheet/log" class="hidden">
                <span id="preview_area">
                        
                </span>
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-5">
                外部关联方:
            </div>
            <div class="pure-u-4-5">
                <div class="line" style="line-height:30px;">
                    <input type="checkbox" id="user_check" value="1"/><span>客户手机</span> <input readonly type="text" name="user_phone"/>
                </div>
                <div class="line">
                    <input type="checkbox" id="seller_check" value="1"/><span>售方用户名</span> <input readonly type="text" name="seller_name"/>
                </div>
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-4-5">
                <a href="javascript:void(0);" id="submitBtn" class="button"> 提交新工单</a>
                <a href="javascript:history.go(-1);" class="button">取消</a>
            </div>
        </div>
    </form>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/upload.common.js" charset="utf-8"></script>

<script type="text/javascript">
$(function(){
       $(".dept").click(function(){
            $("input[name='dept']").val($(this).val());
       });

       $("#user_check").change(function(){
           if($(this).is(":checked"))
           {
               $("input[name='user_phone']").removeAttr('readonly');
           }else{
                $("input[name='user_phone']").attr('readonly','readonly');
           }
       });

       $("#seller_check").change(function(){
           if($(this).is(":checked"))
           {
               $("input[name='seller_name']").removeAttr('readonly');
           }else{
                $("input[name='seller_name']").attr('readonly','readonly');
           }
       })
});

 function form_checked()
 {
    let json  = $('#ajax_form').serializeJSON();
    if(json.dept == 0){
        alert("请选择跟进部门");
        return false;
    }

    if(json.subject.trim() =="")
    {
        alert("请输入主题");
        return false;
    }

    return true;
 }
</script>
