<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>地区关联设置</h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span>列表</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="searchFrom" action="<?=url('area','list')?>">
        <input type="hidden" value="area" name="act">
        <input type="hidden" value="list" name="op">
        <table class="tb-type1 noborder search" style="width:100%;">
            <tr>
                <th width="120"><label for="search_title">省/直辖市/自治区</label></th>
                <td>
                    <div style="float: left; text-align: left; width: 70%">
                        <select name="province_id" onchange="setCity(this.value,'opCity')">
                            <option value="">--请选择省份--</option>
                            <?php foreach($output['region'] as $rk => $rv) { ?>
                                <option <?=isSelected([$rv['area_id'],'==',$output['search']['province_id']],'select');?> value="<?=$rv['area_id']?>"><?=$rv['area_name'];?></option>
                            <?php } ?>
                        </select>
                        <select name="city_id" id="opCity">
                            <option value="">--请选择城市--</option>
                            <?php foreach($output['area'] as $ak => $av) { ?>
                                <option <?=isSelected([$av['area_id'],'==',$output['search']['city_id']],'select');?> value="<?=$av['area_id']?>"><?=$av['area_name'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div style="float:right; text-align:left; width: 20%;">
                        <button type="submit" class="btn-search "><?php echo $lang['nc_query'];?></button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn" onclick="resetForm();">重置</button>
                    </div>
                </td>
            </tr>
        </table>
    </form>

    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th class="w24"><?=$lang['area_id'];?></th>
            <th class="w80">地区名称</th>
            <th class="w48">车牌</th>
            <th class="align-center">排放</th>
            <th class="align-center">限牌</th>
            <th class="align-center">车船税<br />0~1.0L</th>
            <th class="align-center">车船税<br />1.0~1.6L</th>
            <th class="align-center">车船税<br />1.6~2.0L</th>
            <th class="align-center">车船税<br />2.0~2.5L</th>
            <th class="align-center">车船税<br />2.5~3.0L</th>
            <th class="align-center">车船税<br />3.0~4.0L</th>
            <th class="align-center">车船税<br />4.0L以上</th>
            <th class="align-center">特需文件</th>
            <th>特别提示</th>
            <th class="w60 align-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?=$v['area_id']; ?></td>
                    <td><?php echo getRegion($v['area_parent_id'])?>&nbsp;<?=$v['area_name']; ?></td>
                    <td><?=$v['area_chepai']; ?></td>
                    <td class="align-center"><?=paifang($v['area_biaozhun']);?></td>
                    <td class="nowrap align-center"><?php echo empty($v['area_xianpai'])?'否':'是'; ?></td>
                    <td><?php echo getArrIndex($v['car_boat_tax'],0); ?></td>
                    <td><?php echo getArrIndex($v['car_boat_tax'],1); ?></td>
                    <td><?php echo getArrIndex($v['car_boat_tax'],2); ?></td>
                    <td><?php echo getArrIndex($v['car_boat_tax'],3); ?></td>
                    <td><?php echo getArrIndex($v['car_boat_tax'],4); ?></td>
                    <td><?php echo getArrIndex($v['car_boat_tax'],5); ?></td>
                    <td><?php echo getArrIndex($v['car_boat_tax'],6); ?></td>
                    <td><?php echo getArrIndex($v['special_file'],null,'explode','|'); ?></td>
                    <td><?php echo empty($v['tips'])?'无':'有'; ?></td>

                    <td class="align-center">
                        <a href="<?=url('area','view',['id'=>$v['area_id']]); ?>">查看</a>
                        |
                        <a href="<?=url('area','edit',['id'=>$v['area_id']]); ?>"><?php echo $lang['nc_edit'];?></a>
                    </td>
                </tr>
            <?php } ?>
        <?php }else { ?>
            <tr class="no_data">
                <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <tr class="tfoot">
                <td>&nbsp;</td>
                <td colspan="16">
                    <div class="pagination"> <?php echo $output['page'];?></div>
                </td>
            </tr>
        <?php } ?>
        </tfoot>
    </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/region.js" charset="utf-8"></script>
<script>
    function resetForm(){
        $('#searchFrom').find(':input').not(':button, :submit, :reset').val('').removeAttr('checked').removeAttr('selected');
        location.href = "<?=url('area','list')?>";
    }
</script>
