<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    .table input.date,.table input.date:hover{width: 120px;}
    .table input.txt{width: 200px; padding:2px; border: 1px #000 solid;}
    .table input.not-border{border: none; background: none; display: none;}
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
                <li><a href="JavaScript:void(0);" class="current"><span>申请投放<em style="color: #ff0000;">免激活</em>代金券</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <?php
    $group = $output['group'];
    ?>
    <form id="release_form" action="<?=url('hc_vouchers','save_release');?>" method="post">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="group_id" value="<?=$group['id'];?>" />
        <input type="hidden" name="activated_type" value="0" />

        <table class="table tb-type1">
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
                <td class="vatop rowform">不限</td>
            </tr>

            <tr class="noborder">
                <td class="required">激活类型：</td>
                <td class="vatop rowform"><?=($group['activated_type'])?'需激活':'免激活'; ?></td>
                <td class="required">&nbsp;</td>
                <td class="vatop rowform">&nbsp;</td>
                <td class="required">已占条数:</td>
                <td class="vatop rowform"><?=$group['release_total']?></td>
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
                        echo '诚意金 ￥'.$group['sincerity_money'].'<a href="'.$group['ignore_sincerity_temp'].'">查看设计模板</a>';
                    }else{
                        if(empty($group['use_collateral'])){
                            echo '诚意金 ￥'.$group['sincerity_money'].'<a href="'.$group['ignore_sincerity_temp'].'">查看设计模板</a>';
                        }else{
                            echo '买车担保金余款 ￥'.$group['collateral_money'].'<a href="'.$group['ignore_collateral_temp'].'">查看设计模板</a>';
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
                <td class="required"><label class="validation">投放对象：</label></td>
                <td class="vatop rowform">
                    <label><input type="radio" name="ignore_object" value="1" >特定客户</label>
                </td>
                <td class="required" colspan="4">
                    <span id="userList"></span>
                    <input type="hidden" name="users" id="users"/>
                    <span id="user_input"><input type="text" class="member" /></span>
                    <button type="button" class="button button-primary button-circle button-small" onclick="addUser()"><b>+</b></button>
                </td>
            </tr>
            <tr>
                <td class="required"> &nbsp; </td>
                <td colspan="5">
                     <label><input type="radio" name="ignore_object" checked="checked" value="0" >所有客户</label>
                </td>
            </tr>
            <tr>
                <td class="required"> &nbsp; </td>
                <td colspan="5">
                    <label><input type="radio" name="ignore_object" value="2" >所有客户(一年内已买车的除外)</label>
                </td>
            </tr>
            <tr>
                <td class="required"> &nbsp; </td>
                <td colspan="5">
                    <label><input type="radio" name="ignore_object" value="3" >3个月内的新注册未购车客户</label>
                </td>
            </tr>

            <tr>
                <td class="required"><label class="validation">投放时间：</label></td>
                <td class="required" colspan="5">
                    <label><input type="radio" name="ignore_time_type" checked="checked" value="0" >批准后立即投放</label>
                </td>
            </tr>
            <tr>
                <td class="required"><label>&nbsp;</label></td>
                <td class="vatop rowform">
                    <label><input type="radio" name="ignore_time_type" value="1" >批准后定时投放</label>
                </td>
                <td colspan="4">
                    <span>
                        <input name="fixed_start_time" id="fixed_start_time" class="date" type="text" /> &nbsp;
                    </span>
                    <span>
                    <input name="fixe_hour_time" id="fixe_hour_time" placeholder="24:00" type="text" class="time" />
                    </span>
                </td>
            </tr>
            <tr>
                <td class="required"><label class="validation">投放说明：</label></td>
                <td class="vatop rowform" colspan="5">
                    <textarea name="remark" style="width: 80%; height: 60px;"></textarea>
                </td>
            </tr>
            </tbody>
            <thead><th></th></thead>
            <tfoot>
            <tr class="tfoot">
                <td colspan="6" style="text-align: center;" >
                    <button type="button" class="button button-primary button-small" onclick="submitRelease();">
                        确认无误，提交
                    </button>
                    <a href="<?=url('hc_vouchers','group');?>" class="button button-primary button-small"> 返回 </a>
                </td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/region.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/diy_validate.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/maskedinput/jquery.maskedinput.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/Vouchers/release.js" charset="utf-8"></script>

