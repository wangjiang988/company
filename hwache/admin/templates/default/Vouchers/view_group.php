<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    .table input.date , .table input.date:hover{width:160px ; padding:2px; text-align:center; border:1px #000 solid;}
    .table input.txt{width: 200px; padding:2px; border: 1px #000 solid;}
    .table input.big{width: 600px;}
    .table tbody{margin-bottom: 15px;}
    .table tbody.border {border: 1px #000 solid;}
    p.title{width: 100%; height: 30px; line-height: 30px; font-size: 14px; font-weight: bold; margin-left: 10px;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="<?=url('hc_vouchers','index');?>">代金券</a></h3>
            <ul class="tab-base">
                <li><a href="<?=url('hc_vouchers','group');?>"><span>代金券组</span></a><em>  </em></li>
                <li><a href="JavaScript:void(0);" class="current"><span>查看需激活代金券组</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <?php
    $group = $output['group'];
    ?>
    <form id="voucher_form" action="<?=url('hc_vouchers','save_release');?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="group_id" value="<?=$group['id'];?>" />
        <input type="hidden" name="can_release_num" value="<?=$group['can_release_num'];?>" />
        <input type="hidden" name="activated_type" value="1" />
        <input type="hidden" name="ref_url" value="<?php echo getReferer();?>" />

        <table class="table tb-type2">
            <thead>
            <th colspan="6"><hr /></th>
            </thead>
            <tbody>
            <tr class="noborder">
                <td width="10%" class="required"><label>代金券组编号：</label></td>
                <td class="vatop rowform"><?=$group['id']?></td>
                <td width="10%" class="required"><label>券组状态：</label></td>
                <td class="vatop rowform">
                    <?php
                    $touNum = (($group['use_collateral'] + $group['use_sincerity']) * $group['activated_total_num']) - $group['release_total'];
                    if($group['release_total'] ==0){
                        echo "未投放";
                    }else{
                        echo ($touNum ==0) ? "全部投放" : "部分投放";
                    }
                    ?>
                </td>
                <td width="10%" class="required"><label>每组条数：</label></td>
                <td class="vatop rowform">
                    <?php
                    echo ($group['use_collateral'] + $group['use_sincerity']) * $group['activated_total_num'];
                    ?>
                </td>
            </tr>

            <tr class="noborder">
                <td class="required">激活类型：</td>
                <td class="vatop rowform"><?=($group['activated_type'])?'需激活':'免激活'; ?></td>
                <td class="required">激活码位数：</td>
                <td class="vatop rowform"><?=$group['activated_num']?></td>
                <td class="required">激活码生成规则:</td>
                <td class="vatop rowform">
                    <?php
                    $rule = ['全数字','小写字母','大写字母','前两位大写字母+数字','前两位大写字母+数字+末尾两位大写字母'];
                    echo $rule[$group['activated_rule']-1];
                    ?>
                </td>
            </tr>

            <tr class="noborder">
                <td class="required">有效激活时间：</td>
                <td class="vatop rowform"><?=$group['activated_start_time'].'~'.$group['activated_end_time'] ?></td>
                <td class="required">已占条数：</td>
                <td class="vatop rowform"><?=getGroupTake($group['id'])?></td>
                <td class="required">可投条数:</td>
                <td class="vatop rowform"><?=($group['status']!=2)?$group['can_release_num']:0;?></td>
            </tr>

            <tr>
                <td class="required"><label>券组说明：</label></td>
                <td class="required" colspan="5"><?=$group['remark']?></td>
            </tr>

            </tbody>
            <thead style="border-top: #1E7EB4 1px solid;">
            <th colspan="6">
                <p class="title">代金券：</p>
            </th>
            </thead>
            <tbody class="border">
            <tr>
                <td class="required"><label>代金券类别：</label></td>
                <td class="vatop rowform" colspan="5">
                    <?php
                    if($group['type'] ==1){
                        $brandArr= [getGoodsClass($group['brand_id']),getGoodsClass($group['series_id']),getGoodsClass($group['model_id'])];
                        echo '品类券 '.implode( ' > ',array_filter($brandArr));
                    }else{
                        echo '通用券';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="required"><label>有效使用时间：</label></td>
                <td colspan="5">
                    <?php
                    echo $group['life_start_time'] .' '.$group['life_start_hour'].' ~ '. $group['life_end_time'].' '.$group['life_end_hour'];
                    ?>
                </td>
            </tr>
            <tr>
                <td class="required"><label>代用款项与面值：</label></td>
                <td class="vatop rowform">
                    <?php
                    if($group['use_collateral'] && $group['use_sincerity']){
                        echo '诚意金 ￥'.$group['sincerity_money'];
                    }else{
                        echo ($group['use_collateral']) ?  '买车担保金余款 ￥'.$group['collateral_money'] : '诚意金 ￥'.$group['sincerity_money'] ;
                    }
                    ?>
                </td>
                <td class="vatop rowform" colspan="4">
                    <?php
                    if($group['use_collateral'] && $group['use_sincerity']){
                        echo '买车担保金余款 ￥'.$group['collateral_money'];
                    }
                    ?>
                </td>
            </tr>
            </tbody>
            <!---->
            <thead>
            <th colspan="6">
                <hr style="border:0;background-color:#1E7EB4;height:1px;" />
            </th>
            </thead>
            <tbody>
            <tr>
                <td class="required"><label>新增人：</label></td>
                <td class="vatop rowform">
                    <?php
                      echo getDeptAdmin($group['admin_id']);
                    ?>
                </td>
                <td class="required"> 新增时间；</td>
                <td class="vatop rowform" colspan="3"><label><?=$group['created_at']?></label> </td>
            </tr>
            <?php
            if(! is_null($output['peration'])) {
                $peration = $output['peration'][0];
                ?>
                <tr>
                    <td class="required">失效人：</td>
                    <td class="vatop rowform">
                        <?=$peration['user_name']?>
                    </td>
                    <td class="required">失效时间：</td>
                    <td class="vatop rowform" colspan="3">
                        <?=$peration['created_at']?>
                    </td>
                </tr>

                <tr>
                    <td class="required"><label>失效原因：</label></td>
                    <td class="vatop rowform" colspan="5"><?=$peration['remark']?></td>
                </tr>
                <?php } ?>
            </tbody>
            <thead><th></th></thead>
            <tfoot>
            <tr class="tfoot">
                <td colspan="6" style="text-align: center;" >
                    <a href="<?=url('hc_vouchers','group');?>" class="button button-primary button-small"> 返回 </a>
                </td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />

