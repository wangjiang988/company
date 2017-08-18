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
                <li><a href="JavaScript:void(0);" class="current"><span>新增</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="seller_form" action="<?=url('seller','post')?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="seller_id" id="seller_id" value="<?=$output['find']['seller_id'];?>" />
        <input type="hidden" name="member_id" id="member_id" value="<?=$output['find']['member_id'];?>" />
        <input type="hidden" name="ref_url" value="<?php echo getReferer();?>" />
        <table class="table tb-type2">
            <tbody>
            <tr class="noborder">
                <td width="required">
                    <label class="validation" for="identity"><?=$lang['identity'];?>:</label>
                </td>
                <td class="vatop rowform">
                    <select name="identity" id="identity">
                        <option value=""><?php echo $lang['nc_please_choose'];?>...</option>
                        <option value="0">个人用户</option>
                        <option value="1">企业用户</option>
                    </select>
                </td>
            </tr>
            <tr class="noborder">
                <td width="required">
                    <label class="validation" for="seller_name"><?=$lang['seller_name'];?>:</label>
                </td>
                <td class="vatop rowform">
                    <input type="text" id="seller_name" name="seller_name" class="txt">
                </td>
            </tr>

            <tr class="noborder">
                <td width="required">
                    <label class="validation" for="member_truename">用户姓名:</label>
                </td>
                <td class="vatop rowform">
                    <input type="text" name="member_truename" class="txt">
                </td>
            </tr>

            <tr class="noborder">
                <td width="required">
                    <label class="validation" for="member_mobile">手机号:</label>
                </td>
                <td class="vatop rowform">
                    <input type="text" name="member_mobile" class="txt">
                </td>
            </tr>
            <tr>
                <td class="required">
                    <label class="validation" for="passwrod">设置初始密码:</label>
                </td>
                <td class="vatop rowform">
                    <input type="password" name="password" class"txt" style="width:250px;">
                </td>
            </tr>
            <tr>
                <td class="required">
                    <label class="validation" for="seller_card_num"><?=$lang['seller_card_num'];?>:</label>
                </td>
                <td class="vatop rowform">
                    <input type="text" name="seller_card_num" class="txt">
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
                            <option value="<?=$rv['area_id']?>"><?=$rv['area_name'];?></option>
                        <?php } ?>
                    </select>
                    <select name="seller_city_id" id="opCity">
                        <option value="">--请选择城市--</option>
                    </select>
                    <br />
                    <input type="text" class="txt" name="seller_bank_addr" placeholder="填写开户行地址" id="bankAddr" value="<?=$output['find']['seller_bank_addr']?>" />
                </td>
            </tr>
            <tr>
                <td class="required"><label class="validation" for="seller_bank_account">卡号:</label></td>
                <td class="vatop rowform">
                    <input type="text" class="txt" name="seller_bank_account" />
                </td>
            </tr>

            <tr>
                <td class="required"><label class="validation" for="basic_deposit">固定保证金:</label></td>
                <td class="vatop rowform">
                    <input type="text" class="txt" name="basic_deposit" />
                </td>
            </tr>
            <tr>
                <td class="required"><label class="validation" for="credit_line">授信额度:</label></td>
                <td class="vatop rowform">
                    <input type="text" class="txt" name="credit_line" />
                </td>
            </tr>

            <tr>
                <td class="required" colspan="2" style="background:#8c8c8c; color: #ffffff; margin-left: 5px;"> | <b>其他信息</b></td>
            </tr>
            <tr>
                <td class="required"><?=$lang['seller_sex'];?>:</td>
                <td class="vatop rowform">
                    <input type="radio" value="1" name="seller_sex">男
                    <input type="radio" value="2" name="seller_sex">女
                </td>
            </tr>
            <tr>
                <td class="required"><?=$lang['seller_email'];?>:</td>
                <td class="vatop rowform">
                    <input type="text" name="seller_email" class="txt">
                </td>
            </tr>
            <tr>
                <td width="required"><?=$lang['seller_phone']?>:</td>
                <td class="vatop rowform">
                    <input type="text" name="seller_phone" class="txt" placeholder="固定电话">
                </td>
            </tr>
            <tr>
                <td class="required">用户联系地址:</td>
                <td class="vatop rowform">
                    <input type="text" name="member_address" class="txt">
                </td>
            </tr>
            <tr>
                <td class="required"><?=$lang['seller_postcode'];?>:</td>
                <td class="vatop rowform">
                    <input type="text" name="seller_postcode" class="txt">
                </td>
            </tr>
            <tr>
                <td class="required"><?=$lang['seller_weixin'];?>:</td>
                <td class="vatop rowform">
                    <input type="text" name="seller_weixin" class="txt">
                </td>
            </tr>
            <tr class="noborder">
                <td class="required"><?=$lang['seller_photo']?></td>
                <td id="divComUploadContainer">
                    <input type="file" multiple="multiple" id="fileupload" name="seller_photo" /></td>
            </tr>
            <tr>
                <td class="required">备注:</td>
                <td><textarea name="remarks" rows="7" cols="80" ></textarea></td>
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
    var _checkUrl = "<?=url('seller','checkSeller')?>";
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/region.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/diy_validate.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/Seller/add.js" charset="utf-8"></script>