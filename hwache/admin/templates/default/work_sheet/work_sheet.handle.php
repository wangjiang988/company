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

    <div class="clear"></div>
    
    <div class="big_title">
        添加记录
    </div>

     <form method="post"  id="ajax_form" name="ajax_form">
        <input type="hidden" name="act" value="work_sheet">
        <input type="hidden" name="op" value="handle">
        <input type="hidden" name="form_submit" value="ok">
        <input type="hidden" name="id" value="<?=$current_handler['id']?>">
        <div class="pure-g line">
            <div class="pure-u-1-5">
                记录内容:
            </div>
             <div class="pure-u-4-5">
                <textarea name="content" style="width:500px; height:100px;"></textarea>
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
                <span style="color:red;">*</span>跟进处理部门:
            </div>
            <div class="pure-u-4-5">
                <input type="hidden" name="dept" value="0">
                <input type="hidden" name="dept_name">
                <?php if($dept_list){ ?> 
                    <?php foreach($dept_list as $dept){ ?>
                        <span style="margin-right:10px;">
                            <input id="dept_<?=$dept['id']?>" class="dept" data-name="<?=$dept['name']?>" name="dept_check" type="radio" value="<?=$dept['id']?>"><label for="dept_<?=$dept['id']?>"><?=$dept['name']?></label>                      
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
                当前处理人:
            </div>
            <div class="pure-u-4-5">
                 <?=$current_handler['creator']?>
            </div>
        </div>



    </form>

    <div class="center mt-50">
            <a href="javascript:transfer();" class="button confirm">不处理，转单</a>
            <a href="javascript:finish();" class="button confirm">已了解，关闭工单</a>
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
            $("input[name='dept_name']").val($(this).data('name'));
       });
});

function form_checked()
{
    let json  = $('#ajax_form').serializeJSON();
    if(json.dept == 0){
        alert("请选择跟进部门");
        return false;
    }

    if(json.content.trim() =="")
    {
        alert("请输入记录内容");
        return false;
    }
    return true;
}

/**
 * 不处理，转单
 */
function transfer()
{
        if(!form_checked())
        {
            return false;
        }
        //询问框
        layer.confirm('确定转给其他部门处理吗？', {
        btn: ['确定','取消'] //按钮
        }, function(){
            do_handle('transfer'); 
        }, function(){
            
        });
}

/**
 * 已了结，关闭工单
 */
function finish()
{
    let json  = $('#ajax_form').serializeJSON();
    if(json.content.trim() =="")
    {
        alert("请输入记录内容");
        return false;
    }

    //询问框
    layer.confirm('确定本工单已了结吗？', {
    btn: ['确定','取消'] //按钮
    }, function(){
        do_handle('finish'); 
    }, function(){
        
    });
}

function do_handle(action)
{
        set_file_path();
        let json  = $('#ajax_form').serializeJSON();
        json.action = action;
        post("index.php?act=work_sheet&op=handle&id=<?=$current_handler['id']?>",json)
        .then(function(res){
            if(res.data.code == 200)
            {
                alert(res.data.msg);
                layer.closeAll();
                // location.href='index.php?act=work_sheet&op=index';
            }else{
                alert(res.data.msg);
            }
        })
    
}
</script>
