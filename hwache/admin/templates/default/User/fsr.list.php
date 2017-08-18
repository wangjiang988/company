<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>防骚扰管理</h3>
            <ul class="tab-base">
                <li><a href="<?=url('new_user','fsr')?>" <?php if($output['search']['type']==0){?>class="current"<?php } ?>><span>手机防骚扰</span></a></li>
                <li><a href="<?=url('new_user','fsr',['type'=>1])?>" <?php if($output['search']['type']==1){?>class="current"<?php } ?>><span>邮箱防骚扰</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="searchFrom" action="<?=url('new_user','fsr')?>">
        <input type="hidden" name="act" value="new_user">
        <input type="hidden" name="op" value="fsr">

        <input type="hidden" name="type" value="<?=$output['search']['type']?>">
        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
                <tr>
                    <th><label for="search_title"><?php echo empty($output['search']['type']) ? '手机':'邮箱';  ?></label></th>
                    <td>
                        <input type="text" value="<?=$output['search']['keyword'];?>" name="search_keyword" class="txt">
                    </td>
                    <td>
                        <button type="submit" class="btn-search"><?php echo $lang['nc_query'];?></button>                    &nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn" onclick="addFreeze();">添加</button>
                        <button type="button" class="btn" onclick="delAll();">批量解除</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>


    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th class="w24"><input type="checkbox" onclick="allCheck(this)"/> 全选</th>
            <th class="align-center"><?php echo empty($output['search']['type']) ? '手机号':'邮箱地址';  ?></th>
            <th class="align-center">添加人</th>
            <th class="align-center">添加时间</th>
        </tr>
        </thead>
        <form method="post" id="checkForm" action="<?=url('new_user','delFreeze')?>">
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td> <input type="checkbox" name="id[]" value="<?=$v['id']?>" /> </td>
                    <td><?=$v['value']; ?></td>
                    <td><?=$v['real_name']; ?></td>
                    <td class="nowrap align-center"><?=$v['created_at']?></td>
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/diy_validate.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/User/edit.js" charset="utf-8"></script>