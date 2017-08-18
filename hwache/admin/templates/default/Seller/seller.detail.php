<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    .table .required{width: 100px; margin: 0; padding: 0;}
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
    <form id="seller_form" action="<?=url('seller','post')?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="seller_id" id="seller_id" value="<?=$output['find']['seller_id'];?>" />
        <input type="hidden" name="member_id" id="member_id" value="<?=$output['find']['member_id'];?>" />
        <input type="hidden" name="ref_url" value="<?php echo getReferer();?>" />
        <table class="table tb-type2">
            <tbody>
            <tr class="noborder">
                <td width="required">
                    <label class="validation" for="status">启用状态:</label>
                </td>
                <td class="vatop rowform">
                    <input name="status" <?php if($output['find']['status'] == '0'){ ?>checked="checked"<?php } ?>  value="0" type="radio">未审核
                    <input name="status" <?php if($output['find']['status'] == '1'){ ?>checked="checked"<?php } ?>  value="1" type="radio">审核通过
                    <input name="status" <?php if($output['find']['status'] == '2'){ ?>checked="checked"<?php } ?> value="2" type="radio">帐号冻结
               </td>
            </tr>
            <tr class="noborder">
                <td width="required">
                    <label class="validation" for="identity"><?=$lang['identity'];?>:</label>
                </td>
                <td class="vatop rowform">
                    <?php //empty($output['find']['identity'])?$lang['id_no']:$lang['id_yes'];?>
                    <select name="identity" id="identity">
                        <option value=""><?php echo $lang['nc_please_choose'];?>...</option>
                        <option <?php if($output['find']['identity'] == 0){ ?>selected='selected'<?php } ?> value="0">个人用户</option>
                        <option <?php if($output['find']['identity'] == 1){ ?>selected='selected'<?php } ?> value="1">企业用户</option>
                    </select>
                </td>
            </tr>
            <tr class="noborder">
                <td width="required">
                    <label class="validation" for="seller_name"><?=$lang['seller_name'];?>:</label>
                </td>
                <td class="vatop rowform">
                    <input type="hidden" name="seller_name" value="<?=$output['find']['seller_name'];?>" />
                    <?=$output['find']['seller_name'];?>
                </td>
            </tr>

            <tr class="noborder">
                <td width="required">
                    <label class="validation" for="member_truename">用户姓名:</label>
                </td>
                <td class="vatop rowform">
                    <input type="text" value="<?=$output['find']['member_truename'];?>" name="member_truename" class="txt">
                </td>
            </tr>

            <tr>
                <td class="required">
                    <label for="passwrod">设置初始密码:</label>
                </td>
                <td class="vatop rowform">
                    <input type="text" name="password" value="" class"txt" style="width:250px;">
                </td>
            </tr>

            <tr>
                <td class="required">
                    <label class="validation" for="member_mobile">手机号:</label>
                </td>
                <td class="vatop rowform">
                    <input type="text" value="<?=$output['find']['member_mobile'];?>" name="member_mobile" class"txt" style="width:250px;">
                </td>
            </tr>

            <tr>
                <td class="required">
                    <label class="validation" for="seller_card_num"><?=$lang['seller_card_num'];?>:</label>
                </td>
                <td class="vatop rowform">
                    <input type="text" value="<?=$output['find']['seller_card_num'];?>" name="seller_card_num" class="txt">
                </td>
            </tr>
            <tr>
                <td class="required">
                    <label class="validation" for="seller_bank_addr"><?php echo $lang['seller_bank_addr'];?>:</label>
                </td>
                <td class="vatop rowform">
                    <select name="seller_province_id" onchange="setCity(this.value,'opCity')">
                        <option value="">--请选择省份--</option>
                        <?php foreach($output['region'] as $rk => $rv) { ?>
                            <option <?=isSelected([$rv['area_id'],'==',$output['find']['seller_province_id']],'select');?> value="<?=$rv['area_id']?>"><?=$rv['area_name'];?></option>
                        <?php } ?>
                    </select>
                    <select name="seller_city_id" id="opCity">
                        <option value="">--请选择城市--</option>
                        <?php foreach($output['area'] as $ak => $av) { ?>
                            <option <?=isSelected([$av['area_id'],'==',$output['find']['seller_city_id']],'select');?> value="<?=$av['area_id']?>"><?=$av['area_name'];?></option>
                        <?php } ?>
                    </select>
                     <br />
                    <input type="text" class="txt" name="seller_bank_addr" placeholder="填写开户行地址" id="bankAddr" value="<?=$output['find']['seller_bank_addr']?>" />
                </td>
            </tr>
            <tr>
                <td class="required"><label class="validation" for="seller_bank_account">卡号:</label></td>
                <td class="vatop rowform">
                    <input type="text" class="txt" name="seller_bank_account" value="<?=$output['find']['seller_bank_account']?>" />
                </td>
            </tr>

            <tr>
                <td class="required"><label class="validation" for="basic_deposit">固定保证金:</label></td>
                <td class="vatop rowform">
                    <input type="text" class="txt" name="basic_deposit" value="<?=$output['find']['basic_deposit']?>" />
                </td>
            </tr>
            <tr>
                <td class="required"><label class="validation" for="credit_line">授信额度:</label></td>
                <td class="vatop rowform">
                    <input type="text" class="txt" name="credit_line" value="<?=$output['find']['credit_line']?>" />
                </td>
            </tr>



            <tr>
                <td class="required" colspan="2" style="background:#8c8c8c; color: #ffffff; margin-left: 5px;"> | <b>其他信息</b></td>
            </tr>
            <tr>
                <td class="required"><?php echo $lang['seller_sex'];?>:</td>
                <td class="vatop rowform">
                    <input type="radio" <?php echo isSelected([$output['find']['seller_sex'],'==',1],'radio')?> value="1" name="seller_sex">男
                    <input type="radio" <?php echo isSelected([$output['find']['seller_sex'],'==',2],'radio')?> value="2" name="seller_sex">女
                </td>
            </tr>
            <tr>
                <td class="required"><?php echo $lang['seller_email'];?>:</td>
                <td class="vatop rowform">
                    <input type="text" value="<?=$output['find']['seller_email'];?>" name="seller_email" class="txt">
                </td>
            </tr>

            <tr class="noborder">
                <td width="required">
                    <label for="seller_phone"><?=$lang['seller_phone']?>:</label>
                </td>
                <td class="vatop rowform">
                    <input type="text" value="<?=$output['find']['seller_phone'];?>" name="seller_phone" class="txt">
                </td>
            </tr>

            <tr>
                <td class="required">用户联系地址:</td>
                <td class="vatop rowform">
                    <input type="text" value="<?=$output['find']['member_address'];?>" name="member_address" class="txt">
                </td>
            </tr>
            <tr>
                <td class="required"><?=$lang['seller_postcode'];?>:</td>
                <td class="vatop rowform">
                    <input type="text" value="<?=$output['find']['seller_postcode'];?>" name="seller_postcode" class="txt">
                </td>
            </tr>
            <tr>
                <td class="required"><?=$lang['seller_weixin'];?>:</td>
                <td class="vatop rowform">
                    <input type="text" value="<?=$output['find']['seller_weixin'];?>" name="seller_weixin" class="txt">
                </td>
            </tr>
            <tr>
                <td class="required" colspan="2" style="background:#8c8c8c; color: #ffffff; margin-left: 5px;"> | <b>其他联系方式</b>
                <span> <button type="button" onclick="dialogDiv()"> 添加 </button> </span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table style="width: 50%;">
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
                                    <td><a href="javascript:;" onclick="delOther(<?=$ov['id']?>);">删除</a></td>
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

            <tr class="noborder">
                <td class="required"><?=$lang['seller_photo']?></td>
                <td id="divComUploadContainer">
                    <input type="file" multiple="multiple" id="fileupload" name="seller_photo" />
                    <img src="<? echo C('upload_site_url').'/'.$output['find']['seller_photo'];?>" alt="" width="200">
                </td>
            </tr>
            <tr>
                <td class="required">备注:</td>
                <td><textarea name="remarks" rows="7" cols="80" ><?=$output['find']['remarks']?></textarea></td>
            </tr>

            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td colspan="15" >
                    <button type="submit" class="btn" id="submitBtn"> <?=$lang['nc_submit']?> </button>
                </td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script>
    var del_url = "<?=url('seller','delOther')?>";
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/region.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/diy_validate.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/Seller/edit.js" charset="utf-8"></script>