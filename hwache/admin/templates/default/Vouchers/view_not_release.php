<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    .table input.date , .table input.date:hover{width:160px ; padding:2px; text-align:center; border:1px #000 solid;}
    .table input.txt{width: 200px; padding:2px; border: 1px #000 solid;}
    .table input.big{width: 600px;}
    .table tbody{margin-bottom: 15px;}
    .table tbody.border {border: 1px #000 solid;}
    p.title{width: 100%; height: 30px; line-height: 30px; font-size: 14px; font-weight: bold; margin-left: 10px;}

    table .tab , table .tab tr{width: 100%; margin: 5px; }
    .tab td { height: 30px; line-height: 30px; color: #000; padding:5px;text-align:left; margin-left: 15px;}
    .tab .center {text-align: center;}
    .tab td.right{text-align: right; margin-right: 15px; width: 150px;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="<?=url('hc_vouchers','index');?>">代金券</a></h3>
            <ul class="tab-base">
                <li><a href="<?=url('hc_vouchers','release');?>"><span>投放激活列表</span></a><em> | </em></li>
                <li><a href="JavaScript:void(0);" class="current"><span>投放<em class="red">免激活</em>代金券</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <?php
    $group = $output['info'];
    ?>
    <form id="release_form" action="<?=url('hc_vouchers','postSetStatus');?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="id" value="<?=$group['id'];?>" />
        <input type="hidden" name="type" value="2" />
        <input type="hidden" name="status" id="status" />
        <input type="hidden" name="activated_type" value="0" />

        <table class="table tb-type2">
            <thead>
            <th colspan="6"><hr /></th>
            </thead>
            <tbody>
            <tr class="noborder">
                <td width="10%" class="required"><label>代金券组编号：</label></td>
                <td class="vatop rowform"><?=$group['group_id']?></td>
                <td width="10%" class="required"><label>券组状态：</label></td>
                <td class="vatop rowform">
                    <?php
                    if(in_array($group['status'],[3,4])){
                        echo '已失效';
                    }else{
                        $touNum = (($group['use_collateral'] + $group['use_sincerity']) * $group['activated_total_num']) - $group['release_total'];
                        if($group['release_total'] ==0 && $group['status']==0){
                            echo "未投放";
                        }else{
                            echo ($touNum ==0) ? "全部投放" : "部分投放";
                        }
                    }
                    ?>
                </td>
                <td width="10%" class="required"><label>每组条数：</label></td>
                <td class="vatop rowform">不限</td>
            </tr>

            <tr class="noborder">
                <td class="required">激活类型：</td>
                <td class="vatop rowform"><?=($group['activated_type'])?'需激活':'免激活'; ?></td>
                <td class="required">&nbsp;</td>
                <td class="vatop rowform">&nbsp;</td>
                <td class="required">已占条数:</td>
                <td class="vatop rowform">
                    <?=getGroupTake($group['group_id'])?>
                </td>
            </tr>

            <tr>
                <td class="required"><label>券组说明：</label></td>
                <td class="required" colspan="5"><?=$group['group_remark']?></td>
            </tr>

            <thead>
            <th colspan="6">
                <hr style="border:0;background-color:#1E7EB4;height:1px;" />
            </th>
            </thead>
            <tbody>
            <tr>
                <td class="required"><label>申请投放编号：</label></td>
                <td class="vatop rowform"><?=$group['id']?></td>
                <td class="required"><label>投放状态：</label></td>
                <?php
                if(in_array($group['status'],[0,1])){
                    ?>
                    <td class="vatop rowform" colspan="3">
                        <?php
                        $statusArr =['待批准','待投放','已投放','已失效'];
                        echo $statusArr[$group['status']];
                        ?>
                    </td>
                <?php }else{ ?>
                    <td class="vatop rowform">
                        <?php
                        $statusArr =['待批准','待投放','已投放','已失效','未批准'];
                        echo $statusArr[$group['status']];
                        ?>
                    </td>
                    <td class="required"> &nbsp;</td>
                    <td class="vatop rowform"> &nbsp;</td>
                <?php }?>
            </tr>
            <tr>
                <td class="required"><label>申请投放条数：</label></td>
                <td class="vatop rowform"><?=$group['release_total_num']?></td>
                <td class="required"> &nbsp;</td>
                <?php
                if(in_array($group['status'],[0,5])){
                    ?>
                    <td class="vatop rowform" colspan="3">
                        &nbsp;
                    </td>
                <?php }else{ ?>
                    <td class="vatop rowform">
                        &nbsp;
                    </td>
                    <td class="required"><label>实际投放数：</label></td>
                    <td class="vatop rowform">
                        <?php
                        echo ($group['status']==2) ? $group['release_total'] : 0;
                        ?>
                    </td>
                <?php }?>
            </tr>
            <tr>
                <td class="required"><label>投放对象：</label></td>
                <td class="vatop rowform" colspan="5">
                    <?php
                    $objectArr = ['所有客户','特定客户','一年内未买车的客户','三个月内新注册并且未买车的用户'];
                    echo $objectArr[$group['ignore_object']];
                    if($group['ignore_objdec'] ==1){
                        echo "(".$group['ignore_users'].")";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="required"><label>投放时间：</label></td>
                <td class="vatop rowform" colspan="5">
                    <?php
                        if($group['ignore_time_type'] ==0){
                            echo '批准后立即投放';
                        }else{
                            echo '批准后定时投放('.$group['fixed_start_time'].' '.$group['fixe_hour_time'].')';
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="required"><label>投放说明：</label></td>
                <td class="vatop rowform" colspan="5"><?=$group['remark']?></td>
            </tr>
            <?php
            if($group['status'] ==3){
                ?>
                <tr>
                    <td class="required"><label>终止投放原因：</label></td>
                    <td class="vatop rowform" colspan="5">
                        <?php
                        if(isset($output['log']) && !is_null($output['log'])) {
                            foreach ($output['log'] as $key => $log) {
                                if($log['step']==3){
                                    echo $log['remark'];
                                }
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>

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
                        echo '诚意金 ￥'.$group['sincerity_money'].'<a href="'.$group['ignore_sincerity_temp'].'">查看设计模板</a>';
                    }else{
                        if(empty($group['use_collateral'])){
                            echo '买车担保金余款 ￥'.$group['collateral_money'].'<a href="'.$group['ignore_collateral_temp'].'">查看设计模板</a>';
                        }else{
                            echo '诚意金 ￥'.$group['sincerity_money'].'<a href="'.$group['ignore_sincerity_temp'].'">查看设计模板</a>';
                        }
                    }
                    ?>
                </td>
                <td class="vatop rowform" colspan="4">
                    <?php
                    if($group['use_collateral'] && $group['use_sincerity']){
                        echo '买车担保金余款 ￥'.$group['collateral_money'].'<a href="'.$group['ignore_collateral_temp'].'">查看设计模板</a>';
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
                <td class="required"><label>申请投放人：</label></td>
                <td class="vatop rowform"><?=getDeptAdmin($group['admin_id']);?></td>
                <td class="required"> 申请时间；</td>
                <td class="vatop rowform" colspan="3"><label><?=$group['created_at']?></label> </td>
            </tr>
            <?php
            if(isset($output['log']) && !is_null($output['log'])){
                foreach($output['log'] as $key => $log){
                    $remarkStr = [1=>'审批人:',2=>'投放人:',3=>'失效人:',4=>'未批准人:'];
                    ?>
                    <tr>
                        <td class="required"><label><?=$remarkStr[$log['step']]?></label></td>
                        <td class="vatop rowform"><?=getDeptAdmin($log['user_id']);?></td>
                        <td class="required"> 申请时间；</td>
                        <td class="vatop rowform" colspan="3"><label><?=$log['created_at']?></label> </td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
            <thead><th></th></thead>
            <tfoot>
            <tr class="tfoot">
                <td colspan="6" style="text-align: center;" >
                    <?php
                    switch($group['status']){
                        case 1://
                            echo '<a href="JavaScript:;" onclick="submitData(\'confirm\',2)" class="button button-primary button-small"> 审核通过，并确认投放 </a>&nbsp;';
                            echo '<a href="JavaScript:;" onclick="failure(\'.$group[\'id\'].\')" class="button button-primary button-small"> 终止投放 </a>';
                            break;
                        case 0:
                            if($group['approval'] ==1){
                                echo '<a href="JavaScript:;" onclick="ajaxSub(\'agree\','.$group['id'].',1,2,0)" class="button button-primary button-small"> 同意投放 </a>&nbsp;';
                                echo '<a href="JavaScript:;" onclick="ajaxSub(\'notAgree\','.$group['id'].',4,2,0)" class="button button-primary button-small"> 不同意 </a>';
                            }
                            break;
                    }
                    ?>
                    <a href="<?=url('hc_vouchers','release');?>" class="button button-primary button-small"> 返回 </a>
                </td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/Vouchers/release.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />