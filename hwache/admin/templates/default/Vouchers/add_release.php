<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    .table input.date , .table input.date:hover{width:160px ; padding:2px; text-align:center;}
    .table input.txt{width: 200px; padding:2px;}
    .table input.small{float:left;width: 60px; margin: 0;}
    .table tbody{margin-bottom: 15px;}
    .table tbody.border {border: 1px #000 solid;}
    p.title{width: 100%; height: 30px; line-height: 30px; font-size: 14px; font-weight: bold; margin-left: 10px;}
    .danwei{float:left;width: 20px; text-align: center; border: 1px #ccc solid; height: 23px; line-height: 23px; font-size: 14px; color: #ff0000; margin: 0; background: #ccc;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="<?=url('hc_vouchers','index');?>">代金券</a></h3>
            <ul class="tab-base">
                <li><a href="<?=url('hc_vouchers','group');?>"><span>代金券组</span></a><em>  </em></li>
                <li><a href="JavaScript:void(0);" class="current"><span>申请投放需激活代金券</span></a></li>
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
        <input type="hidden" name="activated_type" value="1" />
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
                <td class="vatop rowform"><?=$group['release_total']?></td>
                <td class="required">可投条数:</td>
                <td class="vatop rowform"><?=$group['can_release_num']?></td>
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
                            echo ($group['use_collateral']) ? '买车担保金余款 ￥'.$group['collateral_money'] : '诚意金 ￥'.$group['sincerity_money'];
                        }
                    ?>
                </td>
                <td class="vatop rowform">
                    <?php
                    if($group['use_collateral'] && $group['use_sincerity']){
                        echo '买车担保金余款 ￥'.$group['collateral_money'];
                    }
                    ?>
                </td>
                <td class="required" colspan="3">&nbsp;</td>
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
                    <td class="required"><label class="validation">本次投放条数：</label></td>
                    <td colspan="5">
                        <input type="text" class="txt" name="release_total_num" id="release_total_num" max="<?=$group['can_release_num']?>" placeholder="0~<?=$group['can_release_num']?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="required"><label class="validation">投放对象：</label></td>
                    <td class="vatop rowform">
                        <label><input type="radio" name="release_object" checked="checked" value="0" >华车内部</label>
                    </td>
                    <td class="required">
                        <select name="guide_dept" id="guide_dept" onchange="getDeptUser(this.value,'#guide_name')">
                            <option value="">选择部门</option>
                            <?php
                            foreach($output['dept'] as $dept){
                                echo '<option value="'.$dept['name'].'">'.$dept['name'].'</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td class="required" colspan="3">
                        <select name="guide_name" id="guide_name">
                            <option value="">选择员工</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="required">&nbsp;</td>
                    <td class="vatop rowform">
                        <label><input type="radio" name="release_object" value="1" >推广代理</label>
                    </td>
                    <td class="required">代理人</td>
                    <td class="vatop rowform" colspan="3">
                        <input type="text" class="txt" name="proxy_name" id="proxy_name" />
                    </td>
                </tr>
                <tr>
                    <td class="required">&nbsp;</td>
                    <td class="vatop rowform">&nbsp;</td>
                    <td class="required">华车指导</td>
                    <td class="required">
                        <select name="d_dept" id="d_dept" onchange="getDeptUser(this.value,'#d_guide_name')">
                            <option value="">选择部门</option>
                            <?php
                            foreach($output['dept'] as $dept){
                                echo '<option value="'.$dept['name'].'">'.$dept['name'].'</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td class="required" colspan="2">
                        <select name="d_guide_name" id="d_guide_name">
                            <option value="">选择员工</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="required">&nbsp;</td>
                    <td class="vatop rowform">&nbsp;</td>
                    <td class="required">推广奖励</td>
                    <td class="required">
                        <label><input type="radio" name="reward_type" checked="checked" value="0"/>无</label>
                    </td>
                    <td class="vatop rowform" colspan="2"></td>
                </tr>
                <tr>
                    <td class="required">&nbsp;</td>
                    <td class="vatop rowform">&nbsp;</td>
                    <td class="required">&nbsp;</td>
                    <td class="required">
                        <label><input type="radio" name="reward_type" value="1"/>代金券结算金额的</label>
                    </td>
                    <td class="vatop rowform" colspan="2">
                        <input type="text" class="txt small" name="reward_money1" id="reward_money1" placeholder="0~100"/><em class="danwei">%</em>
                    </td>
                </tr>
                <tr>
                    <td class="required">&nbsp;</td>
                    <td class="vatop rowform">&nbsp;</td>
                    <td class="required">&nbsp;</td>
                    <td class="required">
                        <label><input type="radio" name="reward_type" value="2"/>每个完成代金券结算订单</label>
                    </td>
                    <td class="vatop rowform" colspan="2">
                        <em class="danwei">￥</em><input type="text" class="txt small" name="reward_money2" id="reward_money2" placeholder="0~500"/>
                    </td>
                </tr>
                <tr>
                    <td class="required"><label class="validation">投放区域：</label></td>
                    <td class="required">
                        <select name="province" id="province" onchange="setCity(this.value,'city')">
                            <option value="0">全国</option>
                            <?php
                            foreach($output['region'] as $region){
                                echo '<option value="'.$region['area_id'].'">'.$region['area_name'].'</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td class="vatop rowform" colspan="4">
                        <select name="city" id="city">
                            <option value="0">请选择</option>
                        </select>
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
                    <button type="button" class="button button-primary button-small" onclick="submitJhRelease();">
                        确认无误，提交
                    </button>
                    <a href="<?=url('hc_vouchers','group');?>" class="button button-primary button-small"> 返回 </a>
                </td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/diy_validate.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/region.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/Vouchers/release.js" charset="utf-8"></script>
