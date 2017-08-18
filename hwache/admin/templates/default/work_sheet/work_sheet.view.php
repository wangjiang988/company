<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>工单管理</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=work_sheet&op=index"><span>工单管理</span></a>▷</li>
                <li><a class="current"><span>查看工单</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <div class="pure-g line">
        <div class="pure-u-1-3">
            <span>工单编号: </span><span><?=$data['id'];?></span>
        </div>
        <div class="pure-u-1-3">
             <span>工单时间: </span><span><?=$data['created_at'];?></span>
        </div>
        <div class="pure-u-1-3">
           <span>工单提交人: </span><span><?=$data['creator'];?></span>
        </div>
    </div>

    <div class="pure-g line">
        <div class="pure-u-1-3">
            <span>工单对象: </span><span>平台</span>
        </div>
        <div class="pure-u-1-3">
             <span>处理部门: </span><span><?=$data['current_handle_dept']['name'];?>普通权限</span>
        </div>
        <div class="pure-u-1-3">
           <span>状态: </span><span>
                <?php
                     if(in_array($data['status'],[0,10])) echo "待接单";
                     elseif($data['status'] == 1 ) echo "处理中";
                     elseif($data['status'] == 2 ) echo "已完结";
                     else echo  "未知状态";
                ?></span>
        </div>
    </div>

    <div class="pure-g line">
        <div class="pure-u-1">
            <span>主题: </span><span><?=$data['subject']; ?></span>
        </div>
    </div>

     <div class="pure-g line">
         <div class="pure-u-1">
            <span>证据: </span><span>
                <?php if(isset($data['files']) && count($data['files'])>0){ ?>
                    <?php foreach($data['files'] as $file) { ?>
                            <div class="pure-u-1-4">
                                <a href="<?=UPLOAD_SITE_URL.$file['url']?>"><?=$file['name']; ?></a>
                            </div>  
                    <?php } ?>
                <?php } ?>
            </span>
        </div>
    </div>

    <div class="pure-g line">
        <div class="pure-u-1">
            <span>外部关联方: </span>
            <span style="margin-left:20px;">
                <?php 
                if(!empty($data['user_phone'])){
                    echo "客户手机:".$data['user_phone'];
                }?>
            </span>
            
            <span style="margin-left:20px;">
                <?php
                if(!empty($data['seller_name'])){
                    echo "售方用户名:".$data['seller_name'];
                }?>
            </span>
        </div>
    </div>
    
    <div class="clear"></div>


    <div class="big_title">
        处理记录
    </div>
    <div class="info">
        <table class="table tb-type2">
            <thead>
            <tr class="thead blue">
                <th >记录时间</th>
                <th >记录内容</th>
                <th >证据</th>
                <th >记录人</th>
                <th >跟进处理部门</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($handle_list) && is_array($handle_list)){ $i=0; ?>
                <?php foreach($handle_list as $k => $v){ ?>
                    <tr class="hover">
                        <td><?= $v['created_at'] ?></td>
                        <td>
                            <?= $v['content'] ?>
                        </td>
                        <td>
                            <?php if($v['files']){ ?>
                                <?php foreach($v['files'] as $file) { ?>
                                    <a href="<?=UPLOAD_SITE_URL.$file['url']?>"><?=$file['name']; ?></a>
                                <?php }?>
                            <?php }?>
                        </td>
                        <td><?= $v['creator']?></td>
                        <td><?php if($v['next_dept']) echo $v['next_dept'].'普通权限'; ?></td>
                    </tr>
                <?php } ?>
            <?php }else { ?>
                <tr class="no_data" id="no_data" data-has="1">
                    <td colspan="8"><?=$lang['nc_no_record'];?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="center mt-50">
            <?php if($can_handle){ ?>
               <a href="javascript:confirm_handle();" class="button confirm">接单</a>
            <?php } ?>
            <a href="javascript:history.go(-1);" class="button">返回</a>
    </div>

    

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

/**
 * 接单操作
 */
function confirm_handle()
{
        //询问框
        layer.confirm('确定由您来处理本工单吗', {
        btn: ['确定','取消'] //按钮
        }, function(){
            do_handle(); 
        }, function(){
            
        });
}

function do_handle()
{
    
    post('index.php?act=work_sheet&op=pickup&id='+<?=$data['id']?>,{})
    .then(function(res){
        if(res.data.code == 200)
        {
            alert(res.data.msg);
            layer.closeAll();
            location.href='index.php?act=work_sheet&op=index';
        }else{
            alert(res.data.msg);
        }
    })
}
</script>
