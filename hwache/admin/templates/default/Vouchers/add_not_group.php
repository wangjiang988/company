<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    .table input.date , .table input.date:hover{width:100px ;}
    .table input.txt{width: 200px; padding:2px;}
    .table input.big{width: 600px;}
    .table input.small{width: 120px;}
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
                <li><a href="JavaScript:void(0);" class="current"><span>新增<em style="color: #ff0000;">免激活</em>代金券组</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="voucher_form" action="<?=url('hc_vouchers','save_group');?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="id" id="id" value="<?=$output['find']['id'];?>" />
        <input type="hidden" name="activated_type" value="0" />
        <table class="table tb-type1">
            <thead>
            <th><p class="title">设置代金券</p></th>
            </thead>
            <tbody class="border">
            <tr class="noborder">
                <td width="10%" class="required"><label class="validation">代金券类别：</label></td>
                <td colspan="4" class="vatop rowform">
                    <input type="radio" name="type" checked value="0" />通用券
                </td>
            </tr>
            <tr class="noborder">
                <td class="required">&nbsp;</td>
                <td class="vatop rowform">
                    <input type="radio" name="type" value="1" />品类券
                </td>
                <td class="required">
                    <label for="member_truename">品牌：</label>
                    <select name="brand_id" id="brand_id" onchange="addBrand('#series_id',this.value,'#model_id')">
                        <option value="">--选择--</option>
                        <?php
                        foreach($output['brands'] as $brand){
                            echo '<option value="'.$brand['gc_id'].'">'.$brand['gc_name'].'</option>';
                        }
                        ?>
                    </select>
                </td>
                <td class="required">
                    <label>车系：</label>
                    <select name="series_id" id="series_id" onchange="addBrand('#model_id',this.value,'')">
                        <option value="">--选择--</option>
                    </select>
                <td class="required">
                    <label for="seller_card_num">车型：</label>
                    <select name="model_id" id="model_id">
                        <option value="">--选择--</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="required"><label class="validation">有效使用时间：</label></td>
                <td class="vatop rowform"><input type="text" name="life_start_time" id="life_start_time" class="date" /></td>
                <td class="required">
                    <input type="text" name="life_start_hour" id="life_start_hour" class="txt small start_time" />
                </td>
                <td class="vatop rowform">~<input type="text" name="life_end_time" id="life_end_time" class="date" /></td>
                <td><input type="text" name="life_end_hour" id="life_end_hour" class="txt small time" /></td>
            </tr>

            <tr>
                <td class="required"><label class="validation">代用款项与面值：</label></td>
                <td class="vatop rowform">
                    <label><input type="checkbox" name="use_sincerity" id="use_sincerity" value="1" />诚意金</label>
                </td>
                <td class="required">
                    ￥<input type="text" name="sincerity_money" id="sincerity_money" class="txt" placeholder="0~200" />
                </td>
                <td class="required" colspan="2">设计模板：<input type="file" name="ignore_sincerity_temp" /></td>

            </tr>
            <tr>
                <td class="required">&nbsp;</td>
                <td class="vatop rowform">
                    <input type="checkbox" name="use_collateral" id="use_collateral" value="1" />买车担保金余款
                </td>
                <td class="required">
                    ￥<input type="text" name="collateral_money" id="collateral_money" class="txt" placeholder="0~300" />
                </td>
                <td class="required" colspan="2">
                    设计模板：<input type="file" name="ignore_collateral_temp" />
                </td>
            </tr>
            </tbody>
            <thead>
            <th><p class="title">设置代金券组</p></th>
            </thead>
            <tbody class="border">
            <tr>
                <td class="required">激活码位数：</td>
                <td class="vatop rowform" colspan="4">不限</td>
            </tr>
            <tr>
                <td class="required"><label class="validation">券组说明：</label></td>
                <td colspan="4">
                    <input type="text" class="txt big" name="remark" id="remark" />
                </td>
            </tr>
            </tbody>
            <thead><th></th></thead>
            <tfoot>
            <tr class="tfoot">
                <td colspan="5" style="text-align: center;" >
                    <button type="button" class="button button-primary button-small" onclick="submitGroup();">
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/Vouchers/group.js" charset="utf-8"></script>