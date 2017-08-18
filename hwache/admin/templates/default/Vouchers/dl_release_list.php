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
                <li><a href="javascript:;" class="current"><span>推广投放列表</span></a></li>
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
                <th><label for="search_title">投放编号</label></th>
                <td>
                    <input type="text" value="<?=$search['id'];?>" name="id" class="txt">
                    <label for="seller_name">投放条数</label>
                    <input type="text" value="<?=$search['release_total1'];?>" name="release_total1" class="txt small"> ~
                    <input type="text" value="<?=$search['release_total'];?>" name="release_tota2" class="txt small">
                    <label for="start_deposit">投放时间</label>
                    <input type="text" value="<?=$search['updated_at1'];?>" name="updated_at1" class="date"> ~
                    <input type="text" value="<?=$search['updated_at2'];?>" name="updated_at2" class="date">
                    <label for="start_credit">投放地区</label>
                    <select name="province" onchange="setCity(this.value,'city')">
                        <option value="">全国</option>
                        <?php
                        foreach($output['region'] as $region){
                            $selected = isSelected([$search['province'],'==',$region['area_id']],'select');
                            echo '<option value="'.$region['area_id'].'" '.$selected.'>'.$region['area_name'].'</option>';
                        }
                        ?>
                    </select>
                    <select name="city" id="city">
                        <option value="">请选择</option>
                        <?php
                        foreach($output['area'] as $city){
                            $checked = isSelected([$search['city'],'==',$region['area_id']],'select');
                            echo '<option value="'.$city['area_id'].'" '.$checked.'>'.$city['area_name'].'</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="search_jxb">券类别</label></th>
                <td>
                    <select name="type">
                        <option value="">  --不限--  </option>
                        <option value="1" <?=isSelected([$search['type'],'==',1],'select')?> >通用券</option>
                        <option value="2" <?=isSelected([$search['type'],'==',2],'select')?>>品类券</option>
                    </select>

                    <label for="name_phone">代用款项</label>
                    <select name="use_vouchers">
                        <option value="">  --不限--  </option>
                        <option value="1" <?=isSelected([$search['use_vouchers'],'==',1],'select')?> >诚意金</option>
                        <option value="2" <?=isSelected([$search['use_vouchers'],'==',2],'select')?>>买车担保金</option>
                        <option value="3" <?=isSelected([$search['use_vouchers'],'==',3],'select')?>>诚意金/买车担保金余款</option>
                    </select>

                    <label for="seller_name">推广奖励</label>
                    <select name="reward_type">
                        <option value="">  --不限--  </option>
                        <option value="1" <?=isSelected([$search['reward_type'],'==',1],'select')?>>按结算金额</option>
                        <option value="2" <?=isSelected([$search['reward_type'],'==',2],'select')?> >按结算订单数</option>
                    </select>
                    <label for="seller_name">激活率</label>
                    <input type="text" value="<?=$search['activated_rate1'];?>" name="activated_rate1" class="txt small"> ~
                    <input type="text" value="<?=$search['activated_rate2'];?>" name="activated_rate2" class="txt small">

                    <label for="start_deposit">投放状态</label>
                    <select name="status">
                        <option value="">  --不限--  </option>
                        <option value="1" <?=isSelected([$search['status'],'==',1],'select')?> >已投放</option>
                        <option value="2" <?=isSelected([$search['status'],'==',2],'select')?>>已失效</option>
                    </select>
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
                        <a href="<?=url('hc_vouchers','dl_view_release',['id'=>1])?>">查看</a>
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