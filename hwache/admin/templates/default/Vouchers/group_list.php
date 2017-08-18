<style type="text/css">
    .tab-base em{ padding: 0 10px;}
    .search input.txt{width: 120px;}
    .search input.small{width: 63px;}
    .search input.big{width: 300px;}
    table .tab , table .tab tr{width: 100%; margin: 5px; }
    .tab td { height: 30px; line-height: 30px; color: #000; padding:5px;text-align:left; margin-left: 15px;}
    .tab .center {text-align: center;}
    .tab td.right{text-align: right; margin-right: 15px; width: 150px;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>代金券</h3>
            <ul class="tab-base">
                <?php
                require_once('vouchers_nav.php');
                ?>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form name="formSearch" id="searchFrom" action="<?=url('hc_vouchers','group')?>" method="get">
        <input type="hidden" value="hc_vouchers" name="act">
        <input type="hidden" value="group" name="op">
        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <th><label for="search_title">代金券组编号</label></th>
                <td>
                    <input type="text" value="<?=$output['search']['id'];?>" name="id" class="txt">
                    <label for="seller_name">激活类型</label>
                    <select name="activated_type">
                        <option value="">  --不限--  </option>
                        <option value="2" <?=isSelected([$output['search']['activated_type'],'==',2],'select')?> >需激活</option>
                        <option value="1" <?=isSelected([$output['search']['activated_type'],'==',1],'select')?>>免激活</option>
                    </select>
                    <label for="name_phone">代用款项</label>
                    <select name="use_vouchers">
                        <option value="">  --不限--  </option>
                        <option value="1" <?=isSelected([$output['search']['use_vouchers'],'==',1],'select')?> >诚意金</option>
                        <option value="2" <?=isSelected([$output['search']['use_vouchers'],'==',2],'select')?>>买车担保金余款</option>
                        <option value="3" <?=isSelected([$output['search']['use_vouchers'],'==',3],'select')?>>诚意金/买车担保金余款</option>
                    </select>
                    <label for="start_deposit">券组状态</label>
                    <select name="is_release">
                        <option value="">  --不限--  </option>
                        <option value="1" <?=isSelected([$output['search']['is_release'],'==',1],'select')?> >未投放</option>
                        <option value="2" <?=isSelected([$output['search']['is_release'],'==',2],'select')?>>部分投放</option>
                        <option value="3" <?=isSelected([$output['search']['is_release'],'==',3],'select')?>>全部投放</option>
                        <option value="4" <?=isSelected([$output['search']['is_release'],'==',4],'select')?>>已失效</option>
                    </select>
                    <label for="start_credit">每组条数</label>
                    <input type="text" value="<?=$output['search']['group_num1'];?>" name="group_num1" class="txt small">~
                    <input type="text" value="<?=$output['search']['group_num2'];?>" name="group_num2" class="txt small">
                </td>
            </tr>
            <tr>
                <th><label for="search_jxb">券类别</label></th>
                <td>
                    <select name="type">
                        <option value="">  --不限--  </option>
                        <option value="1" <?=isSelected([$output['search']['type'],'==',1],'select')?> >通用券</option>
                        <option value="2" <?=isSelected([$output['search']['type'],'==',2],'select')?>>品类券</option>
                    </select>

                    <label for="brand_id">品牌/车系/车型</label>
                    <select name="brand_id" onchange="addBrand('#series_id',this.value,'#model_id')">
                        <option value="">--选择--</option>
                        <?php
                        foreach($output['brands'] as $brand){
                            $selected = isSelected([$output['search']['brand_id'],'==',$brand['gc_id']],'select');
                            echo '<option value="'.$brand['gc_id'].'" '.$selected.'>'.$brand['gc_name'].'</option>';
                        }
                        ?>
                    </select>
                    <select name="series_id" id="series_id" onchange="addBrand('#model_id',this.value,'')">
                        <option value="">--选择--</option>
                        <?php
                        foreach($output['series'] as $serie){
                            $_selected = isSelected([$output['search']['series_id'],'==',$serie['gc_id']],'select');
                            echo '<option value="'.$serie['gc_id'].'" '.$_selected.'>'.$serie['gc_name'].'</option>';
                        }
                        ?>
                    </select>
                    <select name="model_id" id="model_id">
                        <option value="">--选择--</option>
                        <?php
                        foreach($output['models'] as $model){
                            $checked = isSelected([$output['search']['model_id'],'==',$model['gc_id']],'select');
                            echo '<option value="'.$model['gc_id'].'" '.$checked.'>'.$model['gc_name'].'</option>';
                        }
                        ?>
                    </select>
                    <label for="seller_jiaxb">可投条数</label>
                    <input type="text" value="<?=$output['search']['release_num1'];?>" name="release_num1" class="txt small">~
                    <input type="text" value="<?=$output['search']['release_num2'];?>" name="release_num2" class="txt small">
                </td>
            </tr>

            <tr>
                <th><label for="seller_jiaxb">有效使用开始时间</label></th>
                <td>
                    <input type="text" value="<?=$output['search']['life_start_time1'];?>" name="life_start_time1" class="date">~
                    <input type="text" value="<?=$output['search']['life_start_time2'];?>" name="life_start_time2" class="date">
                    <label for="seller_jiaxb">新增时间</label>
                    <input type="text" value="<?=$output['search']['created_at1'];?>" name="created_at1" class="date">~
                    <input type="text" value="<?=$output['search']['created_at2'];?>" name="created_at2" class="date">
                    <label for="seller_jiaxb">券组说明：</label>
                    <input type="text" value="<?=$output['search']['remark'];?>" name="remark" class="txt big">
                </td>
                </tr>

            <tr>
                <th>有效使用截止时间</th>
                <td>
                    <input type="text" value="<?=$output['search']['life_end_time1'];?>" name="life_end_time1" class="date">~
                    <input type="text" value="<?=$output['search']['life_end_time2'];?>" name="life_end_time2" class="date">

                    <div style="float:right; text-align:left; width:auto;">
                        <button type="submit" class="button button-primary button-small">搜索</button>
                        <a href="<?=url('hc_vouchers','group')?>" class="button button-primary button-small">重置</a>
                        <a href="<?=url('hc_vouchers','exportGroup',$output['uri'])?>" class="button button-primary button-small">导出</a>
                        <a href="<?=url('hc_vouchers','add_group',['activated'=>1])?>" class="button button-primary button-small">新增激活组</a>
                        <a href="<?=url('hc_vouchers','add_group')?>" class="button button-primary button-small">新增免激活组</a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th class="w24">代金券组编号</th>
            <th class="w60">激活类型</th>
            <th class="w48">券类别</th>
            <th class="align-center">品牌/车系/车型</th>
            <th class="align-center">代用款项</th>
            <th class="align-center">面值</th>
            <th class="align-center">每组条数</th>
            <th>可投放数</th>
            <th>有效期</th>
            <th>券组状态</th>
            <th class="w120 align-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?=$v['id']; ?></td>
                    <td><?=($v['activated_type'])?'需激活':'免激活'; ?></td>
                    <td><?=($v['type'])?'品类券':'通用券'; ?></td>
                    <td class="align-center"><?php
                        if($v['type'] ==1){
                            echo getGoodsClass($v['brand_id']).getGoodsClass($v['series_id']).getGoodsClass($v['model_id']);
                        }else{
                            echo '-';
                        }
                        ?></td>
                    <td class="align-center"><?php
                        if($v['use_collateral'] && $v['use_sincerity']){
                            echo '诚意金/买车担保金余款';
                        }else{
                            echo ($v['use_collateral']) ? '买车担保金余款' : '诚意金';
                        }
                        ?></td>
                    <td class="align-center">
                        <?php
                        if($v['use_collateral'] && $v['use_sincerity']){
                            echo '￥'.$v['sincerity_money'].'/￥'.$v['collateral_money'];
                        }else{
                            echo ($v['use_collateral']) ? '￥'.$v['collateral_money'] : '￥'.$v['sincerity_money'];
                        }
                        ?>
                    </td>
                    <td class="nowrap align-center">
                        <?php
                        $nums = ($v['use_collateral'] + $v['use_sincerity']) * $v['activated_total_num'];
                        echo empty($nums) ? '不限' : $nums;
                        ?>
                    </td>
                    <td><?php
                            echo empty($v['activated_type'])? '不限' : $v['can_release_num'];
                        ?></td>
                    <td class="nowrap align-center">
                        <?php
                        echo $v['life_start_time'].' ~ '. $v['life_end_time'];
                        ?>
                    </td>
                    <td class="nowrap align-center">
                        <?php
                        if($v['status'] ==2){
                            echo '失效';
                        }else{
                            $tfResult = isGroupRelease($v['id']);
                            echo $tfResult['msg'];
                        }
                        ?>
                    </td>
                    <td class="align-center">
                        <?php
                        if($v['status'] !=2) {
                            $url = url('hc_vouchers', 'app_release', ['group_id' => $v['id']]);
                            switch($tfResult['code']){
                                case 0:
                                    echo '<a href="' . $url . '">申请投放</a> | ';
                                    echo '<a href="javascript:;" onclick="failure(' . $v['id'] . ')">失效</a> | ';
                                    break;
                                case 2:
                                case 3:
                                    echo '<a href="' . $url . '">申请投放</a> | ';
                                    break;
                            }
                        }
                        ?>
                        <a href="<?=url('hc_vouchers','add_group',['id'=>$v['id']]); ?>">查看</a>
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/maskedinput/jquery.maskedinput.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/Vouchers/group.js" charset="utf-8"></script>