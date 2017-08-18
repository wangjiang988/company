<style type="text/css">
    .tab-base em{ padding: 0 10px;}
    .search input.txt{width: 120px;}
    .search input.small{width: 63px;}
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
    <form name="formSearch" id="searchFrom" action="<?=url('vouchers','index')?>" method="get">
        <input type="hidden" value="hc_vouchers" name="act">
        <input type="hidden" value="index" name="op">
        <?php $search = $output['search']?>
        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <th><label>代金券编号</label></th>
                <td>
                    <input type="text" value="<?=$search['voucher_sn'];?>" name="voucher_sn" class="txt">
                    <label>申请投放编号</label>
                    <input type="text" value="<?=$search['id'];?>" name="id" class="txt">
                    <label>券类别</label>
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
                    <label for="release_object">投放对象</label>
                    <select name="release_object">
                        <option value="">  --不限--  </option>
                        <option value="1" <?=isSelected([$search['release_object'],'==',1],'select')?> >华车内部</option>
                        <option value="2" <?=isSelected([$search['release_object'],'==',2],'select')?>>推广代理</option>
                        <option value="3" <?=isSelected([$search['release_object'],'==',3],'select')?>>华车指导</option>
                    </select>
                    <input class="txt" name="proxy_name" value="<?=$search['proxy_name']?>"  />

                </td>
            </tr>
            <tr>
                <th> <label>券状态</label></th>
                <td>
                    <select name="status">
                        <option value="">  --不限--  </option>
                        <option value="1" <?=isSelected([$search['status'],'==',1],'select')?> >未生效</option>
                        <option value="2" <?=isSelected([$search['status'],'==',2],'select')?>>未使用</option>
                        <option value="3" <?=isSelected([$search['status'],'==',3],'select')?>>已过期</option>
                        <option value="4" <?=isSelected([$search['status'],'==',4],'select')?>>已使用</option>
                        <option value="5" <?=isSelected([$search['status'],'==',5],'select')?>>已结算</option>
                    </select>

                    <label>来源</label>
                    <select name="source">
                        <option value="">  --全部--  </option>
                        <option value="1" <?=isSelected([$search['source'],'==',1],'select')?> >激活码</option>
                        <option value="2" <?=isSelected([$search['source'],'==',2],'select')?>>母代金券</option>
                        <option value="3" <?=isSelected([$search['source'],'==',3],'select')?>>免激活</option>
                        <option value="4" <?=isSelected([$search['source'],'==',4],'select')?>>平台</option>
                    </select>

                    <label>来源编号</label>
                    <input class="txt" name="source_sn" value="<?=$search['source_sn']?>"  />
                    <label>投放区域</label>
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
                    <label>结算金额</label>
                    <input type="text" value="<?=$search['settlement1'];?>" name="settlement1" class="txt small">~
                    <input type="text" value="<?=$search['settlement2'];?>" name="settlement2" class="txt small">
                </td>
            </tr>
            <tr>
                <th><label>结算时间</label></th>
                <td>
                    <input type="text" value="<?=$search['settlement_time1'];?>" name="settlement_time1" class="date">~
                    <input type="text" value="<?=$search['settlement_time2'];?>" name="settlement_time2" class="date">

                    <label>客户会员号</label>
                    <input type="text" value="<?=$search['user_name'];?>" name="user_name" class="txt">
                    <label>客户手机</label>
                    <input type="text" value="<?=$search['user_mobile'];?>" name="user_mobile" class="txt small">
                    <label>上牌区域</label>
                    <select name="sp_province" onchange="setCity(this.value,'sp_city')">
                        <option value="">全国</option>
                        <?php
                        foreach($output['region'] as $_region){
                            $_selected = isSelected([$search['sp_province'],'==',$_region['area_id']],'select');
                            echo '<option value="'.$_region['area_id'].'" '.$_selected.'>'.$_region['area_name'].'</option>';
                        }
                        ?>
                    </select>
                    <select name="sp_city" id="sp_city">
                        <option value="">请选择</option>
                        <?php
                        foreach($output['sp_area'] as $_city){
                            $checked = isSelected([$search['sp_city'],'==',$_city['area_id']],'select');
                            echo '<option value="'.$_city['area_id'].'" '.$checked.'>'.$_city['area_name'].'</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="brand_id">购买车型</label></th>
                <td>
                    <select name="brand_id" onchange="addBrand('#series_id',this.value,'#model_id')">
                        <option value="">--选择--</option>
                        <?php
                        foreach($output['brands'] as $brand){
                            $selected = isSelected([$search['brand_id'],'==',$brand['gc_id']],'select');
                            echo '<option value="'.$brand['gc_id'].'" '.$selected.'>'.$brand['gc_name'].'</option>';
                        }
                        ?>
                    </select>
                    <select name="series_id" id="series_id" onchange="addBrand('#model_id',this.value,'')">
                        <option value="">--选择--</option>
                        <?php
                        foreach($output['series'] as $serie){
                            $_selected = isSelected([$search['series_id'],'==',$serie['gc_id']],'select');
                            echo '<option value="'.$serie['gc_id'].'" '.$_selected.'>'.$serie['gc_name'].'</option>';
                        }
                        ?>
                    </select>
                    <select name="model_id" id="model_id">
                        <option value="">--选择--</option>
                        <?php
                        foreach($output['models'] as $model){
                            $checked = isSelected([$search['model_id'],'==',$model['gc_id']],'select');
                            echo '<option value="'.$model['gc_id'].'" '.$checked.'>'.$model['gc_name'].'</option>';
                        }
                        ?>
                    </select>
                    <label>订单号</label>
                    <input type="text" value="<?=$search['order_sn'];?>" name="order_sn" class="txt">
                    <label for="start_deposit">使用时间</label>
                    <input type="text" value="<?=$search['use_time1'];?>" name="use_time1" class="date">~
                    <input type="text" value="<?=$search['use_time2'];?>" name="use_time2" class="date">
                    <label for="seller_jiaxb">过期时间</label>
                    <input type="text" value="<?=$search['expired_at1'];?>" name="expired_at1" class="date">~
                    <input type="text" value="<?=$search['expired_at2'];?>" name="expired_at2" class="date">
                </td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <td>
                    <div style="float:right; text-align:left; width:auto;">
                        <button type="submit" class="button button-primary button-small">搜索</button>
                        <a href="<?=url('hc_vouchers','index')?>" class="button button-primary button-small">重置</a>
                        <a href="<?=url('hc_vouchers','exportVoucher',$output['uri'])?>" class="button button-primary button-small">导出</a>

                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th class="w85">代金券编号</th>
            <th class="w60">来源</th>
            <th class="w48">来源编号</th>
            <th class="align-center">申请投放编号</th>
            <th class="align-center">客户会员号</th>
            <th class="align-center">券类别</th>
            <th class="align-center">代用款项</th>
            <th>面值</th>
            <th>结算金额</th>
            <th>结算时间</th>
            <th>投放区域</th>
            <th>上牌地区</th>
            <th>券状态</th>
            <th class="w120 align-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?=$v['voucher_sn']; ?></td>
                    <td><?=getVouchersSource($v['id'])?></td>
                    <td><?=getVouchersSourceCode($v['id'])?></td>
                    <td class="align-center"><?=$v['tf_id'];?></td>
                    <td class="align-center"><?=$v['user_id'];?></td>
                    <td class="align-center"><?=($v['type'])?'品类券':'通用券';?></td>
                    <td>
                        <?php
                        if($v['use_collateral'] && $v['use_sincerity']){
                            echo '诚意金/买车担保金余款';
                        }else{
                            echo ($v['use_collateral']) ?  '买车担保金余款' :'诚意金';
                        }
                        ?>
                    </td>
                    <td class="nowrap align-center">
                        <?php
                        if($v['use_collateral'] && $v['use_sincerity']){
                            echo '￥'.$v['collateral_money'].'/￥'.$v['sincerity_money'];
                        }else{
                            echo ($v['use_collateral']) ? '￥'.$v['collateral_money'] : '￥'.$v['sincerity_money'];
                        }
                        ?>
                    </td>
                    <td>TODO</td>
                    <td class="nowrap align-center">TODO</td>
                    <td class="nowrap align-center">
                    <?php
                    echo ($v['province']==0) ? '全国' : getRegion($v['province']).getRegion($v['city']);
                    ?>
                    </td>
                    <td class="nowrap align-center">
                        TODO
                    </td>
                    <td>
                        <?php
                        $statusArr = ['未生效','未使用','已过期','已使用','已结算'];
                        echo $statusArr[$v['status']];
                        ?>
                    </td>
                    <td class="align-center">
                        <a href="<?=url('hc_vouchers','view',['id'=>$v['id']]); ?>">查看</a>
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
