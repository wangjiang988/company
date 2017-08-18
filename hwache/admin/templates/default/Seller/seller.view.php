<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    label{ font-weight: bold;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?=$lang['index_title'];?></h3>
            <ul class="tab-base">
                <li><a href="<?=url('seller','list')?>"><span>列表</span></a></li>
                <li><a href="<?=url('seller','add')?>"><span>新增</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>编辑</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
        <table class="table tb-type2">
            <tbody>
            <tr>
                <td colspan="4" style="background:#0A8CD2; color: #ffffff; margin-left: 5px;"> | <b>帐号信息</b></td>
            </tr>
            <tr>
                <td width="required"><label for="status">启用状态:</label></td>
                <td class="vatop rowform">
                    <?php if($output['find']['status'] == '0'){ ?>未审核<?php } ?>
                    <?php if($output['find']['status'] == '1'){ ?>审核通过<?php } ?>
                    <?php if($output['find']['status'] == '2'){ ?>帐号冻结<?php } ?>
                </td>
                <td width="required"><label for="seller_name"><?=$lang['seller_name'];?>:</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['seller_name'];?>
                </td>
            </tr>

            <tr>
                <td width="required"><label for="member_truename">用户姓名:</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['member_truename'];?>
                </td>
                <td class="required"><label for="seller_card_num"><?=$lang['seller_card_num'];?>:</label></td>
                <td class="vatop rowform">
                    <?=setMSKstring($output['find']['seller_card_num']);?>
                </td>
            </tr>

            <tr>
                <td class="required"><label for="seller_bank_addr"><?php echo $lang['seller_bank_addr'];?>:</label></td>
                <td class="vatop rowform">
                    <?=getRegion($output['find']['seller_province_id']);?>
                    <?=getRegion($output['find']['seller_city_id']);?>
                    <?=$output['find']['seller_bank_addr']?>
                </td>

                <td class="required"><label for="seller_bank_account">卡号:</label></td>
                <td class="vatop rowform">
                    <?=setMSKstring($output['find']['seller_bank_account'])?>
                </td>
            </tr>

            <tr>
                <td class="required"><label for="seller_card_num">固定保证金:</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['basic_deposit'];?>
                </td>
                <td class="required"><label for="seller_card_num">授信额度:</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['credit_line'];?>
                </td>
            </tr>

            <tr>
                <td colspan="4" style="background:#0A8CD2; color: #ffffff; margin-left: 5px;"> | <b>其他信息</b></td>
            </tr>
            <tr>
                <td class="required"><label for="seller_sex"><?php echo $lang['seller_sex'];?>:</label></td>
                <td class="vatop rowform">
                    <?php echo ($output['find']['seller_sex'] == 1)?'男':'女'; ?>
                </td>

                <td class="required"><label for="seller_email"><?php echo $lang['seller_email'];?>:</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['seller_email'];?>
                </td>
            </tr>

            <tr>
                <td width="required"><label for="seller_phone"><?=$lang['seller_phone']?>:</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['seller_phone'];?>
                </td>
                <td class="required"><label for="member_address">用户联系地址:</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['member_address'];?>
                </td>
            </tr>

            <tr>
                <td class="required"><label for="seller_postcode"><?=$lang['seller_postcode'];?>:</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['seller_postcode'];?>
                </td>

                <td class="required"><label for="seller_weixin"><?=$lang['seller_weixin'];?>:</label></td>
                <td class="vatop rowform">
                    <?=$output['find']['seller_weixin'];?>
                </td>
            </tr>

            <tr>
                <td colspan="4" style="background:#0A8CD2; color: #ffffff; margin-left: 5px;"> | <b>其他联系方式</b>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table style="width: 80%;">
                        <thead>
                        <tr style="border: 1px #4cae4c solid;font-size: 14px; text-align: center;">
                            <td width="35%"><b>名称</b></td>
                            <td width="35%"><b>号码</b></td>
                            <td width="30%"><b>操作</b></td>
                        </tr>
                        </thead>
                        <tbody style="border: 1px #ccc solid;">
                        <?php
                        $otherAll = count($output['contact']);
                        if($otherAll) {
                            foreach ($output['contact'] as $ok => $ov) {
                                ?>
                                <tr>
                                    <td><?= $ov['name'] ?></td>
                                    <td><?= $ov['phone'] ?></td>
                                    <td></td>
                                </tr>
                                <?php
                            }
                        }else{
                            ?>
                            <tr><td colspan="3" align="center"><h1>没有记录。。。</h1></td></tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <td class="required"><label for="seller_photo"><?=$lang['seller_photo']?></label></td>
                <td id="divComUploadContainer">
                    <img src="<? echo C('upload_site_url').'/'.$output['find']['seller_photo'];?>" alt="" width="200">
                </td>
                <td class="vatop rowform"><label for="remarks">备注:</label></td>
                <td><?=$output['find']['remarks']?></td>
            </tr>
            </tbody>
        </table>
</div>