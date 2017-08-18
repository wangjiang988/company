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
                <?php
                require_once('vouchers_nav.php');
                ?>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form name="formSearch" id="searchFrom" action="<?=url('hc_vouchers','release')?>" method="get">
        <input type="hidden" value="hc_vouchers" name="act">
        <input type="hidden" value="release" name="op">
        <input type="hidden" value="0" name="activated">
        <?php
        $search = $output['search'];
        ?>
        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <th><label for="search_title">申请投放编号</label></th>
                <td>
                    <input type="text" value="<?=$search['id'];?>" name="id" class="txt">
                    <label for="seller_name">代金券组编号</label>
                    <input type="text" value="<?=$search['group_id'];?>" name="group_id" class="txt">
                    <label for="name_phone">代用款项</label>
                    <select name="use_vouchers">
                        <option value="">  --不限--  </option>
                        <option value="1" <?=isSelected([$search['use_vouchers'],'==',1],'select')?> >诚意金</option>
                        <option value="2" <?=isSelected([$search['use_vouchers'],'==',2],'select')?>>买车担保金余款</option>
                        <option value="3" <?=isSelected([$search['use_vouchers'],'==',3],'select')?>>诚意金/买车担保金余款</option>
                    </select>


                    <label for="start_deposit">投放状态</label>
                    <select name="status">
                        <option value="">  --不限--  </option>
                        <option value="1" <?=isSelected([$search['status'],'==',1],'select')?> >待批准</option>
                        <option value="2" <?=isSelected([$search['status'],'==',2],'select')?>>待投放</option>
                        <option value="3" <?=isSelected([$search['status'],'==',3],'select')?>>已投放</option>
                        <option value="4" <?=isSelected([$search['status'],'==',4],'select')?>>已失效</option>
                        <option value="5" <?=isSelected([$search['status'],'==',5],'select')?>>未批准</option>
                    </select>
                    <label for="release_total">投放条数</label>
                    <input type="text" value="<?=$search['release_total1'];?>" name="release_total1" class="txt small">~
                    <input type="text" value="<?=$search['release_total2'];?>" name="release_total2" class="txt small">
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

                    <label for="brand_id">品牌</label>
                    <select name="brand_id" onchange="addBrand('#series_id',this.value,'#model_id')">
                        <option value="">--选择--</option>
                        <?php
                        foreach($output['brands'] as $brand){
                            $selected = isSelected([$search['brand_id'],'==',$brand['gc_id']],'select');
                            echo '<option value="'.$brand['gc_id'].'" '.$selected.'>'.$brand['gc_name'].'</option>';
                        }
                        ?>
                    </select>
                    <label for="series_id">车系</label>
                    <select name="series_id" id="series_id" onchange="addBrand('#model_id',this.value,'')">
                        <option value="">--选择--</option>
                        <?php
                        foreach($output['series'] as $serie){
                            $_selected = isSelected([$search['series_id'],'==',$serie['gc_id']],'select');
                            echo '<option value="'.$serie['gc_id'].'" '.$_selected.'>'.$serie['gc_name'].'</option>';
                        }
                        ?>
                    </select>
                    <label for="model_id">车型</label>
                    <select name="model_id" id="model_id">
                        <option value="">--选择--</option>
                        <?php
                        foreach($output['models'] as $model){
                            $checked = isSelected([$search['model_id'],'==',$model['gc_id']],'select');
                            echo '<option value="'.$model['gc_id'].'" '.$checked.'>'.$model['gc_name'].'</option>';
                        }
                        ?>
                    </select>
                    <label for="release_object">投放对象</label>
                    <select name="ignore_object">
                        <option value="">  --不限--  </option>
                        <option value="1" <?=isSelected([$search['ignore_object'],'==',1],'select')?> >所有客户</option>
                        <option value="2" <?=isSelected([$search['ignore_object'],'==',2],'select')?>>特定客户</option>
                        <option value="3" <?=isSelected([$search['ignore_object'],'==',3],'select')?>>一年内未买车的客户</option>
                        <option value="4" <?=isSelected([$search['ignore_object'],'==',4],'select')?>>三个月内新注册并且未买车的用户</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th><label for="seller_jiaxb">投放时间</label></th>
                <td>
                    <input type="text" value="<?=$search['created_at1'];?>" name="created_at1" class="date">~
                    <input type="text" value="<?=$search['created_at2'];?>" name="created_at2" class="date">

                    <div style="float:right; text-align:left; width:auto;">
                        <button type="submit" class="button button-primary button-small">搜索</button>
                        <a href="<?=url('hc_vouchers','release')?>" class="button button-primary button-small">重置</a>
                        <a href="<?=url('hc_vouchers','exportNotRelease',$output['uri'])?>" class="button button-primary button-small">导出</a>

                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th>申请投放编号</th>
            <th class="w24">代金券组编号</th>
            <th class="w48">券类别</th>
            <th class="align-center">品牌/车系/车型</th>
            <th class="align-center">代用款项</th>
            <th class="align-center">面值</th>
            <th class="align-center">申请投放条数</th>
            <th>投放对象</th>
            <th>投放条数</th>
            <th>投放时间</th>
            <th>状态</th>
            <th class="w120 align-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?=$v['id']; ?></td>
                    <td><?=$v['group_id']; ?></td>
                    <td><?=($v['type'])?'品类券':'通用券'; ?></td>
                    <td class="align-center">
                        <?php
                        if($v['type'] ==1){
                            echo getGoodsClass($v['brand_id']).getGoodsClass($v['series_id']).getGoodsClass($v['model_id']);
                        }else{
                            echo '-';
                        }
                        ?></td>
                    <td class="align-center">
                        <?php
                        if($v['use_collateral'] && $v['use_sincerity']){
                            echo '诚意金/买车担保金余款';
                        }else{
                            echo ($v['use_collateral']) ? '买车担保金余款' : '诚意金';
                        }
                        ?>
                    </td>
                    <td class="align-center">
                        <?php
                        if($v['use_collateral'] && $v['use_sincerity']){
                            echo '￥'.$v['collateral_money'].'/￥'.$v['sincerity_money'];
                        }else{
                            echo ($v['use_collateral']) ? '￥'.$v['collateral_money'] : '￥'.$v['sincerity_money'];
                        }
                        ?>
                    </td>
                    <td class="nowrap align-center"><?=empty($v['release_total_num'])?'不限':$v['release_total_num'];?></td>
                    <td>
                        <?php
                        $objectArr = ['所有客户','特定客户','一年内未买车的客户','三个月内新注册并且未买车的用户'];
                        echo $objectArr[$v['ignore_object']];
                        ?>
                    </td>
                    <td class="nowrap align-center"><?=($v['status']==2)?$v['release_total']:'';?></td>
                    <td class="nowrap align-center"><?=($v['status']==2)?$v['created_at']:''?></td>
                    <td>
                        <?php
                        if($v['status']==2 && empty($v['release_total'])){
                            echo "投放中";
                        }else{
                            $statusArr =['待批准','待投放','已投放','已失效','未批准'];
                            echo $statusArr[$v['status']];
                        }
                        ?>
                    </td>
                    <td class="align-center">
                        <?php
                        if(empty($v['status'])){
                            echo '<a href="'.url('hc_vouchers','app_release',['id'=>$v['id'],'approval'=>1]).'">审批</a> | ';
                        }
                        ?>
                        <a href="<?=url('hc_vouchers','app_release',['id'=>$v['id']]); ?>">查看</a>
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/region.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>