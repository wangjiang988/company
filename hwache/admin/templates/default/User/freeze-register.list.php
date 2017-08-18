<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>未注册手机号管理</h3>
            <ul class="tab-base">
                <li>管理</li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="searchFrom" action="<?=url('new_user','fsr')?>">
        <input type="hidden" name="act" value="new_user">
        <input type="hidden" name="op" value="register">

        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <th><label for="search_title">手机号</label></th>
                <td>
                    <input type="text" value="<?=$output['search']['keyword'];?>" name="search_keyword" class="txt">
                    <select name="status">
                        <option value="1">--请选择--</option>
                        <option <?=isSelected([$output['search']['status'],'==',2],'select')?> value="2">当日冻结</option>
                        <option <?=isSelected([$output['search']['status'],'==',3],'select')?> value="3">正常</option>
                    </select>
                </td>
                <td>
                    <button type="submit" class="btn-search"><?php echo $lang['nc_query'];?></button>
                </td>
            </tr>
            </tbody>
        </table>
    </form>


    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th class="w24">编号</th>
            <th class="align-center">手机号</th>
            <th class="align-center">冻结开始时间</th>
            <th class="align-center">状态</th>
            <th class="align-center">操作</th>
        </tr>
        </thead>
        <form method="post" id="checkForm" action="<?=url('new_user','delFreeze')?>">
            <tbody>
            <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                <?php foreach($output['list'] as $k => $v){ ?>
                    <tr class="hover">
                        <td class="nowrap align-center"><?=$v['id']?></td>
                        <td class="nowrap align-center"><?=$v['value']; ?></td>
                        <td class="nowrap align-center"><?=$v['created_at']?></td>
                        <td class="nowrap align-center"><?=($v['is_freeze']==1)? '当日冻结' : '正常';?></td>
                        <td class="nowrap align-center">
                            <?php
                            $id = $v['id'];
                            $click = "setDj('".$id."',0,'reg_dj')";
                            if($v['is_freeze']==1) {
                                echo '<a href="javascript:;" onclick="' . $click . '">解冻</a>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php }else { ?>
                <tr class="no_data">
                    <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
                </tr>
            <?php } ?>
            </tbody>
        </form>
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
<script>
    var dj_user_url = "<?=url('new_user','freeze')?>";
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/diy_validate.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/User/edit.js" charset="utf-8"></script>