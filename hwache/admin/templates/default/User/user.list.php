<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $lang['index_title'];?></h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage'];?></span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="searchFrom" action="<?=url('new_user','list')?>">
        <input type="hidden" value="new_user" name="act">
        <input type="hidden" value="list" name="op">
        <?php $search = $output['search'];?>
        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <th><label for="search_title">姓名</label></th>
                &nbsp;&nbsp;
                <td><input type="text" value="<?=$search['keyword'];?>" name="search_keyword" class="txt">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="name_phone">手机/邮箱</label>
                    &nbsp;&nbsp;
                    <input type="text" value="<?=$search['email_phone'];?>" name="email_phone" class="txt">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="status">启用状态</label>
                    &nbsp;&nbsp;
                    <select name="status" id="status" class="">
                        <option value="-1" <?php if($search['status']==-1){?>selected="selected"<?php } ?>><?php echo $lang['nc_please_choose'];?>...</option>
                        <option value="0" <?php if($search['status']==0){?>selected="selected"<?php } ?> >无效</option>
                        <option value="1" <?php if($search['status']==1){?>selected="selected"<?php } ?>>有效</option>
                    </select>
                </td>
                <td>
                    <button type="submit" class="btn-search "><?php echo $lang['nc_query'];?></button>                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn" onclick="resetForm();">重置</button>
                </td>

            </tr>

            </tbody>
        </table>
    </form>

    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th class="w24">会员号</th>
            <th class="w60">姓名</th>
            <th class="w48">手机号</th>
            <th class="align-center">邮箱</th>
            <th class="align-center">注册时间</th>
            <th class="align-center">最近登录</th>
            <th class="w60 align-center">账号状态</th>
            <th class="w70 align-center">账号限制</th>
            <th class="w60 align-center"><?=$lang['operation'];?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?=$v['id']; ?></td>
                    <td><?=$v['last_name'].$v['first_name']; ?></td>
                    <td><?=$v['phone']; ?></td>
                    <td class="align-center"><?=$v['email'];?></td>
                    <td class="nowrap align-center"><?=$v['created_at']?></td>
                    <td class="nowrap align-center"><?=$v['updated_at']?></td>
                    <td class="align-center"><? echo getUserStatus($v['id']); ?></td>
                    <td class="align-center"><?php echo getFrStatus($v['id']); ?></td>
                    <td class="align-center">
                        <a href="<?=url('new_user','view',['id'=>$v['id']]); ?>">查看</a>
                        |
                        <a href="<?=url('new_user','edit',['id'=>$v['id']]); ?>"><?php echo $lang['nc_edit'];?></a>
                    </td>
                </tr>
            <?php } ?>
        <?php }else { ?>
            <tr class="no_data">
                <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <tr class="tfoot">
                <td>&nbsp;</td>
                <td colspan="16">
                    <div class="pagination"> <?php echo $output['page'];?></div>
                </td>
            </tr>
        <?php } ?>
        </tfoot>
    </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script>
    function resetForm(){
        $('#searchFrom').find(':input').not(':button, :submit, :reset').val('').removeAttr('checked').removeAttr('selected');
        location.href = "<?=url('seller','list')?>";
    }
</script>