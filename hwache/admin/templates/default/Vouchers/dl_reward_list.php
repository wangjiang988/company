<style type="text/css">
    .tab-base em{ padding: 0 10px;}
    .search input.txt{width: 120px;}
    .search input.small{width: 63px;}
    .search input.big{width: 300px;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>代金券</h3>
            <ul class="tab-base">
                <li><a href="<?=url('hc_vouchers','promotion');?>"><span>代金券推广</span></a><em> | </em></li>
                <li><a href="javascript:;" class="current"><span>推广酬劳列表</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"><?php $search=$output['search']?></div>
    <form name="formSearch" id="searchFrom" action="" method="get">
        <input type="hidden" value="hc_vouchers" name="act">
        <input type="hidden" value="dl_release" name="op">
        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <th><label>推广代理编号</label></th>
                <td>
                    <input type="text" value="<?=$search['id'];?>" name="id" class="txt">
                    <label>推广代理用户名</label>
                    <input type="text" value="<?=$search['id'];?>" name="id" class="txt">
                    <label>手机号</label>
                    <input type="text" value="<?=$search['id'];?>" name="id" class="txt">
                    <label>姓名</label>
                    <input type="text" value="<?=$search['id'];?>" name="id" class="txt">
                </td>
            </tr>
            <tr>
                <th><label for="search_title">酬劳状态</label></th>
                <td>
                    <select name="province" onchange="setCity(this.value,'city')">
                        <option value="">全部</option>
                        <option value="">待申报</option>
                        <option value="">待开票</option>
                        <option value="">待转账</option>
                        <option value="">待记账</option>
                        <option value="">已完成</option>
                    </select>
                    <label for="model_id">酬劳年月</label>
                    <select name="year">
                        <option value="">--选择--</option>
                        <?php
                        for($i=date('Y');$i<date('Y')+5;$i++){
                            $checked = isSelected([$output['search']['year'],'==',$i],'select');
                            echo '<option value="'.$i.'" '.$checked.'>'.$i.'</option>';
                        }
                        ?>
                    </select>
                    <select name="month">
                        <option value="">--选择--</option>
                        <?php
                        for($j=1;$j<=date('t');$j++){
                            $checked = isSelected([$output['search']['month'],'==',$j],'select');
                            echo '<option value="'.$j.'" '.$checked.'>'.$j.'</option>';
                        }
                        ?>
                    </select>
                    <label>劳务费发票号</label>
                    <input type="text" value="<?=$output['search']['service_invoice'];?>" name="service_invoice" class="txt">
                    <label for="seller_jiaxb">银行凭证号</label>
                    <input type="text" value="<?=$output['search']['voucher_code'];?>" name="voucher_code" class="txt">
                    <label for="seller_jiaxb">记账凭证号：</label>
                    <input type="text" value="<?=$output['search']['accounting_voucher'];?>" name="accounting_voucher" class="txt">
                </td>
            </tr>
            <tr>
                <th></th>
                <td>
                    <div style="float:right; text-align:left; width:auto;">
                        <button type="submit" class="button button-primary button-small">查询</button>
                        <a href="<?=url('hc_vouchers','group')?>" class="button button-primary button-small">重置</a>
                        <a href="<?=url('hc_vouchers','exportGroup',$output['uri'])?>" class="button button-primary button-small">导出</a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th class="w24">投放编号</th>
            <th class="w60">投放时间</th>
            <th class="w48">投放条数</th>
            <th class="align-center">投放区域</th>
            <th class="align-center">券类别</th>
            <th class="align-center">代用款项</th>
            <th class="align-center">面值</th>
            <th>推广奖励</th>
            <th>激活率</th>
            <th>投放状态</th>
            <th class="w120 align-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?=$v['id'];?></td>
                    <td><?=$v['id'];?></td>
                    <td>
                        <?php

                        ?>
                    </td>
                    <td class="align-center"><?php

                        ?></td>
                    <td class="align-center"><?php

                        ?></td>
                    <td class="align-center">
                        <?php

                        ?>
                    </td>
                    <td class="nowrap align-center">
                        <?php

                        ?>
                    </td>
                    <td><?=$v['can_release_num'];?></td>
                    <td class="nowrap align-center">
                        <?php

                        ?>
                    </td>
                    <td class="nowrap align-center">
                        <?php
                        ?>
                    </td>
                    <td class="align-center">
                        <?php
                        ?>
                        <a href="<?=url('hc_vouchers','dl_view_release',['id'=>1])?>">酬劳办理</a> |
                        <a href="<?=url('hc_vouchers','dl_view_release',['id'=>1])?>">酬劳明细</a>
                    </td>
                </tr>
            <?php } ?>
        <?php }else { ?>
            <tr class="no_data">
                <td colspan="12"><?php echo $lang['nc_no_record'];?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <tr class="tfoot">
                <td>&nbsp;</td>
                <td colspan="12">
                    <div class="pagination"> <?php echo $output['page'];?></div>
                </td>
            </tr>
        <?php } ?>
        </tfoot>
    </table>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/Vouchers/group.js" charset="utf-8"></script>