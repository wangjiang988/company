<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    .table .required{width: 50px; margin: 0; padding: 0; }
    .subTable , .subTable td {border: 1px #000000 solid; text-align: center;}
    .subTable input[type=text]{width:80%;}
    .subTable .tr-header{background:#3F8DBF; text-align: center;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>地区关联设置</h3>
            <ul class="tab-base">
                <li><a href="<?=url('area','list')?>"><span>列表</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>查看</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="seller_form" action="<?=url('area','post')?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="area_id" id="area_id" value="<?=$output['find']['area_id'];?>" />
        <input type="hidden" name="ref_url" value="<?php echo getReferer();?>" />
        <table class="table tb-type2">
            <tr class="noborder">
                <td class="required">
                    <label for="status">地区:</label>
                </td>
                <td class="vatop rowform">
                    <?=getRegion($output['find']['area_parent_id'])?> &nbsp;
                    <?=getRegion($output['find']['area_id'])?>
                </td>
            </tr>
            <tr class="noborder">
                <td class="required">
                    <label class="validation" for="identity">当前默认车牌信息:</label>
                </td>
                <td class="vatop rowform">
                    <?=$output['find']['area_chepai']?>
                </td>
            </tr>
            <tr class="noborder">
                <td class="required">
                    <label class="validation" for="seller_name">是否限牌:</label>
                </td>
                <td class="vatop rowform"><?php echo empty($output['find']['area_xianpai'])?'否':'是'; ?></td>
            </tr>

            <tr class="noborder">
                <td class="required">
                    <label class="validation" for="member_truename">上牌排放标准:</label>
                </td>
                <td class="vatop rowform"><?=paifang($output['find']['area_biaozhun'])?></td>
            </tr>

            <tr class="noborder">
                <td width="80px">
                    <label class="validation" for="member_butie">该地政府是否对车辆置换提供补贴:</label>
                </td>
                <td class="vatop rowform"><?php echo empty($output['find']['area_butie'])?'否':'是'; ?></td>
            </tr>


            <tr>
                <td class="required" > <b>当地上牌特需文件</b>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table style="width: 70%;" class="subTable">
                        <thead>
                        <tr class="tr-header">
                            <td width="20%"><b>编号</b></td>
                            <td width="50%"><b>文件名称</b></td>
                        </tr>
                        </thead>
                        <tbody id="lilstFile">
                        <?php
                        $otherAll = explode('|',$output['find']['special_file']);
                        if($otherAll) {
                            foreach ($otherAll as $ok => $ov) {
                                ?>
                                <tr>
                                    <td><?=$ok ?></td>
                                    <td><?=$ov ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                        </tbody>
                    </table>
                </td>
            </tr>

            <tr class="noborder">
                <td class="required">车船使用税</td>
                <td id="divComUploadContainer">
                    <table class="subTable" style="width: 40%;">
                        <thead>
                        <tr class="tr-header">
                            <td width="50%"><b>排气量</b></td>
                            <td width="50%"><b>金额</b></td>
                        </tr>
                        </thead>
                        <tbody style="text-align: center;">
                        <?php
                        $taxAll = array();
                        if($output['find']['car_boat_tax']){
                            $taxAll = explode(',',$output['find']['car_boat_tax']);
                        }
                        ?>
                        <tr><td>1.0升(含以下)</td><td>￥<?=$taxAll[0] ?></td></tr>
                        <tr><td>1.0~1.6升(含)</td><td>￥<?=$taxAll[1] ?></td></tr>
                        <tr><td>1.6~2.0升(含)</td><td>￥<?=$taxAll[2] ?></td></tr>
                        <tr><td>2.0~2.5升(含)</td><td>￥<?=$taxAll[3] ?></td></tr>
                        <tr><td>2.5~3.0升(含)</td><td>￥<?=$taxAll[4] ?></td></tr>
                        <tr><td>3.0~4.0升(含)</td><td>￥<?=$taxAll[5] ?></td></tr>
                        <tr><td>4.0升以上</td><td>￥<?=$taxAll[6] ?></td></tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="required">特别提示:</td>
                <td><?=$output['find']['tips']?></td>
            </tr>
            <tr>
                <td class="required">备注:</td>
                <td><?=$output['find']['notes']?></td>
            </tr>
        </table>
    </form>
</div>